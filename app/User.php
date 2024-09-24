<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User current($user = null)
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string $phone_number
 * @property string $password
 * @property int $status
 * @property int $role
 * @property string|null $id_number
 * @property string|null $affiliate_code
 * @property string|null $city
 * @property string|null $birth_date
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAffiliateCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User active()
 * @property-read \App\AffiliateCodes $affiliate
 * @property-read \App\AffiliateCodes|null $affiliateClient
 * @property-read \App\Gender|null $gender
 * @property-read \App\Country|null $country
 * @property float $balance
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBalance($value)
 * @property-read \App\Driver $driver
 * @property-read \App\Partner $partner
 * @property int $subscribed
 * @property-read \App\Admins $admin
 * @property-read int|null $affiliate_count
 * @property-read int|null $notifications_count
 * @property-read \App\Payouts $payouts
 * @property-read \App\Routes $routes
 * @property-read \App\Sales $sales
 * @property-read \App\Vehicle $vehicles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSubscribed($value)
 * @property-read int|null $payouts_count
 * @property-read int|null $routes_count
 * @property-read int|null $sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sales[] $salesRoute
 * @property-read int|null $sales_route_count
 * @property-read int|null $vehicles_count
 * @property int $country_id
 * @property int $gender_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Vehicle[] $vehiclesActive
 * @property-read int|null $vehicles_active_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User suspended()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGenderId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 */
class User extends Authenticatable {
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name', 'email', 'password', 'phone_number', 'country_id', 'city', 'id_number', 'affiliate_code', 'status', 'gender_id', 'birth_date', 'subscribed', 'locale'
    ];

    public function scopeCurrent($query, $user = null) {
        if (\Auth::check()) {
            $query->where('id', \Auth::user()->id);
        }
        else if (isset($user)) {
            $query->where('id', $user);
        }
    }

    public function scopeActive($query) {
        $query->where('status', 1);
    }

    public function scopeSuspended($query) {
        $query->where('status', 3);
    }

    public function photoExists() {
        if (Storage::disk('s3')->exists('users/' . $this->id . '.'.$this->extension)) {
            return true;
        } else {
            return false;
        }
    }

    public function photoById($id) {
        $user = User::whereId($id)->first('extension');
        $extension = $user->extension ?? 'jpg';
        if (Storage::disk('s3')->exists('users/' . $id . '.'.$extension)) {
            return Storage::temporaryUrl('users/'.$id.'.'.$user->extension, now()->addMinutes(5));
        } else {
            return '/images/users/default.png';
        }
    }


    public function photoSmallById($id) {
        $user = User::whereId($id)->first('extension');
        $extension = $user->extension ?? 'jpg';
        if (Storage::disk('s3')->exists('users/small/' . $id . '.'.$extension)) {
            return Storage::temporaryUrl('users/small/' . $id . '.'.$extension, now()->addMinutes(5));
        } else {
            return '/images/users/small/default.png';
        }
    }

    public function countryImageById($id) {
        if (file_exists(public_path() . '/admin/img/flags/' . strtolower($id) . '.png')) {
            return '/admin/img/flags/' . strtolower($id) . '.png';
        } else {
            return '/admin/img/flags/_European Union.png';
        }
    }

    public function photo() {
        if (Storage::disk('s3')->exists('users/' . $this->id . '.' . $this->extension)) {
            return Storage::temporaryUrl('users/' . $this->id . '.'.$this->extension, now()->addMinutes(5));
        } else {
            return '/images/users/default.png';
        }
    }

    public function photoSmall() {
        if (Storage::disk('s3')->exists('users/small/' . $this->id . '.' . $this->extension)) {
            return Storage::temporaryUrl('users/small/' . $this->id . '.'.$this->extension, now()->addMinutes(5));
        } else {
            return '/images/users/small/default.png';
        }
    }

    public function driver() {
        return $this->hasOne('App\Driver', 'user_id');
    }

    public function vehicles() {
        return $this->hasMany('App\Vehicle', 'user_id');
    }

    public function vehiclesActive() {
        return $this->hasMany('App\Vehicle', 'user_id')->where('status', 1);
    }

    public function routes() {
        return $this->hasMany('App\Routes', 'user_id');
    }

    public function partner() {
        return $this->hasOne('App\Partner', 'user_id');
    }

    public function sales() {
        return $this->hasMany('App\Sales', 'user_id');
    }

    public function salesRoute() {
        return $this->hasManyThrough('App\Sales', 'App\Routes', 'user_id', 'route_id');
    }

    public function payouts() {
        return $this->hasMany('App\Payouts', 'user_id');
    }


    public function admin() {
        return $this->hasOne('App\Admins', 'user_id');
    }

    public function affiliate() {
        return $this->hasMany('App\AffiliateCodes', 'user_id');
    }


    public function gender() {
        return $this->belongsTo('App\Gender', 'gender_id');
    }

    public function balanceUpdates() {
        return $this->hasMany('App\BalanceUpdates', 'user_id');
    }

    public function country() {
        return $this->belongsTo('App\Country', 'country_id');
    }


    public function affiliateClient() {
        return $this->belongsTo('App\AffiliateCodes', 'affiliate_code', 'code');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public $nonFields = ['avatar','created_at','updated_at','the_type','balance', 'payouts', 'total_withdrawn', 'total_routes','extension','locale'];


    public function routeNotificationForNexmo($notification)
    {
        return $this->phone_number;
    }

    public function findForPassport($username)
    {
        return $this->where('phone_number', $username)->first();
    }

    public function validateForPassportPasswordGrant($password)
    {
        return ($password == $this->password || Hash::check($password, $this->password));
    }



}
