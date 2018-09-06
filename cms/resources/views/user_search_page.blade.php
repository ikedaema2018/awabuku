@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;

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
<h2 class="col-xs-12">所有している本</h2>　
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
                    <p>おすすめコメントを見る</p>
                    <a href="{{ url('rental/'.$book["id"])}}"<p>本をレンタルする</p></a>
                   
                 
                    
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
  