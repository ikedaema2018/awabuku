@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Comment;

?>
<!--ジャンボトロン-->
    <div class="container">

        <div class="jumbotron text-center">
           <span navbar-brand><img class="avater img-circle" src="{{$user->avater}}"></img></span>
            <h3>{{$user->name}}さんのBookList</h3>
        </div>
    
    </div>
<!--ジャンボトロン終わり-->



<!--所有している本-->
<h2 class="col-xs-12">{{$user->name}}さんがコメントをいれた書籍</h2>　
        @if(isset($books)>0)
        <?php $i=0 ?>
            @foreach($books as $book)

            
              @if($i == 0)
                <div class="col-sm-12 border_bottom">
              @endif
                <div class="col-sm-2">
                <ul class="sample">
                 <li>
                    <img src="{{$book["BookImage"]}}" class="img-responsive" width="128" height="180"></img>
                    <a href="">{{ $book["BookTitle"] }}</a>
                    <p>{{ $book["BookAuthor"] }}</p>
       
                   <div class="col-sm-6 text-center">
                       
                 <li>              
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#sampleModal1">
            	  コメントを見る
                </button>

        <!-- コメント入力モーダル -->
        <div class="modal fade" id="sampleModal1" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
            <!--モーダルヘッダー2    -->
                	<div class="modal-header">
                		<button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                	    <h3 class="modal-title">{{ $book["BookTitle"] }}</h3>
                	</div>
        
            <!--モーダルボディー2    -->
                	<div class="modal-body" style="padding:43px;">
                		<h4 style="text-decoration:underline; text-decoration-color:#FFFF00;">おすすめコメント</h4>
                        @foreach($user_comments as $user_comment)
                        @if($user_comment->book_id == $book["id"])
                                <p>{{$user_comment->comment_text}}</p>
        
                        @endif
                        @endforeach
                    </div>
              <!--モーダルフッター    -->
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
              </div>
        
            </div>
        </div>
        </div>

                 </li>
                 <li>
                     <a href="{{url('/rental/'.$book["id"])}}">レンタルする</a>
                 </li>     
                 </ul>
                </div>
                  <?php $i=$i+1 ?>
                  @if($i == 6 || $loop->last)
                  <?php $i=0 ?>
                 </div>
                @endif
                
            @endforeach
        @endif
<!--所有している本　終わり-->

<!--レンタル履歴　はじめ-->
 <h2 class="col-xs-12">レンタル履歴</h2>
    @if(isset($rentaled_books)>0)
         <?php $i=0?>
          @foreach($rentaled_books as $rentaled_book)
              @if($i==0)
               <div class="col-sm-12 border_bottom">
                      @endif
                       <div class="col-sm-2">
                            <ul class="sample">
                                <li>
                                <img src="{{$rentaled_book["BookImage"]}}" class="img-responsive" width="128" height="180"></img>
                                
                                <p>{{ $rentaled_book["BookTitle"] }}</p>
                                <p>{{ $rentaled_book["BookAuthor"] }}</p>
                                 <p>おすすめコメントを見る</p> 
                                 <a href="{{url('mypage/'.Comment::find($book["id"])->owner_id)}}"><p>おすすめコメントを見る</p></a>
                                 <a href="{{ url('rental/'.$rentaled_book["id"]) }}"><p>レンタルする</p></a>
                                
                                </li>
                            </ul>
                 　     </div>
                 
                     　    <?php $i=$i+1?>
                     　      @if($i == 6 || $loop->last)
                     　      <?php $i=0 ?>
            　</div>
             　    @endif
         @endforeach   
    @endif





 @endsection
  