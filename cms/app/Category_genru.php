<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_genru extends Model
{
    //
         public function categories(){
              return $this->hasMany('App\Category', 'category_genru', 'id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }
    
    
    
}
