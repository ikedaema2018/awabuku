<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_list extends Model
{
   public function category_Name(){
              return $this->belongsTo('App\Category','category_id','id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
      }
    public function book_Name(){
              return $this->belongsTo('App\Book','book_id','id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
      }

}
