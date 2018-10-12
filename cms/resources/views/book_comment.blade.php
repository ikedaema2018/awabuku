@extends('layouts.app')

@section('content')

<?php

use App\Comment;
use App\User;
use App\Rental;
use App\Category;

?>
     <ul style="list-style:none;" class="sample">
         <li><span><img class="avater img-circle" src="{{$comment->user->avater}}"></img></span></li>
         <li><h3><b><a href="{{url('user_search_page/'.$comment->user->id)}}">{{$comment->user->name}}</a>さんのオススメ</b></h3></li>
     </ul>

    <div class="row">
        <div class="col-sm-3"　style="background:#CCC;height:200px;"style="text-align:center;">
            <img src="{{ $comment->user_c->BookImage}}"></img>
        </div>
        <div class="col-sm-9"　style="background:#CCC;height:200px;">
            
       
            <h2>{{ $comment->user_c->BookTitle }}</h2>
            
            <p>{{ $comment->user_c->BookAuthor }}</p>
            <p>{{ $comment->user_c->isbn10 }}/&nbsp;{{ $comment->user_c->isbn13 }}</p>
            <p>{{ $comment->user_c->PublishedDate}}</p>
            
        </div>
    </div>  
    <div style="margin-left:40px;">
    <div class="row">
        <p>書籍の内容</p>
        <p class="col-xs-12">{{ $comment->user_c->BookDiscription}}</p>
    </div>
  
    <div class="row">
        <p>タグ</p>
         @foreach($comment->user_c->tags as $tag)
        <p class="tag">{{$tag->tags}}</p>
        @endforeach
    
    </div>  
    <div class="row">
      <p>おすすめしたい人</p>    
      <p class="col-xs-12">{{$comment->keys->key}}</p>
    </div>
    
    <div class="row">
       <p>評価</p>     
        @if(($comment->evaluation) == 1)
        <div class="star-rating-icon">
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star-empty"></span>
          <span class="glyphicon glyphicon-star-empty"></span>
          <span class="glyphicon glyphicon-star-empty"></span>
          <span class="glyphicon glyphicon-star-empty"></span>
        </div>
        @endif @if(($comment->evaluation) == 2)
        <div class="star-rating-icon">
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star-empty"></span>
          <span class="glyphicon glyphicon-star-empty"></span>
          <span class="glyphicon glyphicon-star-empty"></span>
        </div>
        @endif @if(($comment->evaluation) == 3)
        <div class="star-rating-icon">
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star-empty"></span>
        </div>
        @endif 
        @if(($comment->evaluation) == 4)
        <div class="star-rating-icon">
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star"></span>
        </div>
        @endif
        @if(($comment->evaluation) == 5)
        <div class="star-rating-icon">
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star"></span>
          <span class="glyphicon glyphicon-star-empty"></span>
          <span class="glyphicon glyphicon-star-empty"></span>
        </div>
        @endif
    </div>
     <div class="row">
        <p style="border-bottom-style: outset;">おすすめコメント</p>
        <div class="col-xs-offset-1 col-xs-9 col-xs-offset-2" style="background-color:#fffaf0;">
        <p class="col-xs-12">{{$comment->comment_text}}<a href="{{url('user_search_page/'.$comment->user_id)}}"></a></p>
        </div>
    </div>
    </div>
                 
                 


 @endsection
        
                    
                    




