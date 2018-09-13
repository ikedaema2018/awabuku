@extends('layouts.app')

@section('content')

<?php

use App\Comment;
use App\User;
use App\Rental;
use App\Category;
use App\Category_genru;

?>

<h1>personテーブル作成</h1>
    <div class="row">
        <div class="col-xs-3"　style="background:#CCC;height:200px;">
            <img src="{{ $book["BookImage"]}}"></img>
        </div>
        <div class="col-xs-9"　style="background:#CCC;height:200px;">
            <h2>{{ $book->BookTitle }}</h2>
            <p>{{ $book->BookAuthor }}</p>
            <p>{{ $book->isbn10 }}/&nbsp;{{ $book->isbn13 }}</p>
            <p>{{ $book->PublishedDate}}</p>
             @if(isset($categories)>0)
             @foreach($categories as $category)
            <p>{{$category->category_name}}</p>
             @endforeach
             @endif
        </div>
    </div>  
    
    <div class="row">
        <p class="col-xs-12">書籍の内容</p>
        <p class="col-xs-12">{{ $book->BookDiscription}}</p>
    </div>
   
    <div class="row">
     @foreach($category_names as $category_name)
        <a href="{{url('category_genre_page/'.Category_genru::find($category_name["category_genru"])->id)}}"><p>ジャンル：{{Category_genru::find($category_name["category_genru"])->category_genruname}}</p></a>
     @endforeach    
     @foreach($category_names as $category_name)
        <a href="{{url('category_page/'.$category_name["id"])}}"><p>カテゴリ：{{$category_name["category_name"]}}</p></a>
     @endforeach   
     
     </div>
        
        
        
        
        
<p class="col-xs-12">おすすめコメント</p>
        
<table class="table table-striped">
    <tr>
        <th>ユーザー名</th>
        <th>こんな人</th>
        <th>評価</th>
        <th>オススメポイント</th>
    
    </tr>        
      
      
      
     @if(isset($comments)>0)
     @foreach($comments as $comment)
    <tbody>
        <tr>
            <td>
                <p>{{$comment->user_id}}</p>
            </td>
            <td>
                <p>{{$comment->person}}</p>
            </td>
            <td>
                <p>{{$comment->evolution}}</p>
            </td>
            <td>
                <p class="col-xs-12">{{$comment->comment_text}}({{User::find($comment->user_id)->name}})</p>
            </td>
            </tr>
        </tbody>
     @endforeach
     @endif
 </table>   
                 
                 



 @endsection
        
                    
                    




