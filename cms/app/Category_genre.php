<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_genre extends Model
{
             public function categories(){
              return $this->hasMany('App\Category', 'category_genre', 'id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }
    
    
    
    
    //
    
    
    
}
