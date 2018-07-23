<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
  
      public function books(){
              return $this->belongsTo('App\Book', 'book_id', 'id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }

 
  
}
