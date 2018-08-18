<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
         public function book_comments(){
              return $this->belongsTo('App\Owner', 'owner_id', 'id');
         }
         public function user_c(){
              return $this->belongsTo('App\Book', 'book_id', 'id');     
  
              
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }
}


