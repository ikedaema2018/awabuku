@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;


?>
 <div class="jumbotron text-center">
     
     <span navbar-brand><img class="avater img-circle" src="{{Auth::user()->avater}}"></img></span>
     <h3>{{Auth::user()->name}}さんのマイページ</h3>

</div>



  <div class="row">
   
     <ul style="list-style:none;">
       
       
       <!--返却期限を過ぎている本があった時に警告-->
       @if(count($expired_rentals)>0)
       
       <p>返却期限を過ぎている本があります！！！！！！！！！！！！！！！！！！！！！！！！！</p>
       <p>返却期限を過ぎている本がある場合、新規貸出はできません</p>
       
       @foreach($expired_rentals as $expired_rental)
       
       <h2>期限切の本</h2>
        
            <li><p>返却期限：{{$expired_rental->return_day}}</p></li>
            <li><p>{{Book::find($expired_rental->rental_books->book_id)->BookTitle}}</p></li>
            <li><p>{{Book::find($expired_rental->rental_books->book_id)->BookAuthor}}</p></li>
            <li><p>{{Book::find($expired_rental->rental_books->book_id)->isbn13 }}</p></li>
            <li><img src="{{Book::find($expired_rental->rental_books->book_id)->BookImage}}"></img></li>
            <li><p>{{$expired_rental->rental_books->owner}}さんの本</p></li>
           
            
        <div>
        <p>本を返却する◆</p>
            <form action="{{url('return/'.$expired_rental->id)}}" method="post">
            {{ csrf_field() }}
              <input type ="hidden" name="rental_id" value="{{ $expired_rental->book_id }}">
               <input type ="hidden" name="owner_id" value="{{ $expired_rental->owner_id }}">
                 <button type="submit">返却する</button>
             
            </form>
         </div> 
        @endforeach   
       
       @endif
       
       
       
       <h2>現在借りている本</h2>
       <div class="row">
        @if(count($rentals) > 0 )
        @foreach($rentals as $rental)
        
        <div class="col-xs-2" style="backgroud:#ccc,height:250px;">
         <ul style="list-style:none;">
            <li><img src="{{Book::find($rental->rental_books->book_id)->BookImage}}"></img></li> 
            <li><p>{{Book::find($rental->rental_books->book_id)->BookTitle}}</p></li>
            <li><p>返却期限：</p></li>
            <li><p>{{$rental->return_day}}</p></li>
            <li><p>{{Book::find($rental->rental_books->book_id)->BookAuthor}}</p></li>
            <li><p>{{Book::find($rental->rental_books->book_id)->isbn13 }}</p></li>
            
            <li><p>{{$rental->rental_books->owner}}さんの本</p></li>
           
        
            
       
            <li>
                <form action="{{url('return/'.$rental->id)}}" method="post">
                {{ csrf_field() }}
                  <input type ="hidden" name="rental_id" value="{{ $rental->book_id }}">
                  <input type ="hidden" name="owner_id" value="{{ $rental->owner_id }}">
                 <button type="submit" class="btn-success">返却する</button>
                </form>
            </li>
         </div> 
        @endforeach   
 
        @else
            <p>現在借りている本はありません</p>
        
        @endif
         </div>
        
    
        
    </ul>  
    
    
    

    

     <h2 class="col-xs-12">所有している本</h2>
 
        <div class="row">
          @if(isset($owners)>0)
          @foreach($owners as $owner)
    
           <div class="col-xs-2" style="backgroud:#ccc,height:250px;">
                <ul style="list-style:none;">
                    <li><img src="{{$owner->books->BookImage}}" class="img-responsive"></img></li>
                    <li><a href="">{{ $owner->books->BookTitle }}</a></li>
                    <li><p>{{ $owner->books->BookAuthor }}</p></li>
                    <li><p>{{ $owner->books->isbn13 }}</p></li>
                    <li><p>{{ $owner->books->Published}}</p></li>      
                    <li><a href="{{url('mypage/'.$owner->id)}}">詳細へ</a></li>

                    @foreach($owner->rentals as $aaa)
                    @if($aaa->return_flag == 1)
                    <li style="color:red">{{User::find($aaa->user_id)->name}}さんに{{$aaa->return_day}}まで貸出中</p>
                    @endif
                    @endforeach
                </ul>
                
     　   </div>
          @endforeach   
          @endif
     　
     　</div>


 <h2 class="col-xs-12">レンタル履歴</h2>
 
        <div class="row">
          @if(isset($returned_rentals)>0)
          @foreach($returned_rentals as $returned_rental)
    
           <div class="col-xs-2" style="backgroud:#ccc,height:250px;">
                <ul style="list-style:none;">
                    <li><img src="{{Book::find($returned_rental->rental_books->book_id)->BookImage}}" class="img-responsive"></img></li>
                    <li><a href="">{{Book::find($returned_rental->rental_books->book_id)->BookTitle}}</a></li>
                    <li><p>{{ Book::find($returned_rental->rental_books->book_id)->BookAuthor}}</p></li>
                    <li><p>{{ Book::find($returned_rental->rental_books->book_id)->isbn13 }}</p></li>
                    <li><p>{{ Book::find($returned_rental->rental_books->book_id)->Published}}</p></li>      
                    <li><a href="{{url('mypage/'.$owner->id)}}">詳細へ</a></li>
                    
                  
                </ul>
                _
     　   </div>
          @endforeach   
          @endif
     　
     　</div>










 @endsection
  