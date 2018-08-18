<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread_comment extends Model
{
    //
   
         public function r(){
              return $this->belongsTo('App\Comment', 'comment_id', 'id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
}
}