<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
 
       public function books(){
              return $this->belongsToMany('App\Book', 'category_lists', 'category_id','book_id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }
    
          public function category_hasMany_lists(){
              return $this->hasMany('App\Category_list');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }
          
        //   public function category_genre(){
        //       return $this->belongsTo('App\Category_genre', 'category_genre', 'id');
        //     // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
        //   }
         public function category_Genre(){
              return $this->belongsTo('App\Category_genre', 'category_genre', 'id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }
    
}
