<?php


namespace App\Http\Controllers\Api;


use App\BalanceUpdates;
use App\Http\Controllers\Controller;
use App\Sales;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class QRScannerController extends Controller {

    public function balanceUpdate($sale_id) {
        $query = BalanceUpdates::with('user')->where('sale_id', $sale_id)->where('status', 0);
        if($query->exists()) {
            $q = $query->get()->toArray();
            foreach($q as $v) {
                User::where('id', $v['user_id'])->increment('balance', $v['amount']);
            }
            BalanceUpdates::where('sale_id', $sale_id)->update(['status' => 1]);
        }
    }


    private function validateTicket($ticket_number, $method, $userId, $locale) {
        $sale_check = Sales::whereHas('routes', function ($q) use ($userId) {
            $q->where('user_id', $userId)->nowOrFuture();
        })->whereHas('routes.drivers', function ($q) {
            $q->where('status', 1);
        })->where('ticket_number', $ticket_number)->first('status');

        if (isset($sale_check) && $sale_check->status == 1) {
            Sales::status(1)->where('ticket_number', $ticket_number)->update(['status' => 3]);
            $sale_info = Sales::with([
                'users:id,name',
                'routes:id,user_id,from,to,currency_id,departure_date,departure_time,arrival_time',
                'routes.citiesFrom',
                'routes.citiesFrom.translated',
                'routes.citiesTo:id',
                'routes.citiesTo.translated',
                'routes.currency:id,key'
            ])->where('ticket_number', $ticket_number)->status(3)->first()->toArray();
            $this->balanceUpdate($sale_info['id']);
            $response = [
                'status' => 1,
                'ticket_number' => $sale_info['ticket_number'],
                'passenger' => $sale_info['users']['name'],
                'price' => $sale_info['price'],
                'price_currency' => $sale_info['routes']['currency']['key'],
                'from' => $sale_info['routes']['cities_from']['translated']['name'],
                'to' => $sale_info['routes']['cities_to']['translated']['name'],
                'seat_number' => $sale_info['seat_number'],
                'departure_date' => Carbon::parse($sale_info['routes']['departure_date'])->locale($locale)->translatedFormat('j\ M Y'),
                'departure_time' => $sale_info['routes']['departure_time'],
                'arrival_time' => $sale_info['routes']['arrival_time'],
            ];
            $code = 200;
        } else if (isset($sale_check) && $sale_check->status == 3) {
            $response = ['status' => 0, 'text' => ($method == "number") ?
                \Lang::get('validation.ticket_number_already_active') :
                \Lang::get('validation.qr_code_already_active')];
            $code = 422;
        } else {
            $response = ['status' => 0, 'text' => ($method == "number") ?
                \Lang::get('validation.ticket_number_invalid') :
                \Lang::get('validation.qr_code_invalid')];
            $code = 422;
        }

        return ['response' => $response, 'code' => $code];
    }

    public function parseQR(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        if ($request->QRData) {
            $ticket_number = explode(" |", $request->QRData)[0];
            $response = $this->validateTicket($ticket_number, 'QR', $request->user()->id, $request->lang);
        } else {
            $response['response'] = ['status' => 0, 'text' => \Lang::get('validation.qr_code_invalid')];
            $response['code'] = 422;
        }
        return response()->json($response['response'], $response['code']);
    }


    public function parseTicket(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        if ($request->ticket_number) {
            $response = $this->validateTicket($request->ticket_number, 'number', $request->user()->id, $request->lang);
        } else {
            $response['response'] = ['status' => 0, 'text' => \Lang::get('validation.ticket_number_invalid')];
            $response['code'] = 422;
        }
        return response()->json($response['response'], $response['code']);
    }
}
