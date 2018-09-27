<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
       public function tag_Category(){
              return $this->belongsTo('App\Category', 'category_id', 'id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }
       public function books(){
     return $this->belongsToMany('App\Book');
     // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名'); 
          
          
}
}