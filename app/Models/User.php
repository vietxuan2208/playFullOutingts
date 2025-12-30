<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'fullname',
        'birthday',
        'gender',
        'email',
        'password',
        'address',
        'role_id',
        'status',
        'photo',
        'is_delete',
        'google_id',
        'password_set',
        'last_login',
        'phone',
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

    /**
     * Return true if user is admin (role = 2)
     */
    public function isAdmin()
    {
        return $this->role_id == 2;
    }

    /**
     * Relation to Role model (roles table)
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class, 'user_id');
    }
}
