<?php

namespace App\Http\Controllers\Payments;

use App\Cart;
use App\Http\Controllers\BookingController;
use App\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CreditCardController extends BookingController
{

    public function viewCreditCardForm($transaction_id, $total) {
        $data = $this->essentialVars(['current_currency_code','current_locale_id']);
        $data['total'] = $total;
        $data['transaction_id'] = $transaction_id;
        return view('components.checkout.cardForm', $data)->render();
    }

    public function confirm(Request $request) {
        $req = $request->only(['ConfirmRequest','signature']);

        $fp = fopen(public_path()."CartuBankKEY.pem", "r");
        $cert = fread($fp, 8192);
        fclose($fp);
        if(!openssl_verify(
            'ConfirmRequest='.$req['ConfirmRequest'],
            base64_decode($req['signature']),
            openssl_get_publickey($cert))) {
            die("signature error");
        }

        header('Content-type: text/xml');

        $xml = xml_parser_create('UTF-8');
        xml_parse_into_struct($xml, $req['ConfirmRequest'], $vals);
        xml_parser_free($xml);

        foreach ($vals as $data) {
            if ($data['tag']=='TRANSACTIONID')
                $response['transaction_id'] = $data['value'];
            if ($data['tag']=='PAYMENTID')
                $response['payment_id'] = $data['value'];
            if ($data['tag'] == 'STATUS')
                $response['status'] = $data['value'];
            if ($data['tag']=='AMOUNT')
                $response['amount'] = $data['value'];
            if ($data['tag']=='PAYMENTDATE')
                $response['payment_date'] = $data['value'];
        }

        $resp = Arr::only($response, ['transaction_id','payment_id']);
        if($response['status'] == 'C' && !$this->checkOrder(Arr::only($response, ['transaction_id','amount']))) {
            $resp['status'] = 'DECLINED';
        }
        else if($response['status'] == 'Y' && !$this->successOrder($response['transaction_id'])){
            $resp['status'] = 'DECLINED';
        }
        else {
            $resp['status'] = 'ACCEPTED';
        }

        return view('components.checkout.XMLResponse', $resp);
    }

    public function confirmTest($transaction_id, $amount) {
        $data['transaction_id'] = $transaction_id;
        $data['amount'] = $amount;

        if(!$this->checkOrder($data)) {
            return 'check error';
        }
        else if (!$this->successOrder($data['transaction_id'])) {
            return 'success error';
        }
        else {
            return 'succ';
        }
    }

    private function successOrder($transaction_id) {
        BookingController::orderApprove($transaction_id);
        return true;
    }


    private function checkOrder($data) {
        if(strlen($data['transaction_id']) == 11) {
            if (Cart::where('transaction_id', $data['transaction_id'])->exists()) {
                if(Sales::whereHas('cart', function ($q) use ($data) {
                    $q->where('transaction_id', $data['transaction_id']);
                })->status(2)->sum('price') != $data['amount']) {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        else {
            if(Sales::status(2)->transaction($data['transaction_id'])->exists()) {
                if(Sales::status(2)->transaction($data['transaction_id'])->sum('price') != $data['amount']){
                    return false;
                }
            }
            else {
                return false;
            }
        }

        return true;
    }



}
