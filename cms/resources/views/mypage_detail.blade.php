@extends('layouts.app')

@section('content')

<?php

use App\Comment;
use App\User;
use App\Category;

?>

<!--本の詳細-->
    <div class="row">
        <div class="col-sm-3"　style="background:#CCCCCC height:200px;" style="text-align:center;">
            <img src="{{ $book->BookImage}}"></img>
        </div>
        <div class="col-sm-6"　style="background:C;height:200px;">
            <h2>{{ $book->BookTitle }}</h2>
            <p>{{ $book->BookAuthor }}</p>
            <p>{{ $book->isbn10 }}/&nbsp;{{ $book->isbn13 }}</p>
            <p>{{ $book->PublishedDate}}</p>
        </div>
        <div class="col-sm-3">  
            <label for="deleate">登録したデータを削除する。</label> 
                <div>
                  <form action="{{url('delete_ownbook')}}" method="post">
                         {{ csrf_field() }}
                      <input type="radio" name="life_flag" value="1"<?php if($owner->life_flag==1):echo 'checked="checked"';endif;?>>はい
                    　<input type="radio" name="life_flag" value="0"<?php if($owner->life_flag==0):echo 'checked="checked"';endif;?>>いいえ<br>
                    　<input type="hidden" name="id" value={{"$owner->id"}}>
                      
                      <button type="submit" class="btn btn-info">削除する</button>
                  </form>
                </div>
        </div>        
    
    <div class="row">
        <p class="col-xs-12">書籍の内容</p>
        <p class="col-xs-12">{{ $book->BookDiscription}}</p>
    </div>
     
<!--本の詳細終わり-->   


<!--おすすめコメント一覧開始-->
  
    <div class="col-sm-12">
    <p>おすすめコメント</p>
        @if(isset($comments)>0)
        <table class="table table-striped">
         <tr>
            <th>ユーザー</th> 
            <th>オススメな人</th>
            <th>オススメ度</th>
            <th>コメント</th>
         </tr>
         <tbody>
             @foreach($comments as $comment)
             <tr>    
                <td>{{User::find($comment->user_id)->name}}さん</td>
                <td>{{$comment->person}}</td>
                <td>
                    @if(($comment->evolution) == 1)
                    <div class="star-rating-icon">
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                    </div>
                    @endif @if(($comment->evolution) == 2)
                    <div class="star-rating-icon">
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                    </div>
                    @endif @if(($comment->evolution) == 3)
                    <div class="star-rating-icon">
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                    </div>
                    @endif 
                    @if(($comment->evolution) == 4)
                    <div class="star-rating-icon">
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                    </div>
                    @endif
                    @if(($comment->evolution) == 5)
                    <div class="star-rating-icon">
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                    </div>
                    @endif
                </td>
                <td>{{$comment->comment_text}}</td>
             </tr>
              @endforeach
          </tbody> 
         </table> 
        @endif
    </div>
    </div>
<!--おすすめコメント一覧終わり-->               
 @endsection
        
                    
                    




