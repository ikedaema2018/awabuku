<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function owners(){
    return $this->hasMany('App\Owner');
    }
}