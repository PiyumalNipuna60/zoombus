<?php

namespace App\Http\Controllers;

use App\Financial;
use App\Payouts;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Hashids\Hashids;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Agent;

class PayoutController extends ValidationController {
    public function __construct() {
        parent::__construct();
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $this->middleware('customer');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function allPayoutData(Request $request) {
        $type = $request->only(['type'])['type'];
        if (in_array($type, ['driver', 'partner'])) {
            $payouts = Payouts::current()->with('currency', 'financial')->where('type', $type)->get()->toArray();
            $hsh = new Hashids('', 15);

            $data = collect($payouts)->map(function ($v) use ($hsh) {
                $v['date'] = Carbon::parse($v['updated_at'] ?? $v['created_at'])->translatedFormat('j\ F Y');
                $v['transaction_id'] = $hsh->encode($v['id']);

                if ($v['financial']['type'] == 1) {
                    $payoutTo = $v['financial']['card_number'];
                } else if ($v['financial']['type'] == 2) {
                    $payoutTo = $v['financial']['paypal_email'];
                } else if ($v['financial']['type'] == 3) {
                    $payoutTo = $v['financial']['account_number'];
                }

                $v['method'] = view('components.img', ['class' => 'h-12-px-m float-left', 'src' => '/images/financial-types/' . $v['financial']['type'] . '.png'])->render() . '&nbsp;' . $payoutTo;

                $v['currency'] = $v['currency']['key'];
                $v['the_status'] = StatusController::fetch($v['status'], null, [], [1 => ['text' => \Lang::get('misc.approved')], 2 => ['text' => \Lang::get('misc.requested')]]);
                return $v;
            });

            return datatables()->of($data ?? [])->rawColumns(['method', 'the_status'])->toJson();
        }
    }


    protected function validator(array $data) {
        $st = User::current()->first('balance')->balance;
        $psz = Payouts::current($data['user_id'] ?? null)->where('status', 2)->sum('amount') ?? 0;
        $allowableMax = ($st - $psz >= 1) ? $st - $psz : 0;
        $vals = [
            'amount' => 'required|numeric|between:10,' . $allowableMax,
            'type' => 'required|string|' . Rule::in(['driver', 'partner']),
        ];
        return Validator::make($data, $vals);
    }

    public function request(Request $request) {
        $data = $request->only(['amount', 'type']);
        $response = ValidationController::response($this->validator($data), \Lang::get('validation.successfully_sent_to_review'));
        if ($response->original['status'] == 1) {
            if (!Financial::current()->active()->exists()) {
                $response->original = ['status' => 0, 'text' => \Lang::get('validation.custom.financial_id.required', ['link' => route('financial_add')])];
            }
            else {
                $data['user_id'] = \Auth::user()->id;
                $data['financial_id'] = Financial::current()->active()->first('id')->id;
                $this->store($data);
            }
        }
        return response()->json($response->original);
    }

    protected function store($data) {
        return Payouts::create($data);
    }


}
