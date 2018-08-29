@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Category;
?>

<div class="jumbotron text-center">
     <h1>アワブク<small>our books</small></h1>
     <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;g's library</h2>
</div>
 
 <div class="row"> 
 
   <h2><a href=""><p>{{$category->category_name}}</a></h2>

</div>

<div class="row">   
 
   @if(count($book_lists)>0)
     @foreach($book_lists as $book_list)
        <div class= "col-sm-2">
          <ul class="sample">
           <li>
             <a href="{{url('rental/'.$book_list->book_id)}}"><img src="{{Book::find($book_list->book_id)->BookImage}}"></img></a><br>
             <a href="{{url('rental/'.$book_list->book_id)}}">{{Book::find($book_list->book_id)->BookTitle}}</a><br>
           </li>          
          </ul>
        </div>
    @endforeach
  @endif             
 </div>     
</div> 

@endsection