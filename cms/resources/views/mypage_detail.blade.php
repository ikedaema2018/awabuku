@extends('layouts.app')

@section('content')

<?php

use App\Comment;
use App\User;
use App\Category;

?>


    <div class="row">
        <div class="col-xs-3"　style="background:#CCC;height:200px;">
            <img src="{{ $book->BookImage}}"></img>
        </div>
        <div class="col-xs-9"　style="background:#CCC;height:200px;">
            <h2>{{ $book->BookTitle }}</h2>
            <p>{{ $book->BookAuthor }}</p>
            <p>{{ $book->isbn10 }}/&nbsp;{{ $book->isbn13 }}</p>
            <p>{{ $book->PublishedDate}}</p>
            
        </div>
    </div>  
    
    <div class="row">
        <p class="col-xs-12">書籍の内容</p>
        <p class="col-xs-12">{{ $book->BookDiscription}}</p>
    </div>
   
    <div class="row">
        
        <p class="col-xs-12">おすすめコメント</p>
       
             @if(isset($comments)>0)
             @foreach($comments as $comment)
             
                <p class="col-xs-1">{{$comment->evolution}}</p>
                <p class="col-xs-1">{{$comment->person}}</p>
                <p class="col-xs-10">{{$comment->comment_text}}</p>

             @endforeach
             @endif
    </div>
                 
                 


 @endsection
        
                    
                    




