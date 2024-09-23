<?php

namespace App\Http\Controllers\Api;

use App\Financial;
use App\Http\Controllers\FinancialController as FC;
use App\Payouts;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class FinancialController extends FC {
    public function __construct() {
        parent::__construct();
    }

    public function setPrimary(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        if (Financial::current($request->user()->id)->whereType($request->type)->whereStatusChild(1)->exists()) {
            Financial::current($request->user()->id)->update(['status' => 0]);
            Financial::current($request->user()->id)->whereType($request->type)->whereStatusChild(1)->update(['status' => 1]);
            return response()->json('', 200);
        } else {
            return response()->json(['text' => \Lang::get('validation.error_updating_financial')], 422);
        }
    }

    public function getPrimary(Request $request) {
        if (Financial::current($request->user()->id)->whereStatus(1)->exists()) {
            $type = Financial::current($request->user()->id)->whereStatus(1)->first()->type;
            return response()->json($type, 200);
        } else {
            return response()->json('Not Found', 404);
        }
    }

    public function getByType(Request $request) {
        if (Financial::current($request->user()->id)->whereType($request->type)->exists()) {
            $getByType = Financial::current($request->user()->id)->whereType($request->type)->get()->toArray();
            return response()->json($getByType, 200);
        } else {
            return response()->json('Not Found', 404);
        }
    }

    public function setByType(Request $request) {
        if (Financial::current($request->user()->id)->whereType($request->type)->whereId($request->id)->exists()) {
            Financial::current($request->user()->id)->whereType($request->type)->update(['status_child' => 0]);
            Financial::current($request->user()->id)->whereType($request->type)->whereId($request->id)->update(['status_child' => 1]);
            return response()->json(['text' => 'Success'], 200);
        } else {
            return response()->json('Unprocessable Entity', 422);
        }
    }


    public function addAction(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $response = $this->add($request);
        if ($response->original['status'] == 1) {
            $statusCode = 200;
            $response->original = $this->getByType($request)->original[0];
            if($request->id) {
                $response->original['successText'] = \Lang::get('validation.method_updated');
            }
        } else {
            $statusCode = 422;
        }
        return response()->json($response->original, $statusCode);
    }

    public function delete(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $data = $request->only('id', 'type');
        if (Financial::current($request->user()->id)->where('id', $data['id'])->exists() && !Payouts::whereFinancialId($data['id'])->exists()) {
            if(Financial::where('id', $data['id'])->where('status', 1)->exists()) {
                Financial::where('id', $data['id'])->delete();
                Financial::current($request->user()->id)->whereType($data['type'])->take(1)->update(['status' => 1]);
            }
            else {
                Financial::where('id', $data['id'])->delete();
            }
            Financial::current($request->user()->id)->whereType($data['type'])->take(1)->update(['status_child' => 1]);
            $response = ['status' => 1, 'text' => \Lang::get('validation.successfully_removed_financial')];
        } else {
            $response = ['status' => 0, 'text' => \Lang::get('validation.financial_method_cant_be_deleted')];
        }

        if ($response['status'] == 1) {
            $response = $this->getByType($request)->original;
            $statusCode = 200;
        } else {
            $statusCode = 422;
        }
        return response()->json($response, $statusCode);
    }

}
