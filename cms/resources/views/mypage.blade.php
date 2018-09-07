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
           <span navbar-brand><img class="avater img-circle" src="{{Auth::user()->avater}}"></img></span>
            <h3>{{Auth::user()->name}}さんのマイページ</h3>
        </div>
    
    </div>
<!--ジャンボトロン終わり-->





  <div class="row">
       <!--返却期限を過ぎている本があった時に警告-->
       @if(count($expired_rentals)>0)
       <div class="alert alert-danger" role="alert">
       <p>返却期限を過ぎている本があります。</p>
       <p>返却期限を過ぎている本がある場合、新規貸出はできません</p>
       </div>
       <h2>期限切の本</h2>
       
       <?php $i=0?>
           @foreach($expired_rentals as $expired_rental)
             @if($i == 0)
                 <div class="col-sm-12 border_bottom">
             @endif
               <div class="col-sm-2">
           
                <ul class="sample">
                    
                    
                    <li><p>返却期限：{{$expired_rental->return_day->format('Y-m-d')}}</p></li>
                    <li><p>{{Book::find($expired_rental->rental_books->book_id)->BookTitle}}</p></li>
                    <li><p>{{Book::find($expired_rental->rental_books->book_id)->BookAuthor}}</p></li>
                    <li><p>{{Book::find($expired_rental->rental_books->book_id)->isbn13 }}</p></li>
                    <li><img src="{{Book::find($expired_rental->rental_books->book_id)->BookImage}}"></img></li>
                    <li><p>{{$expired_rental->rental_books->owner}}さんの本</p></li>
                    <li>
                        <form action="{{url('return/'.$expired_rental->id)}}" method="post">
                        {{ csrf_field() }}
                          <input type ="hidden" name="rental_id" value="{{ $expired_rental->book_id }}">
                          <input type ="hidden" name="owner_id" value="{{ $expired_rental->owner_id }}">
                        <button type="submit">返却する</button>
                        </form>
                    </li>    
                </ul>
                </div>
               <?php $i=$i+1?>
               @if($i == 6 || $loop->last)
               <?php $i=0?>
               </div>
               @endif
                     
             @endforeach   
       
       @endif
       
 
<!--レンタル本　はじめ-->
 
<div>  
<h2 class="col-xs-12">レンタルしている本</h2>　
@if(count($rentals) > 0 )
  <?php $i=0?>
    @foreach($rentals as $rental)
         @if($i == 0)
             <div class="col-sm-12 border_bottom">
         @endif
        <div class="col-sm-2">
                <ul class="sample">
                 <li>
                    <img src="{{Book::find($rental->rental_books->book_id)->BookImage}}"width="128" height="180"></img> 
                    <p>{{Book::find($rental->rental_books->book_id)->BookTitle}}</p>
                    <p>返却期限：</p>
                    <p>{{date($rental->return_day)}}</p>
                    <p>{{Book::find($rental->rental_books->book_id)->BookAuthor}}</p>
                    <p>{{Book::find($rental->rental_books->book_id)->isbn13 }}</p>
                    <p>{{$rental->rental_books->owner}}さんの本</p>
                    
                        <form action="{{url('return/'.$rental->id)}}" method="post">
                        {{ csrf_field() }}
                          <input type ="hidden" name="rental_id" value="{{ $rental->book_id }}">
                          <input type ="hidden" name="owner_id" value="{{ $rental->owner_id }}">
                         <button type="submit" class="btn-success">返却する</button>
                        </form>
                  </li>    
                </ul>
        </div>
        <?php $i=$i+1?>
      　@if($i==6 || $roop->last)  
       　<?php $i=0?>
       　</div>
      　@endif
    @endforeach
@else
            <p>現在借りている本はありません</p>
        
@endif
</div>
<!--レンタル本　終わり--> 


<!--所有している本-->
<h2 class="col-xs-12">所有している本</h2>　
        @if(isset($owners)>0)
        <?php $i=0 ?>
            @foreach($owners as $owner)
              @if($i == 0)
                <div class="col-sm-12 border_bottom">
              @endif
                <div class="col-sm-2">
                <ul class="sample">
                 <li>
                    <img src="{{$owner->books->BookImage}}" class="img-responsive" width="128" height="180"></img>
                    <a href="">{{ $owner->books->BookTitle }}</a>
                    <p>{{ $owner->books->BookAuthor }}</p>
                    <p>{{ $owner->books->Published}}</p>      
                    <a href="{{url('mypage/'.$owner->id)}}">詳細へ</a>
    
                    @foreach($owner->rentals as $aaa)
                    @if($aaa->return_flag == 1)
                    <p style="color:red">{{User::find($aaa->user_id)->name}}さんに{{$aaa->return_day->format('Y-m-d')}}まで貸出中</p>
                    @endif
                    @endforeach
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
    @if(isset($returned_rentals)>0)
         <?php $i=0?>
          @foreach($returned_rentals as $returned_rental)
              @if($i==0)
               <div class="col-sm-12 border_bottom">
                      @endif
                       <div class="col-sm-2">
                            <ul class="sample">
                                <li>
                                <img src="{{Book::find($returned_rental->rental_books->book_id)->BookImage}}" class="img-responsive" width="128" height="180"></img>
                                <a href="">{{Book::find($returned_rental->rental_books->book_id)->BookTitle}}</a>
                                <p>{{ Book::find($returned_rental->rental_books->book_id)->BookAuthor}}</p>
                                <p>{{ Book::find($returned_rental->rental_books->book_id)->isbn13 }}</p>
                                <p>{{ Book::find($returned_rental->rental_books->book_id)->Published}}</p>     
                                <a href="{{url('mypage/'.$owner->id)}}">詳細へ</a>
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
  