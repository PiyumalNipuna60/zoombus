<?php


namespace App\Http\Controllers\Payments\PayPal;


use App\Http\Controllers\Payments\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class CreateOrder
{
    public static function createOrder($body)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = $body;
        // 3. Call PayPal to set up a transaction
        $client = PayPalClient::client();
        $response = $client->execute($request);
        return $response;
    }
}
