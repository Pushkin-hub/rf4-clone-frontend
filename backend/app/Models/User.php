<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'group_id',
        'is_admin'
    ];

    protected $hidden = [
        'password_hash'
    ];
    protected $casts = [
        'is_admin' => 'boolean'
    ];
}
