<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\SupportController as SC;
use App\SupportTickets;
use App\User;
use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class SupportController extends SC {

    public function get(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $id = (new Hashids('', 16))->decode($request->id)[0];
        $data = SupportTickets::with('messagesAsc','adminUser')->whereId($id)->whereUserId($request->user()->id)->first()->toArray();
        $data['avatar'] = (new User())->photoById($request->user()->id);
        $data['admin_user']['avatar'] = (new User())->photoById($data['admin']);
        foreach ($data['messages_asc'] as $key => $ma) {
            $data['messages_asc'][$key]['date'] = Carbon::parse($ma['created_at'])->translatedFormat('j\ F Y - H:i:s');
        }
        $request->user()->unreadNotifications()->where('notifiable_id', $request->user()->id ?? \Auth::user()->id)->update(['read_at' => now()]);
        SupportTickets::whereId($id)->whereUserId($request->user()->id)->update(['user_read' => true]);
        return response()->json($data, 200);
    }

    public function reply(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $response = $this->actionReply($request);
        if(is_numeric($response)) {
            $returnResponse = $this->get($request)->original;
            $code = 200;
        }
        else {
            $returnResponse = $response->original;
            $code = 422;
        }
        return response()->json($returnResponse, $code);
    }

    public function newOne(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        if($request->message) {
            $data = $request->only('message');
            $this->store($data, $request->user()->id);
            $returnResponse = [
                'text' => \Lang::get('validation.support_successfully_sent'),
                'avatar' => (new User())->photoById($request->user()->id),
                'date' => Carbon::now()->translatedFormat('j\ F Y - H:i:s')];
            $code = 200;
        }
        else {
            $returnResponse = \Lang::get('validation.please_fill_out_this_field');
            $code = 422;
        }

        return response()->json($returnResponse, $code);
    }

}
