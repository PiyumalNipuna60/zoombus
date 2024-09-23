<?php

namespace App\Http\Controllers\Api;

use App\Financial;
use App\Http\Controllers\PayoutController as PC;
use App\Http\Controllers\ValidationController;
use App\Payouts;
use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class PayoutsController extends PC {

    public function get(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $payouts = Payouts::current($request->user()->id)
            ->with('currency', 'financial')
            ->where('type', $request->type)
            ->skip($request->skip)
            ->take(config('app.withdrawals_per_page'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        $hsh = new Hashids('', 15);
        $data['items'] = collect($payouts)->map(function ($v) use ($hsh) {
            $v['date'] = Carbon::parse($v['updated_at'] ?? $v['created_at'])->translatedFormat('j\ F Y');
            $v['transaction_id'] = $hsh->encode($v['id']);
            if ($v['financial']['type'] == 1) {
                $v['to'] =  $v['financial']['card_number'];
                $v['image'] = 'card';
            } else if ($v['financial']['type'] == 2) {
                $v['image'] = 'paypal';
                $v['to'] =  $v['financial']['paypal_email'];
            } else if ($v['financial']['type'] == 3) {
                $v['image'] = 'bank';
                $v['to'] =  $v['financial']['account_number'];
            }
            else {
                $v['to'] = 'none';
                $v['image'] = 'bank';
            }

            $v['currency'] = $v['currency']['key'];
            return $v;
        });

        $totalWithdrawn = Payouts::whereStatus(1)->current($request->user()->id)->sum('amount');
        $totalRequested = Payouts::whereStatus(2)->current($request->user()->id)->sum('amount');

        $data['balance'] = $request->user()->balance - Payouts::whereStatus(2)->current($request->user()->id)->sum('amount');
        $data['total_withdrawn'] = (substr($totalWithdrawn, -4) === '.000') ? rtrim($totalWithdrawn, '.00') : $totalWithdrawn;
        $data['total_requested'] = (substr($totalRequested, -4) === '.000') ? rtrim($totalRequested, '.00') : $totalRequested;
        if($request->skip == 0) {
            $data['total'] = Payouts::current($request->user()->id)->where('type', $request->type)->count();
        }
        return response()->json($data, 200);

    }

    public function add(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $data = $request->only(['amount', 'type']);
        $data['user_id'] = $request->user()->id;
        $response = ValidationController::response($this->validator($data), \Lang::get('validation.successfully_sent_to_review'));
        if ($response->original['status'] == 1) {
            if (!Financial::current($request->user()->id)->active()->exists()) {
                $response->original = [
                    'status' => 0,
                    'text' => \Lang::get('validation.custom.financial_id.required_mobile'),
                    'error' => [
                        'route' => 'financial',
                        'text' => \Lang::get('validation.custom.financial_id.required_mobile_anchor')
                    ]
                ];
                $code = 422;
            } else {
                $data['financial_id'] = Financial::current($request->user()->id)->active()->first('id')->id;
                $this->store($data);
                $code = 200;
            }
        } else {
            $code = 422;
        }
        return response()->json($response->original, $code);
    }
}
