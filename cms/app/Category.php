<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
 
       public function category_genru_relation(){
              return $this->belongsTo('App\Category_genru', 'category_genru', 'id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }
    
          public function category_hasMany_lists(){
              return $this->hasMany('App\Category_list');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }
    
}
