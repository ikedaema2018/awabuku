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
         }     
         public function user(){
              return $this->belongsTo('App\User', 'user_id', 'id');       
         }
         public function keys(){
              return $this->belongsTo('App\Key', 'key', 'id');       
  
              
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }
}


