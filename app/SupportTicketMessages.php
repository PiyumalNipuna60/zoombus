<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SupportTicketMessages
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTicketMessages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTicketMessages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTicketMessages query()
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTicketMessages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTicketMessages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTicketMessages whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTicketMessages whereUpdatedAt($value)
 * @property int $ticket_id
 * @property string $message
 * @property int $admin
 * @property-read \App\SupportTickets $ticket
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTicketMessages whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportTicketMessages whereMessage($value)
 */
class SupportTicketMessages extends Model
{
    protected $fillable = ['ticket_id','message','admin'];

    public function ticket() {
        return $this->belongsTo('App\SupportTickets', 'ticket_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'admin');
    }
}
