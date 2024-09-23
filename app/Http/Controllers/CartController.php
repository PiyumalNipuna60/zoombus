<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Currency;
use App\Http\Controllers\Payments\PayPalClient;
use App\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Controllers\Payments\PayPalController;
use App\Http\Controllers\Payments\CreditCardController;

class CartController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->middleware('customer');
    }

    public static function store($data) {
        return Cart::create($data);
    }

    public static function update($data) {
        return Cart::where('id', $data['id'])->update(Arr::except($data, ['id']));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */

    public function remove(Request $request) {
        $data = $request->only('id');
        $id = $data['id'];
        if(\Auth::check() && Cart::where('id', $id)->where('user_id', \Auth::user()->id)->exists()) {
            $getSales = Cart::with(['sales:id'])->where('id', $id)->get()->toArray();
            foreach($getSales as $gs) {
                Sales::status(2)->where('id', $gs['sales']['id'])->delete();
            }
            Cart::where('id', $id)->delete();
            $response['status'] = 1;
        }
        else {
            $response['status'] = 0;
        }
        return response()->json($response);
    }


    public function view() {
        $data = Controller::essentialVars();
        return view('cart', $data);
    }

    public function checkout(Request $request) {
        $cicc = Controller::essentialVars(['cart_items','current_currency_key']);
        $cart_items = $cicc['cart_items'];

        $data = $request->only('payment_method');
        if($data['payment_method'] == 1) {
            //return form for Cartu Payments
            $cc = new CreditCardController();
            $response['status'] = 1;
            //TODO: credit card payment API
            $response['text'] = $cc->viewCreditCardForm($cart_items[0]['transaction_id'], array_sum(array_column(array_column($cart_items, 'sales'),'price')));
        }
        else if($data['payment_method'] == 2) {
            foreach($cart_items as $key => $val) {
                $ppD[$key]['ticket_number'] = $val['sales']['ticket_number'];
                $ppD[$key]['from'] = $val['sales']['routes']['cities_from']['translated']['name'];
                $ppD[$key]['routeNumber'] = $val['sales']['routes']['cities_from']['code'].$val['sales']['routes']['id'];
                $ppD[$key]['to'] = $val['sales']['routes']['cities_to']['translated']['name'];
                $ppD[$key]['price'] = $val['sales']['price'];
            }
            $pp = new PayPalClient();
            $response['status'] = 2;
            $response['text'] = $pp->createOrder($ppD);
        }

        return response()->json($response);
    }


}
