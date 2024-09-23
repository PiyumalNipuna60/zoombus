<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\StatusController;
use App\Http\Controllers\ValidationController;
use App\Notifications\SupportReply;
use App\Notifications\SupportReplyNotification;
use App\Payouts;
use App\SupportTickets;
use App\User;
use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class PayoutsController extends AdminController {

    private function store($data) {
        Payouts::updateOrCreate(['id' => $data['id'] ?? 0], Arr::except($data, 'id'));
    }

    private function balanceAction($payout_id, $status, $type) {
        $payoutData = Payouts::with('user')->where('id', $payout_id)->first();
        if ($payoutData) :
            $pData = $payoutData->toArray();
            $amount = $pData['amount'];
            $balance = $pData['user']['balance'];
            if ($status != $pData['status']) {
                if ($status == 2 && $pData['status'] == 1 ||
                    $status == 3 && $pData['status'] == 1 ||
                    $status == 1 && $pData['status'] == 2 ||
                    $status == 1 && $pData['status'] == 3
                ) {
                    if ($type == 'add') {
                        $balanceToUpdate = $balance + $amount;
                    } else if ($type == 'sub') {
                        $balanceToUpdate = $balance - $amount;
                    }
                    User::where('id', $pData['user_id'])->update(['balance' => $balanceToUpdate]);
                }
            }
        endif;
    }

    public function approve(Request $request) {
        $d = $request->only('id');
        $this->balanceAction($d['id'], 1, 'sub');
        Payouts::where('id', $d['id'])->update(['status' => 1]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_payout_approve')]);
    }


    public function decline(Request $request) {
        $d = $request->only('id');
        $this->balanceAction($d['id'], 3, 'add');
        Payouts::where('id', $d['id'])->update(['status' => 3]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_payout_decline')]);
    }

    public function delete(Request $request) {
        $d = $request->only('id');
        $this->balanceAction($d['id'], 2, 'add');
        Payouts::where('id', $d['id'])->delete();
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_payout_delete')]);
    }

    private function validator($data, $mode) {
        $fields = [
            'status' => 'required|integer|' . Rule::in(array_keys(Lang::get('statuses/payout'))),
            'comment' => 'nullable|string|max:255',
        ];
        if ($mode == 'edit') {
            $fields['id'] = 'required|integer|' . Rule::in(array_column(Payouts::all('id')->toArray(), 'id'));
        }
        return \Validator::make($data, $fields);
    }

    private function action($request, $mode) {
        $data = $request->only(['id', 'status', 'comment', 'reason']);
        $response = ValidationController::response($this->validator($data, $mode), Lang::get('alerts.successfully_added'));
        if ($response->original['status'] == 1) {
            $this->balanceAction($data['id'], $data['status'], ($data['status'] == 1) ? 'sub' : 'add');
            $this->store($data);
            if (isset($data['reason']) && !empty($data['reason'])) {
                $pWithUser = Payouts::with('user')->whereId($data['id'])->first()->toArray();
                $hashids = new Hashids('', 16);
                $rejRes = $this->rejectionReason($pWithUser['user']['email'], $pWithUser['user']['name'], $pWithUser['user']['id'], $data['reason']);
                $lng = config('laravellocalization.supportedLocales');
                $cur = $pWithUser['user']['locale'];
                foreach ($lng as $k => $l) {
                    $notData[$k]['ticket'] = $hashids->decode($rejRes['id'])[0];
                }
                User::whereId($pWithUser['user']['id'])->first()->notify(
                    new SupportReplyNotification($notData, \Auth::user()->id, route('support_ticket_secure', ['id' => $rejRes['id'], 'latest_message' => $rejRes['latest_message']]))
                );
                SupportTickets::whereId($hashids->decode($rejRes['id'])[0])->first()->notify(
                    new SupportReply($notData, $cur, route('support_ticket_secure', ['id' =>  $rejRes['id'], 'latest_message' => $rejRes['latest_message']]))
                );
            }
        }
        return response()->json($response->original);
    }

    public function edit(Request $request) {
        return $this->action($request, 'edit');
    }

    public function viewDataColumns() {
        return [
            'user.name', 'amount', 'currency.key', 'payout_to', 'comment', 'date', 'status', 'actions'
        ];
    }

    public function viewData(Request $request) {
        $payoutsQ = Payouts::with('user', 'currency', 'financial');
        if (!empty($request->user_id) && $request->user_id != 'all') {
            $payoutsQ->where('user_id', $request->user_id);
        }
        $payouts = $payoutsQ->get()->toArray();

        foreach ($payouts as $key => $val) {
            $actions = [
                [
                    'url' => route('admin_payout_edit', ['id' => $val['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                ],
                [
                    'url' => route('admin_payout_approve'),
                    'url_class' => 'btn btn-danger btn-condensed btn-sm btn-sm mb-control',
                    'anchor' => \Lang::get('misc.approve'),
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_payout_approve'),
                        'error-msg' => Lang::get('alerts.error_payout_approve'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ],
                [
                    'url' => route('admin_payout_decline'),
                    'url_class' => 'btn btn-danger btn-condensed btn-sm btn-sm mb-control',
                    'anchor' => \Lang::get('misc.decline'),
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_payout_decline'),
                        'error-msg' => Lang::get('alerts.error_payout_decline'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ],
                [
                    'faicon' => 'fa-times',
                    'url' => route('admin_payout_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_payout_delete'),
                        'error-msg' => Lang::get('alerts.error_payout_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id']
                    ],
                ]
            ];
            if ($val['status'] == 3) {
                unset($actions[2]);
            }
            if ($val['status'] == 1) {
                unset($actions[1]);
            }

            if ($val['financial']['type'] == 1) {
                $payoutTo = $val['financial']['card_number'];
            } else if ($val['financial']['type'] == 2) {
                $payoutTo = $val['financial']['paypal_email'];
            } else if ($val['financial']['type'] == 3) {
                $payoutTo = $val['financial']['account_number'];
            }

            $payouts[$key]['payout_to'] = view('components.img', ['class' => 'h-19-px float-left', 'src' => '/images/financial-types/' . $val['financial']['type'] . '.png'])->render() . '&nbsp;' . $payoutTo;
            $payouts[$key]['date'] = Carbon::parse($val['created_at'])->format('Y-m-d H:i');
            $payouts[$key]['status'] = view('components.status-admin', ['text' => Lang::get('statuses/payout.' . $val['status'] . '.text'), 'class' => StatusController::statusLabelClass($val['status'])])->render();
            $payouts[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($payouts)->rawColumns(['payout_to', 'status', 'actions'])->toJson();
    }

    public function view() {
        $data = AdminController::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.all_withdraws');
        $data['columns'] = $this->viewDataColumns();
        $data['columnDefs'] = [
            ['className' => 'lh-16', 'targets' => 3],
        ];

        $data['dateDefs'] = [5];
        $data['ajaxUrl'] = route('admin_payout_data');
        return view('admin.pages.dataTables', $data);
    }

    public function viewEdit($id) {
        $data = AdminController::essentialVars();
        $data['panel_class'] = 'payout-edit';
        $data['seo_title'] = Lang::get('admin_titles.payout_edit');
        $data['ajaxUrl'] = route('admin_payout_edit_action');
        if (Payouts::where('id', $id)->exists()) :
            $cData = Payouts::with('user', 'currency')->where('id', $id)->first()->toArray();
            $cData['id'] = ['value' => $cData['id'], 'readonly' => true];
            $cData['user'] = ['value' => $cData['user']['name'], 'readonly' => true];
            $cData['amount'] = ['value' => $cData['amount'], 'readonly' => true];
            $cData['status'] = ['value' => $cData['status'], 'field' => 'select', 'values' => Lang::get('statuses/payout'), 'select_key' => true];
            $data['fields'] = $this->fields($cData, (new Payouts())->fillable, (new Payouts())->nonFields);
        else:
            abort(404);
        endif;
        return view('admin.pages.add-edit', $data);
    }
}
