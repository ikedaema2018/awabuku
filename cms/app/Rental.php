<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
          public function rental_books(){
              return $this->belongsTo('App\Owner', 'owner_id', 'id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }



}

