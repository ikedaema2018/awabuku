<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function owners(){
    return $this->hasMany('App\Owner','id','book_id');
    }
    
     public function books_hasMany_categorylists(){
     return $this->hasMany('App\Category_list');
     // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名'); 
     }
     public function tags(){
     return $this->belongsToMany('App\Tag');
     // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
     
 
    }
     
     
     
   
     
       
    
}