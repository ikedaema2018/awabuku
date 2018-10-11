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
            <label for="deleate">本の貸出情報を変更する。</label> 
                
                  <form action="{{url('change_rental')}}" method="post">
                         {{ csrf_field() }}
                      <label><input type="radio" name="rental_flag" value="0"<?php if($owner->rental_flag==0):echo 'checked="checked"';endif;?>>はい</label>
                    　<label><input type="radio" name="rental_flag" value="1"<?php if($owner->rental_flag==1):echo 'checked="checked"';endif;?>>いいえ</label>
                    　<label><input type="radio" name="rental_flag" value="2"<?php if($owner->rental_flag==2):echo 'checked="checked"';endif;?>>同期内の貸与可</label><br>
                    　<input type="hidden" name="id" value={{"$owner->id"}}>
                      
                      <button type="submit" class="btn btn-info">変更する</button>
                  </form>
                
        </div>  
        <div class="col-sm-3">  
            <label for="deleate">登録したデータを削除する。</label> 
               
                  <form action="{{url('delete_ownbook')}}" method="post">
                         {{ csrf_field() }}
                      <label><input type="radio" name="life_flag" value="1"<?php if($owner->life_flag==1):echo 'checked="checked"';endif;?>>はい</label>
                      
                    　<label><input type="radio" name="life_flag" value="0"<?php if($owner->life_flag==0):echo 'checked="checked"';endif;?>>いいえ</label><br>
                    　<label><input type="hidden" name="id" value={{"$owner->id"}}>
                      
                      <button type="submit" class="btn btn-info">削除する</button>
                  </form>
                
        </div>        
    </div>
    <div style="margin-left:40px;">
        <div class="row">
            <p >書籍の内容</p>
            <p class="col-xs-12">{{ $book->BookDiscription}}</p>
        </div>
     </div>
            
     
<!--本の詳細終わり-->   


    

<!--コメントリストを表示する　　-->
<div class="col-sm-2-offset col-sm-8 col-sm-offset-2" style="margin-top:40px;">

@if(isset($comments)>0)
      @foreach($comments as $comment)   
        <div class="panel panel-info coment_list">
        	<div class="panel-heading">
        		<ul style="list-style:none;" >
        		<li class=panel-heading-li>{{$comment->updated_at}}</li>
        		
        		</ul>
        	</div>
        	
        	<div class="panel-body hoge row">
        	    <div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">
        	    <div>{{$comment->keys->key}}</div>
        	    <div>
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
                      <span class="glyphicon glyphicon-star-empty"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                    </div>
                    @endif 
                    @if(($comment->evaluation) == 4)
                    <div class="star-rating-icon">
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star-empty"></span>
                    </div>
                    @endif
                    @if(($comment->evaluation) == 5)
                    <div class="star-rating-icon">
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                      <span class="glyphicon glyphicon-star"></span>
                    </div>
                    @endif
                </div>    
                <div><p>{{$comment->comment_text}}</p></div>
             </div>
             </div>  
        </div>     
      @endforeach
@endif 
</div>
<!--コメントを表示する　終了-->


 @endsection
        
                    
                    




