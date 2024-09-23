<?php


namespace App\Http\Controllers\Payments;

use App\Currency;
use App\Http\Controllers\BookingController;
use App\Sales;
use Illuminate\Http\Request;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Payments\CapturesRefundRequest;


class PayPalClient extends BookingController
{
    public static function environment()
    {
        $clientId = config('services.paypal.sandbox_client');
        $clientSecret = config('services.paypal.sandbox_secret');
        return new SandboxEnvironment($clientId, $clientSecret);
    }

    private static function orderBody($data)
    {
        $parsedData = [];
        $totals = [];
        foreach ($data as $key => $val) {
            $currency = Currency::whereId(1)->first('value')->value;
            $currency = $currency+0.05;
            $name = $val['from'] . ' - ' . $val['to'] . ' - #' . $val['routeNumber'];
            $totals[$key] = round($val['price']/$currency, 2);
            $parsedData[$key] = [
                'name' => $name,
                'sku' => $val['ticket_number'],
                'unit_amount' => [
                    'currency_code' => 'USD',
                    'value' => $totals[$key],
                ],
                'quantity' => '1',
            ];
        }
        $body = [
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('paypal_execute'),
                'cancel_url' => route('listings')
            ],
            'purchase_units' => [
                [
                    'description' => 'Zoombus ticket',
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => array_sum($totals),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'USD',
                                'value' => array_sum($totals),
                            ],
                        ]
                    ],
                    'items' => $parsedData
                ]
            ]
        ];

        return $body;
    }

    public function createOrder($data)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = self::orderBody($data);
        $client = new PayPalHttpClient(self::environment());
        $response = $client->execute($request);
        foreach ($data as $val) {
            Sales::where('ticket_number', $val['ticket_number'])->update(['paypal_transaction_id' => $response->result->id, 'payment_method' => 2]);
        }
        return $response->result->links[1]->href;
    }

    public function captureOrder(Request $request)
    {
        $token = $request->token;
        $request = new OrdersCaptureRequest($token);
        $client = new PayPalHttpClient(self::environment());
        $response = $client->execute($request);
        foreach ($response->result->purchase_units as $purchase_unit) {
            foreach ($purchase_unit->payments->captures as $capture) {
                Sales::where('paypal_transaction_id', $token)->update(['paypal_capture_id' => $capture->id]);
            }
        }
        $this->orderApprove($token, 'paypal');
        return redirect()->route('bought_tickets');
    }


    public static function refundBody($amount)
    {
        return [
            'amount' => [
                'value' => $amount,
                'currency_code' => 'USD'
            ]
        ];
    }

    public static function refundOrder($captureId, $amount)
    {
        $currency = Currency::whereId(1)->first('value')->value;
        $currency = $currency+0.05;
        $amountInUSD = round($amount/$currency, 2);
        $request = new CapturesRefundRequest($captureId);
        $request->body = self::refundBody($amountInUSD);
        $client = new PayPalHttpClient(self::environment());
        return $client->execute($request);
    }
}
