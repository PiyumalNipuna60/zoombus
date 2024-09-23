<?php

namespace App\Http\Controllers;

use App\BalanceUpdates;
use App\Cart;
use App\Http\Controllers\Api\QRScannerController;
use App\Http\Controllers\Payments\CreditCardController;
use App\Http\Controllers\Payments\PayPalClient;
use App\Notifications\RemovedFromCart;
use App\Notifications\TicketOrder;
use App\RemainingSeats;
use App\ReservedSeats;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Routes;
use App\Sales;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Auth\RegisterController;
use Hashids\Hashids;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\Lang;
use Mcamara\LaravelLocalization\LaravelLocalization;


class BookingController extends ValidationController
{

    public function __construct()
    {
        parent::__construct();
    }

    public static function store($data)
    {
        return Sales::create($data);
    }

    public static function update($data)
    {
        $upData = Arr::except($data, ['id']);
        return Sales::where('id', $data['id'])->update($upData);
    }

    protected function validator(array $data)
    {
        $after60 = Carbon::now()->addDays(60)->format('Y-m-d');
        $routeId = Routes::with('vehicles')->where('id', $data['route_id'] ?? 0)->first()->toArray();
        $numberOfSeats = $routeId['vehicles']['number_of_seats'] ?? 0;
        if (empty($data['departure_date'])) {
            $depD = $routeId['departure_date'];
            $data['departure_date'] = Carbon::parse($depD)->format('Y-m-d');
        }
        $tsQ = Sales::where('route_id', $data['route_id'] ?? 0)->whereHas('routes', function ($q) use ($data) {
            $q->whereDepartureDate($data['departure_date']);
        });
        if ($data['action'] == "cart" && Auth::check()) {
            $tsQ->where('user_id', Auth::user()->id);
        }
        else {
            $tsQ->status([1, 3]);
        }

        $routeExists = (!Routes::active()->whereId($data['route_id'] ?? 0)->whereHas('user.driver', function ($q) {
            $q->where('status', 1);
        })->whereHas('vehicles', function ($q) {
            $q->where('status', 1);
        })->where(function ($q) {
            $q->where(function ($qu) {
                $qu->whereRaw('timestamp(departure_date, departure_time) >= now() + INTERVAL 1 HOUR');
            });
        })->exists()) ? Rule::in([0]) : null;

        $takenSeats = $tsQ->get(['seat_number'])->toArray();


        $takenPreSeats = ReservedSeats::whereRouteId($data['route_id'] ?? 0)->get('seat_number')->toArray();
        $vals = [
            'action' => 'required|string|' . Rule::in(['buy', 'cart']),
            'route_id' => 'required|integer|' . $routeExists,
            'departure_date' => 'required|date|after_or_equal:today|before:' . $after60,
            'payment_method' => 'required|integer|' . Rule::in([1, 2]),
            'password.*' => 'required|string|min:5',
            'phone_number' => 'required|array',
            'phone_number.*' => 'required|phone:AUTO|distinct',
            'gender_id' => 'required|array',
            'gender_id.*' => 'required|string|' . Rule::in([1, 2]),
            'name' => 'required|array',
            'email' => 'nullable|array',
            'name.*' => 'required|string|max:255',
            'seat_number' => 'required|array',
            'seat_number.*' => 'required|numeric|distinct|between:1,' . $numberOfSeats . '|' .
                Rule::notIn(array_column($takenSeats, 'seat_number')) . '|' .
                Rule::notIn(array_column($takenPreSeats, 'seat_number')),
            'user_id' => 'sometimes|required|integer|' . Rule::exists('users', 'id'),
        ];


        if (!empty($data['phone_number'])) {
            foreach ($data['phone_number'] as $key => $phone) {
                if (User::wherePhoneNumber($phone ?? 0)->whereNotNull('email')->exists()) {
                    $vals['email.' . $key] = 'nullable|email|distinct|' .
                        Rule::exists('users', 'email')->where('phone_number', $phone);
                }
                else {
                    $vals['email.' . $key] = 'nullable|email|distinct|' .
                        Rule::unique('users', 'email');
                }
            }
        }
        else {
            $vals['email.*'] = 'nullable|email|distinct|' .
                Rule::unique('users', 'email');
        }


        return Validator::make($data, $vals);
    }


    public function start(Request $request)
    {
        $data = $request->only(['route_id', 'payment_method', 'phone_number', 'name', 'seat_number', 'gender_id', 'email', 'action']);
        if (Auth::check()): $data['user_id'] = Auth::user()->id; endif;
        $response = ValidationController::response($this->validator($data));
        if ($response->original['status'] == 1) {
            $password = Hash::make(Controller::uniqueString(20));
            $hashit = new Hashids('', 10);
            $hashcart = new Hashids('', 11);
            $hashti = new Hashids('', 10, '0123456789ABCXYZ');
            $priku = Routes::with('citiesFrom', 'citiesFrom.translated', 'citiesTo', 'citiesTo.translated')->where('id', $data['route_id'])->first()->toArray();
            $price = $priku['price'];
            $currency = $priku['currency_id'];
            $total = count($data['phone_number']) * $price;

            if (!Auth::check() && $data['action'] == 'cart') {
                return response()->json(['status' => 0, 'text' => Lang::get('validation.please_log_in_to_add_to_cart')]);
            }

            foreach ($data['phone_number'] as $key => $val) {
                if (!User::where('phone_number', $val)->exists()) {
                    $userData[$key]['phone_number'] = $val;
                    $userData[$key]['name'] = $data['name'][$key];
                    $userData[$key]['gender_id'] = $data['gender_id'][$key];
                    $userData[$key]['password'] = $password;
                    $userData[$key]['email'] = $data['email'][$key] ?? null;
                    $userData[$key]['locale'] = config('app.locale');
                    $id = RegisterController::store($userData[$key])->id;
                }
                else {
                    $id = User::where('phone_number', $val)->first(['id'])->toArray()['id'];
                }
                $jData[$key]['route_id'] = $data['route_id'];
                $jData[$key]['departure_date'] = $priku['departure_date'];
                $jData[$key]['payment_method'] = $data['payment_method'];
                $jData[$key]['seat_number'] = $data['seat_number'][$key];
                $jData[$key]['user_id'] = $id;
                $jData[$key]['price'] = $price;
                $jData[$key]['currency_id'] = $currency;
                $insert = $this->store($jData[$key]);
                $uData[$key]['id'] = $insert->id;
                $uData[$key]['ticket_number'] = $hashti->encode($insert->id);
                $uData[$key]['transaction_id'] = $hashit->encode($uData[0]['id']);
                $this->update($uData[$key]);
                if ($data['payment_method'] == 2) {
                    $ppD[$key]['ticket_number'] = $hashti->encode($insert->id);
                    $ppD[$key]['from'] = $priku['cities_from']['translated']['name'];
                    $ppD[$key]['routeNumber'] = $priku['cities_from']['code'] . $priku['id'];
                    $ppD[$key]['to'] = $priku['cities_to']['translated']['name'];
                    $ppD[$key]['price'] = $price;
                }
                if ($data['action'] == "cart" && Auth::check()) {
                    $cData[$key]['sale_id'] = $insert->id;
                    $cData[$key]['user_id'] = Auth::user()->id ?? 0;
                    if (Cart::where('user_id', $cData[$key]['user_id'])->exists()) {
                        $getFirstCartId = Cart::where('user_id', $cData[$key]['user_id'])->first(['transaction_id'])->transaction_id;
                        $cData[$key]['transaction_id'] = $getFirstCartId;
                    }
                    else {
                        $cData[$key]['transaction_id'] = $hashcart->encode($uData[0]['id']);
                    }
                    CartController::store($cData[$key]);
                }
            }

            if ($data['action'] == "buy") {
                if ($data['payment_method'] == 1) {
                    //return form for Cartu Payments
                    $cc = new CreditCardController();
                    //TODO: credit card payment
                    $response->original['text'] = $cc->viewCreditCardForm($uData[0]['transaction_id'], $total);
                }
                else if ($data['payment_method'] == 2) {
                    //return redirect URL for PayPal
                    $pp = new PayPalClient();
                    $response->original['status'] = 2;
                    $response->original['text'] = $pp->createOrder($ppD);
                }
            }
            else {
                $response->original['status'] = 3;
                $response->original['text'] = Lang::get('cart.successfully_added');
            }
        }
        return response()->json($response->original);

    }



    private function saleStatusUpdate($sale_id, $status, $type = 'single')
    {
        if ($type == 'single') {
            Sales::where('id', $sale_id)->update(['status' => $status]);
            if ($status == 1) {
                RemainingSeats::whereHas('sales', function ($q) use ($sale_id) {
                    $q->whereId($sale_id);
                })->decrement('remaining_seats');
            }
            else if ($status == 4) {
                RemainingSeats::whereHas('sales', function ($q) use ($sale_id) {
                    $q->whereId($sale_id);
                })->increment('remaining_seats');
            }
        }
        else if ($type == 'transaction') {
            Sales::transaction($sale_id)->update(['status' => $status]);
            if ($status == 1) {
                RemainingSeats::whereHas('sales', function ($q) use ($sale_id) {
                    $q->transaction($sale_id);
                })->decrement('remaining_seats');
            }
            else if ($status == 4) {
                RemainingSeats::whereHas('sales', function ($q) use ($sale_id) {
                    $q->transaction($sale_id);
                })->increment('remaining_seats');
            }

        }
    }

    public function checkRefundAmount(Request $request)
    {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $sale = Sales::with('routes', 'balanceUpdates')->whereHas('routes', function ($q) {
            $q->where('status', '!=', 2)->whereRaw('timestamp(arrival_date, arrival_time) > now()');
        })->whereId($request->id)->whereUserId($request->user()->id ?? Auth::user()->id)->status(1);
        if ($sale->exists()) {
            $first = $sale->first()->toArray();
            $searchForDriver = array_search(1, array_column($first['balance_updates'], 'type'));
            $amountDriver = $first['balance_updates'][$searchForDriver]['amount'];
            if (Carbon::now()->diffInHours(Carbon::parse($first['routes']['departure_date'] . ' ' . $first['routes']['departure_time'])) <= 24) {
                $amountForDriver = $amountDriver * 0.14;
                $amountUs = $amountDriver * 0.06;
                $amountToReturnToPassenger = $amountDriver - $amountForDriver - $amountUs;
            }
            else {
                $amountToReturnToPassenger = $amountDriver;
            }
            return response()->json(['status' => 1, 'text' => Lang::get('validation.refund_modal', ['amount' => $amountToReturnToPassenger])], 200);
        }
        else {
            return response()->json(['status' => 0, 'text' => Lang::get('validation.refund_not_allowed')], 403);
        }
    }


    public function refund(Request $request)
    {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $sale = Sales::with('routes', 'balanceUpdates')->whereHas('routes', function ($q) {
            $q->where('status', '!=', 2)->whereRaw('timestamp(arrival_date, arrival_time) > now()');
        })->whereId($request->id)->whereUserId($request->user()->id ?? Auth::user()->id)->status(1);
        if ($sale->exists()) {
            $first = $sale->first()->toArray();
            $searchForDriver = array_search(1, array_column($first['balance_updates'], 'type'));
            $searchForUs = array_search(4, array_column($first['balance_updates'], 'type'));
            $amountDriver = $first['balance_updates'][$searchForDriver]['amount'];
            $amountOur = $first['balance_updates'][$searchForUs]['amount'];
            if (Carbon::now()->diffInHours(Carbon::parse($first['routes']['departure_date'] . ' ' . $first['routes']['departure_time'])) <= 24) {
                $amountForDriver = $amountDriver * 0.14;
                $amountUs = $amountDriver * 0.06;
                $amountToReturnToPassenger = $amountDriver - $amountForDriver - $amountUs;
                BalanceUpdates::whereSaleId($request->id)->whereType(1)->update(['amount' => $amountForDriver]);
                BalanceUpdates::whereSaleId($request->id)->whereType(4)->update(['amount' => $amountOur + $amountUs]);
            }
            else {
                BalanceUpdates::whereSaleId($request->id)->whereType(1)->update(['amount' => 0]);
                $amountToReturnToPassenger = $amountDriver;
            }
            if ($first['payment_method'] == 2) {
                (new PayPalClient())->refundOrder($first['paypal_capture_id'], $amountToReturnToPassenger);
            }
            else if ($first['payment_method'] == 1) {
                //TODO: credit card or paypal refund action goes here with $amountToReturnToPassenger as the amount to return
            }
            (new QRScannerController())->balanceUpdate($request->id);
            Sales::whereId($first['id'])->update(['status' => 4]);
            RemainingSeats::whereRouteId($first['routes'])->increment('remaining_seats');
            return response()->json(['status' => 1, 'text' => Lang::get('validation.refund_successful')], 200);
        }
        else {
            return response()->json(['status' => 0, 'text' => Lang::get('validation.refund_not_allowed')], 403);
        }
    }

    private function balanceUpdate($user_id, $sale_id, $amount, $affiliateClientUserId, $passengerAffiliateUserId)
    {
        $tier1_percent = config('app.tier1_affiliate') / 100;
        $tier2_percent = config('app.tier2_affiliate') / 100;
        $passenger_percent = config('app.passenger_affiliate') / 100;
        $sale_commission = config('app.sale_commission') / 100;

        $theAmt = round($sale_commission * $amount, 3, PHP_ROUND_HALF_DOWN);

        $amt = [0];
        if (isset($affiliateClientUserId)) {
            $usr1 = User::with(['affiliateClient:code,user_id'])->where('id', $affiliateClientUserId)->first(['affiliate_code'])->toArray();
            //insert tier 1 affiliate balance updates table
            $append = round($theAmt * $tier1_percent, 3, PHP_ROUND_HALF_DOWN); // 5% of 10%
            BalanceUpdates::create(['user_id' => $affiliateClientUserId, 'amount' => $append, 'sale_id' => $sale_id, 'type' => 2]);
            $amt[] = $append;

            if (isset($usr1['affiliate_client']['user_id'])) {
                //insert tier 2 affiliate balance updates table
                $appends = round($theAmt * $tier2_percent, 3, PHP_ROUND_HALF_DOWN); //2% of 10%
                BalanceUpdates::create(['user_id' => $usr1['affiliate_client']['user_id'], 'amount' => $appends, 'sale_id' => $sale_id, 'type' => 3]);
                $amt[] = $appends;
            }
        }

        if($passengerAffiliateUserId) {
            //insert passenger affiliate balance update table
            $appendp = round($theAmt * $passenger_percent, 3, PHP_ROUND_HALF_DOWN); //3% of 10%
            BalanceUpdates::create(['user_id' => $passengerAffiliateUserId, 'amount' => $appendp, 'sale_id' => $sale_id, 'type' => 5]);
            $amt[] = $appendp;
        }

        $toDec = array_sum($amt);
        $zApp = $theAmt - $toDec; // Zoombus cut (10%)
        $dApp = $amount - $theAmt; // Driver cut (90%)

        //update driver balance
        BalanceUpdates::create(['user_id' => $user_id, 'amount' => $dApp, 'sale_id' => $sale_id, 'type' => 1]);

        //update zoombus balance
        BalanceUpdates::create(['user_id' => 1, 'amount' => $zApp, 'sale_id' => $sale_id, 'type' => 4]);

    }


    protected function orderApprove($transaction_id, $type = 'card')
    {
        if ($type == 'paypal') {
            $transactionField = 'paypal_transaction_id';
        }
        else {
            $transactionField = 'transaction_id';
        }

        $unique_ids = Sales::with([
            'users:id,name,phone_number,locale,affiliate_code',
            'routes:id,user_id,from,to,departure_date',
            'routes.user',
            'routes.user.affiliateClient',
            'users.affiliateClient',
            'routes.citiesFrom',
            'routes.citiesFrom.translated',
            'routes.citiesTo',
            'routes.citiesTo.translated'
        ])->where($transactionField, $transaction_id)->status(2)->get()->toArray();
        foreach ($unique_ids as $ui) {

            //Create QR Ticket
            $qrCode = new QrCode($ui['ticket_number'] . " | " . $ui['users']['name'] . " | " . $ui['routes']['cities_from']['translated']['name'] . " - " . $ui['routes']['cities_to']['translated']['name']);
            $qrCode->setSize(800);
            $qrCode->setMargin(0);
            Storage::disk('s3')->put('tickets/' . md5($ui['ticket_number']) . '.png', $qrCode->writeString());

            $locale = $ui['users']['locale'];
            //Send SMS and Email
            $departure_date = Carbon::parse($ui['routes']['departure_date']);
            $data['date'] = $departure_date->locale($locale)->translatedFormat('j\ F Y');
            $data['route_name'] = $ui['routes']['cities_from']['translated']['name'] . ' - ' . $ui['routes']['cities_to']['translated']['name'];
            $data['passenger'] = $ui['users']['name'];
            $data['seat_number'] = $ui['seat_number'];

//            User::where('id', $ui['user_id'])->first()->notify(
//                new TicketOrder($data,
//                    $locale,
//                    route('secure_ticket', ['id' => md5($ui['ticket_number'])])
//                )
//            );

            $this->balanceUpdate($ui['routes']['user_id'], $ui['id'], $ui['price'], $ui['routes']['user']['affiliate_client']['user_id'] ?? null, $ui['users']['affiliate_client']['user_id'] ?? null);

            //Remove items from cart
            Cart::where('sale_id', $ui['id'])->delete();

        }


        $this->checkOtherSalesToRemove($transactionField, $transaction_id);

    }


    private function checkOtherSalesToRemove($transactionField, $transaction_id)
    {
        $lng = config('laravellocalization.supportedLocales');
        $sales = Sales::where($transactionField, $transaction_id)->get()->toArray();
        foreach($sales as $sale) {
            $other_sales = Sales::with([
                'routes',
                'routes.citiesFrom',
                'routes.citiesFrom.translate',
                'routes.citiesTo',
                'routes.citiesTo.translate'
            ])->where('route_id', $sale['route_id'])->where('seat_number', $sale['seat_number'])
                ->where('id', '!=', $sale['id']);
            if ($other_sales->exists()) {
                $otherSalesData = $other_sales->get()->toArray();
                foreach ($otherSalesData as $os) {
                    $c = Cart::where('sale_id', $os['id'])->first('user_id');
                    if ($c) {
                        foreach ($lng as $k => $l) {
                            $translateFrom = $os['routes']['cities_from']['translate'];
                            $translateTo = $os['routes']['cities_from']['translate'];
                            $data[$k]['seat_number'] = $os['seat_number'];
                            $data[$k]['route_name'] = $translateFrom[array_search($k, array_column($translateFrom, 'locale'))]['name'] . ' - ' . $translateTo[array_search($k, array_column($translateTo, 'locale'))]['name'];
                        }
                        User::where('id', $c->user_id)->first()->notify(new RemovedFromCart($data));
                    }
                    Sales::where('id', $os['id'])->delete();
                }
            }
            $this->saleStatusUpdate($sale['id'], 1);
            Cart::where('sale_id', $sale['id'])->delete();
        }
    }


}
