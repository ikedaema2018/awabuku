@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;

?>
   <h2>下記本を返却しますか？</h2>
  <div class="row">
     <p class="col-sm-3" style="text-align:center;">
        <img src="{{Book::find($rental->rental_books->book_id)->BookImage}}"></img>    
     </p>    
     <div class="col-sm-9"　style="background:#CCC;height:200px;">  
       <ul style="list-style:none;">
            <li><h3>{{Book::find($rental->rental_books->book_id)->BookTitle}}</h3></li>
            <li><span style=”color:red”;>返却期限：{{$rental->return_day}}</span></li>
            <li><p>{{Book::find($rental->rental_books->book_id)->BookAuthor}}</p></li>
            <li><p>{{Book::find($rental->rental_books->book_id)->isbn13 }}</p></li>
            <li><p>{{User::find($rental->rental_books->user_id)->name}}さんの本</p></li>
       </ul>     
    </div>
  </div>    
   
       
    <div class="col-sm-offset-2 col-sm-8">
        
            <form action="{{ url('return_comment') }}" method="post">
        　
            {{ csrf_field() }}
         <p style="border_bottom:"><b>本の感想を入力して、返却してください。</b></p><br>
                 <div class="form-group"> 
          <p><b>特徴</b></p>
          <div class="block-contents">
              @if(count($keys)>0)
              @foreach($keys as $key)
                <label class="radio-inline"><input type ="radio" name="key" value="{{$key->id}}">{{$key->key}}</label>
              @endforeach
              @endif
         </div>    
       
     
        　<div class="form-group">
          　<p><b>評価</b></p> 
       　　　 <div class="evaluation center">
                <input id="star1" type="radio" name="evaluation" value="5" />
                <label for="star1"class="radio-inline"><span class="text">最高</span>★</label>
                <input id="star2" type="radio" name="evaluation" value="4" />
                <label for="star2"class="radio-inline"><span class="text">良い</span>★</label>
                <input id="star3" type="radio" name="evaluation" value="3" />
                <label for="star3"class="radio-inline"><span class="text">普通</span>★</label>
                <input id="star4" type="radio" name="evaluation" value="2" />
                <label for="star4"class="radio-inline"><span class="text">悪い</span>★</label>
                <input id="star5" type="radio" name="evaluation" value="1" />
                <label for="star5"class="radio-inline"><span class="text">最悪</span>★</label>
        　　　</div>
    　　　</div> 
    
    　　　<div class="form-group">  
         　<label>おすすめポイント</label>
              <div class="center">
              <textarea  rows="5" class="form-control" name="comment_text" placeholder="例）++の勉強をしたい人におすすめ" autofocu></textarea>
              </div>
    　　　</div>   
                <input type ="hidden" name="rental_id" value="{{ $rental->id }}">
                <input type ="hidden" name="owner_id" value="{{ $rental->owner_id }}">
                <input type ="hidden" name="book_id" value="{{ Book::find($rental->rental_books->book_id)->id}}">

                <button type="submit">投稿して返却する</button>
                
            </form>
         
    </div> 

   



 @endsection
