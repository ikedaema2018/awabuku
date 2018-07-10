<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable; // UserInterfaceから変更
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract; // こんなのもいるって

class User extends Model implements AuthenticatableContract
{
    //
    use Authenticatable; 
    protected $fillable = ['name', 'facebook_id', 'avater'];
}
