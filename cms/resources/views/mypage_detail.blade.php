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
            <label for="modify"><a href="{{url('modify_ownbook/'.$owner->id)}}">登録したデータを修正する。</a></label> 
         
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
    </div>
    <div style="margin-left:40px;">
        <div class="row">
            <p >書籍の内容</p>
            <p class="col-xs-12">{{ $book->BookDiscription}}</p>
        </div>
     </div>
            
     
<!--本の詳細終わり-->   


<!--おすすめコメント一覧開始-->
  
    <div class="col-sm-12">
    <p>おすすめコメント</p>
        @if(isset($comments)>0)
        <table class="table table-striped">
         <tr>
            <th class="col-sm-2">ユーザー</th> 
            <th class="col-sm-3">特徴</th>
            <th class="col-sm-2">オススメ度</th>
            <th class="col-sm-5">コメント</th>
         </tr>
         <tbody>
             @foreach($comments as $comment)
             <tr>    
                <td>{{User::find($comment->user_id)->name}}さん</td>
                <td>{{$comment->keys->key}}</td>
                <td>
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
        
                    
                    




