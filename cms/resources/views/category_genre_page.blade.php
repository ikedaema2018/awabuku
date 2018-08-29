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
  <ul class="category">
   @foreach($categories as $category)
   <li><a href=""><p>{{$category->category_name}}</a></p></li>
   @endforeach
  </ul>
</div>

<div class="row">   
 @if(count($genreBooks)>0)
   @foreach($genreBooks as $genreBookKey  => $genreBookValues)
   
        <div class= "col-sm-12">  
            @if(count($genreBookValues)>0)
            <h3>{{$genreBookKey}}</h3>
            @endif
        </div>
     
      
        <div class="row">
            @foreach($genreBookValues as $genreBookValue)
                <div class= "col-sm-3" style="display:table-cell">
                 <ul class="sample">
                   <li>
                     <a href="{{url('category_page/'.current($genreBookValue["pivot"]))}}">{{Category::find(current($genreBookValue["pivot"]))->category_name}}</a><br>
                     <a href="{{url('rental/'.$genreBookValue["id"])}}"><img src="{{$genreBookValue["BookImage"]}}"></img></a><br>
                     <a href="{{url('rental/'.$genreBookValue["id"])}}">{{$genreBookValue["BookTitle"]}}</a><br>
                   </li>
                 </ul>
                </div>
               @endforeach
               
         <div class= "col-sm-12 text-right">
                        
             @if(count($genreBookValues)>0)
                        <a href="">もっと見る</a>
             @endif
             
        </div>    
        
      

     
 @endforeach
@endif
</div> 


@endsection