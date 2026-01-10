<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'lab',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 🔑 INI KUNCI UTAMA LOGIN USERNAME
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }
}
