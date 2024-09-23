<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\SupportTickets
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTickets newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTickets newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTickets query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $status
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $email
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SupportTicketMessages[] $message
 * @property-read int|null $message_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTickets whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTickets whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTickets whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTickets whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTickets whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTickets whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTickets whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTickets whereRead($value)
 * @property int $read
 * @property-read \App\SupportTicketMessages $latestAllMessage
 * @property-read \App\SupportTicketMessages $latestMessage
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SupportTicketMessages[] $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property int|null $admin
 * @property-read \App\User|null $adminUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SupportTicketMessages[] $messagesAsc
 * @property-read int|null $messages_asc_count
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTickets whereAdmin($value)
 */
class SupportTickets extends Model {
    protected $fillable = ['status','user_id','email','name','read'];

    use Notifiable;

    public function messages() {
        return $this->hasMany('App\SupportTicketMessages', 'ticket_id');
    }

    public function messagesAsc() {
        return $this->hasMany('App\SupportTicketMessages', 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function latestAllMessage() {
        return $this->hasOne('App\SupportTicketMessages', 'ticket_id')->orderBy('created_at', 'desc');
    }

    public function latestMessage() {
        return $this->hasOne('App\SupportTicketMessages', 'ticket_id')->where('admin', 0)->orderBy('created_at','desc');
    }


    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function adminUser() {
        return $this->belongsTo('App\User', 'admin');
    }

}
