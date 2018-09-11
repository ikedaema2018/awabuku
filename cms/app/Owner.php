<?php

namespace App;


use App\Scopes\LivingBookScope;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
  
      public function books(){
              return $this->belongsTo('App\Book', 'book_id', 'id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
          }

      public function rentals(){
              return $this->hasMany('App\Rental','owner_id','id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
      }
      public function owner_comments(){
              return $this->hasMany('App\Comment','owner_id','id');
            // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');


      }
      
      protected static function boot(){
        parent::boot();

        static::addGlobalScope(new LivingBookScope);
    }
      
      
      
      // public function users(){
      //         return $this->belongsTo('App\User', 'user_id', 'id');
      //       // return $this->belongsTo('App\User', '外部キーのカラム名', '親元のid扱いのカラム名');
      //     }
          
      // public function posts()
      // {
      //   return $this->hasManyThrough(
      //       'App\Rental',
      //       'App\User',
      //       'id', // usersテーブルの外部キー
      //       'id', // postsテーブルの外部キー
      //       'user_id', // countriesテーブルのローカルキー
      //       'owner_id' // usersテーブルのローカルキー
      //   );
}    
          
          
          
 