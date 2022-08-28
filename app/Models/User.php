<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [''];

    protected $enumerationType = [
        'subscriber',
        'staff',
        'dispatch',
        'adhoc',
        'support',
        'influencer',
        'ambassador'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function roles()
    {
        return $this->morphToMany(Role::class, 'roleable');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function attended()
    {
        return $this->hasMany(Order::class, 'handler_id');
    }

    public function attendedInternally()
    {
        return $this->hasMany(InternalOrder::class);
    }
}
