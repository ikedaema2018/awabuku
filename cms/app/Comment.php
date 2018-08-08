<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
         public function book_comments(){
              return $this->belongsTo('App\Owner', 'owner_id', 'id');
  
              
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }
}
