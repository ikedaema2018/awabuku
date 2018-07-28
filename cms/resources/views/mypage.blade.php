@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;

?>

<h1>マイページ</h1>

   <ul>
       
       
       <!--返却期限を過ぎている本があった時に警告-->
       @if(count($expired_rentals)>0)
       
       <p>返却期限を過ぎている本があります！！！！！！！！！！！！！！！！！！！！！！！！！</p>
       <p>返却期限を過ぎている本がある場合、新規貸出はできません</p>
       
       @foreach($expired_rentals as $expired_rental)
       
       <h1>期限切の本</h1>
        
            <li><p>返却期限：{{$expired_rental->return_day}}</p></li>
            <li><p>{{Book::find($expired_rental->rental_books->book_id)->BookTitle}}</p></li>
            <li><p>{{Book::find($expired_rental->rental_books->book_id)->BookAuthor}}</p></li>
            <li><p>{{Book::find($expired_rental->rental_books->book_id)->isbn13 }}</p></li>
            <li><img src="{{Book::find($expired_rental->rental_books->book_id)->BookImage}}"></img></li>
            <li><p>{{$expired_rental->rental_books->owner}}さんの本</p></li>
           
            
        <div>
        <p>◆本を返却する◆</p>
            <form action="{{url('return/'.$expired_rental->id)}}" method="post">
            {{ csrf_field() }}
              <input type ="hidden" name="rental_id" value="{{ $expired_rental->book_id }}">
               <input type ="hidden" name="owner_id" value="{{ $expired_rental->owner_id }}">
                 <button type="submit">返却する</button>
             
            </form>
         </div> 
        @endforeach   
       
       @endif
       
       
       
       <h1>★現在借りている本★</h1>
        @if(count($rentals) > 0 )
        @foreach($rentals as $rental)
        
            <li><p>返却期限：{{$rental->return_day}}</p></li>
            <li><p>{{Book::find($rental->rental_books->book_id)->BookTitle}}</p></li>
            <li><p>{{Book::find($rental->rental_books->book_id)->BookAuthor}}</p></li>
            <li><p>{{Book::find($rental->rental_books->book_id)->isbn13 }}</p></li>
            <li><img src="{{Book::find($rental->rental_books->book_id)->BookImage}}"></img></li>
            <li><p>{{$rental->rental_books->owner}}さんの本</p></li>
           
            
        <div>
        <p>◆本を返却する◆</p>
            <form action="{{url('return/'.$rental->id)}}" method="post">
            {{ csrf_field() }}
              <input type ="hidden" name="rental_id" value="{{ $rental->book_id }}">
               <input type ="hidden" name="owner_id" value="{{ $rental->owner_id }}">
                 <button type="submit">返却する</button>
             
            </form>
         </div> 
        @endforeach   
 
        @else
            <p>現在借りている本はありません</p>
        
        @endif
        
        
    
        
    </ul>  
    
    
    
    <ul>
     <p>★所有している本★</p>
     
        @if(isset($owners)>0)
        @foreach($owners as $owner)
 
            <li><p>{{ $owner->books->BookTitle }}</p></li>
            <li><p>{{ $owner->books->BookAuthor }}</p></li>
            <li><p>{{ $owner->books->isbn10 }}</p></li>
            <li><p>{{ $owner->books->isbn13 }}</p></li>
            <li><p>{{ $owner->books->PubrishedDate}}</p></li>      
            <li><p>{{ $owner->books->BookDiscription}}</p></li>
            <li><img src="{{$owner->books->BookImage}}"></img></li>
            <li><p>{{$owner->owner}}</p></li>
            
            @foreach($owner->rentals as $aaa)
            @if($aaa->return_flag == 1)
            <p>{{User::find($aaa->user_id)->name}}に貸出中</p>
            @endif
            @endforeach
        
        @endforeach   
        @endif
       
    </ul>





 @endsection
