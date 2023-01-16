<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Support\Facades\Auth;

class LoginAction extends Model implements Authenticatable
{
    use AuthenticableTrait;
    use HasFactory;
    protected $table = 'dsis_system_user';
    public $timestamps = false;

    public static function isUser()
    {
        return (Auth::user()->role_id == 2) ? false :  true;
    }
}
