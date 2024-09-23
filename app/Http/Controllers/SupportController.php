<?php

namespace App\Http\Controllers;

use App\Rules\HashidsRule;
use App\Rules\LastMessageHashids;
use App\SupportTicketMessages;
use App\SupportTickets;
use App\User;
use Hashids\Hashids;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class SupportController extends ValidationController
{

    public function __construct()
    {
        parent::__construct();
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $this->middleware('customer')->only('view', 'actionReply');
            $this->middleware('can_view_support_ticket')->only('view', 'actionReply', 'close');
            $this->middleware('support_ticket_exists')->only('viewSecure', 'actionReplySecure', 'closeSecure');
        }
    }

    protected function store($data, $user = null)
    {
        $data['user_id'] = $user ?? (\Auth::check()) ? \Auth::user()->id : null;
        $stc = SupportTickets::create(Arr::except($data, ['message']));
        SupportTicketMessages::create(['ticket_id' => $stc->id, 'message' => $data['message']]);
        return $stc;
    }

    private function storeMessage($data)
    {
        return SupportTicketMessages::create($data);
    }

    private function validator($data, $type = null)
    {
        $fields = [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'g-recaptcha-response' => 'required',
        ];
        if ($type == 'reply') {
            unset($fields['name']);
            unset($fields['email']);
            unset($fields['g-recaptcha-response']);
            $fields['id'] = ['required', new HashidsRule()];
            $fields['latest_message'] = ['sometimes', 'required', new LastMessageHashids()];
        }
        return Validator::make($data, $fields);
    }

    public function close(Request $request)
    {
        $data = $request->only('id');
        SupportTickets::whereId($data['id'])->delete();
        SupportTicketMessages::whereTicketId($data['id'])->delete();
        return response()->json(['status' => 1, 'text' => Lang::get('validation.successfully_closed_ticket')]);
    }

    public function closeSecure(Request $request)
    {
        $data = $request->only('id', 'latest_message');
        $data['id'] = (new Hashids('', 16))->decode($data['id'])[0];
        SupportTickets::whereId($data['id'])->delete();
        SupportTicketMessages::whereTicketId($data['id'])->delete();
        return response()->json(['status' => 1, 'text' => Lang::get('validation.successfully_closed_ticket')]);
    }

    public function action(Request $request)
    {
        $data = $request->only('name', 'email', 'message', 'g-recaptcha-response');
        $response = ValidationController::response($this->validator($data), Lang::get('validation.support_successfully_sent'));
        if ($response->original['status'] == 1) {
            if (!$this->validateRecaptcha($data['g-recaptcha-response'])) {
                $response->original = ['status' => 0, 'text' => \Lang::get('validation.recaptcha_verification_failed')];
            } else {
                unset($data['g-recaptcha-response']);
                $this->store($data);
            }
        }
        return response()->json($response->original);
    }

    public function actionReply(Request $request)
    {
        $data = $request->only('id', 'message');
        $response = ValidationController::response($this->validator($data, 'reply'), Lang::get('validation.support_successfully_sent'));
        if ($response->original['status'] == 1) {
            $data['ticket_id'] = (new Hashids('', 16))->decode($data['id'])[0];
            $store = $this->storeMessage($data);
            SupportTickets::whereId($data['ticket_id'])->whereRead(1)->update(['read' => 0]);
            SupportTickets::whereId($data['ticket_id'])->whereStatus(0)->update(['status' => 1]);
            if ($request->mobile) {
                return $store->id;
            }
        }

        return response()->json($response->original);

    }

    public function actionReplySecure(Request $request)
    {
        $data = $request->only('id', 'latest_message', 'message');
        $response = ValidationController::response($this->validator($data, 'reply'), Lang::get('validation.support_successfully_sent'));
        if ($response->original['status'] == 1) {
            $data['ticket_id'] = (new Hashids('', 16))->decode($data['id'])[0];
            $this->storeMessage($data);
            SupportTickets::whereId($data['ticket_id'])->whereRead(1)->update(['read' => 0]);
            SupportTickets::whereId($data['ticket_id'])->whereStatus(0)->update(['status' => 1]);
        }
        return response()->json($response->original);
    }

    public function view($id)
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        } else {
            $data = Controller::essentialVars();
            $data['robots'] = 'noindex, nofollow';
            $data['ticket'] = SupportTickets::with('messages', 'messages.user')->whereId($id)->first()->toArray();
            $data['title_page'] = Lang::get('titles.support_single', ['ticket' => $data['ticket']['id']]);
            $data['userAvatar'] = (new User())->photoById($data['ticket']['user_id'] ?? 0);
            return view('single-support-ticket', $data);
        }
    }

    public function viewSecure($ids)
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        } else {
            $data = Controller::essentialVars();
            $id = (new Hashids('', 16))->decode($ids)[0];
            $data['robots'] = 'noindex, nofollow';
            $data['ticket'] = SupportTickets::with('messages', 'messages.user')->whereId($id)->first()->toArray();
            $data['userAvatar'] = (new User())->photoById($data['ticket']['user_id'] ?? 0);
            $data['title_page'] = Lang::get('titles.support_single', ['ticket' => $data['ticket']['id']]);
            return view('single-support-ticket', $data);
        }
    }

    public function viewAll()
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        } else {
            $data = Controller::essentialVars();
            return view('support-ticket', $data);
        }

    }

}
