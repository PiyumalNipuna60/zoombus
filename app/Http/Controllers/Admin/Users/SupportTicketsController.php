<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ValidationController;
use App\Notification;
use App\Notifications\SupportReply;
use App\SupportTicketMessages;
use App\SupportTickets;
use App\User;
use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SupportTicketsController extends AdminController
{

    private function store($data) {
        return SupportTicketMessages::create($data);
    }

    public function close(Request $request) {
        $d = $request->only('id');
        SupportTickets::where('id', $d['id'])->update(['status' => 0]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_support_suspend')]);
    }

    public function delete(Request $request) {
        $d = $request->only('id');
        SupportTickets::where('id', $d['id'])->delete();
        SupportTicketMessages::where('ticket_id', $d['id'])->delete();
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_support_delete')]);
    }

    private function validator($data) {
        $fields = [
            'ticket_id' => 'required|integer|'.Rule::exists('support_tickets', 'id'),
            'message' => 'required',
        ];
        return \Validator::make($data, $fields);
    }

    public function reply(Request $request) {
        $data = $request->only('message','ticket_id');
        $lng = config('laravellocalization.supportedLocales');
        $response = ValidationController::response($this->validator($data), Lang::get('alerts.reply_successfully_sent'));
        if($response->original['status'] == 1) {
            $hashids = new Hashids('', 16);
            $data['admin'] = \Auth::user()->id;
            $aId = $this->store($data);
            SupportTickets::whereId($data['ticket_id'])->whereRead(0)->update(['read' => 1]);
            SupportTickets::whereId($data['ticket_id'])->whereStatus(0)->update(['status' => 1]);
            SupportTickets::whereId($data['ticket_id'])->update(['admin' => \Auth::user()->id]);
            $ticket = SupportTickets::whereId($data['ticket_id'])->with('user')->first();
            $cur = ($ticket && $ticket['user']) ? $ticket['user']['locale'] : 'en';

            if(SupportTickets::whereNotNull('user_id')) {
                $user_id = SupportTickets::whereId($data['ticket_id'])->first('user_id')->user_id;
                if(Notification::where('ticket_id', $data['ticket_id'])->exists()) {
                    $date = Carbon::now()->translatedFormat('Y-m-d H:i:s');
                    Notification::where('ticket_id', $data['ticket_id'])->update(['created_at' => $date, 'updated_at' => $date, 'read_at' => null]);
                }
                else {
                    foreach($lng as $k=>$l) {
                        $notData['text_'.$k] = Lang::get('notifications.support', ['ticket' => $data['ticket_id']], $k);
                    }
                    $notData['url'] = route('support_ticket_secure', ['id' => $hashids->encode($data['ticket_id']), 'latest_message' => $hashids->encode($aId->id)]);
                    $notData['user_id'] = \Auth::user()->id;
                    $notData['type'] = 'support';
                    $data = [
                        'id' => (string) Str::uuid(),
                        'notifiable_id' => $user_id,
                        'type' => 'App\Notifications\SupportReplyNotification',
                        'notifiable_type' => 'App\User',
                        'data' => $notData,
                        'ticket_id' => $data['ticket_id']
                    ];
                    Notification::create($data);
                }
                SupportTickets::whereId($data['ticket_id'])->update(['user_read' => false]);
            }
            foreach($lng as $k=>$l) {
                $mailData[$k]['ticket'] = $data['ticket_id'];
            }
            SupportTickets::whereId($data['ticket_id'])->first()->notify(
                new SupportReply($mailData, $cur, route('support_ticket_secure', ['id' => $hashids->encode($data['ticket_id']), 'latest_message' => $hashids->encode($aId->id)]))
            );
        }
        return response()->json($response->original);
    }

    public function viewData(Request $request) {
        $tickets = SupportTickets::with('latestAllMessage')->get()->toArray();
        foreach($tickets as $k=>$t) {
            $actions = [
                [
                    'url' => route('admin_users_support_ticket_edit', ['id' => $t['id']]),
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                    'faicon' => 'fa-pencil',
                ],
                [
                    'faicon' => 'fa-times',
                    'url' => route('admin_users_support_ticket_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_support_delete'),
                        'error-msg' => Lang::get('alerts.error_support_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $t['id'],
                    ],
                ],
            ];
            $tickets[$k]['latest_message']['message'] = (mb_strlen($t['latest_all_message']['message']) > 255) ? mb_substr($t['latest_all_message']['message'], 0, 252).'...' : $t['latest_all_message']['message'];
            $tickets[$k]['status'] = StatusController::fetch($t['status'], null, [], Lang::get('statuses/support-tickets'), null, 'admin');
            $tickets[$k]['unread'] = StatusController::fetch($t['read'], null, [], Lang::get('statuses/support-tickets-read'), null, 'admin');
            $tickets[$k]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($tickets)->rawColumns(['status','unread','actions'])->toJson();
    }

    public function view() {
        $data = AdminController::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.support_tickets');
        $data['columns'] = ['created_at', 'name', 'email', 'latest_message.message', 'unread', 'status', 'actions'];
        $data['ajaxUrl'] = route('admin_users_support_ticket_data');
        $data['columnDefs'] = [['className' => 'text-center', 'targets' => [0, 1, 2]]];
        $data['order'] = [[ 4, "desc" ]];
        return view('admin.pages.dataTables', $data);
    }

    public function viewEdit($id) {
        $data = AdminController::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.support_tickets');
        if(SupportTickets::whereId($id)->exists()) {
            $data['ticket'] = SupportTickets::with('user','messages','messagesAsc.user')->whereId($id)->first()->toArray();
            $data['userAvatar'] = (new User())->photoSmallById($data['ticket']['user_id'] ?? 0);
            $data['actions'] = [
                [
                    'faicon' => 'fa-times',
                    'anchor' => Lang::get('admin.close'),
                    'url' => route('admin_users_support_ticket_close'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_support_suspend'),
                        'error-msg' => Lang::get('alerts.error_support_suspend'),
                    ],
                    'ajaxData' => [
                        'id' => $id,
                    ],
                ],
                [
                    'faicon' => 'fa-times',
                    'anchor' => Lang::get('admin.delete'),
                    'url' => route('admin_users_support_ticket_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_support_delete'),
                        'error-msg' => Lang::get('alerts.error_support_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $id,
                    ],
                ],
            ];
            if($data['ticket']['status'] == 0) {
                unset($data['actions'][0]);
            }
        }
        else {
            abort(404);
        }
        return view('admin.pages.users.support-ticket-edit', $data);
    }
}
