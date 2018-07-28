@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;

?>

<h1>マイページ</h1>

   <ul>
       <p>下記本を返却しますか？</p>
 
            <li><p>返却期限：{{$rental->return_day}}</p></li>
            <li><p>{{Book::find($rental->rental_books->book_id)->BookTitle}}</p></li>
            <li><p>{{Book::find($rental->rental_books->book_id)->BookAuthor}}</p></li>
            <li><p>{{Book::find($rental->rental_books->book_id)->isbn13 }}</p></li>
            <li><img src="{{Book::find($rental->rental_books->book_id)->BookImage}}"></img></li>
            <li><p>{{User::find($rental->rental_books->user_id)->name}}さんの本</p></li>
           
            
        <div>
        <p>◆本を返却する◆</p>
            <form action="{{ url('return_comment') }}" method="post">
        　
            {{ csrf_field() }}
            <p>本の感想を入力して、返却してください。</p><br>
             <ul>
                <li>  
                <label for="">こんな人におすすめ！</label><br>
                    <input type ="radio" name="person" value="1">初心者
                    <input type ="radio" name="person" value="2">中級者
                    <input type ="radio" name="person" value="3">上級者
                    <input type ="radio" name="person" value="4">その他
                </label>
                </li>     
                <li>
                <label for="">評価</label><br>
                    <input type ="radio" name="evolution" value="1">❤️
                    <input type ="radio" name="evolution" value="2">❤️❤️
                    <input type ="radio" name="evolution" value="3">❤️❤️❤️
                    <input type ="radio" name="evolution" value="4">その他
                </label>
                </li>
                <li>
                <label for="">おすすめポイント</label><br>
                <input type="text" name="comment_text">
                </li>
                
                <input type ="hidden" name="rental_id" value="{{ $rental->id }}">
                <input type ="hidden" name="owner_id" value="{{ $rental->owner_id }}">
                
                <button type="submit">投稿して返却する</button>
                
            </form>
         </div> 

    </ul>  



 @endsection
