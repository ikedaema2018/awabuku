@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Category;
use App\Tag;
?>

 
   <h2>{{$category->category_name}}</h2>


<div class="row">   
<?php $i=0?>
   @if(count($book_lists)>0)
     @foreach($book_lists as $book_list)
      @if($i==0)
        <div class="col-sm-12 border_bottom">
      @endif
            <div class= "col-sm-2">
              <ul class="sample">
               <li>
                 <a href="{{url('rental/'.$book_list->book_id)}}"><img src="{{Book::find($book_list->book_id)->BookImage}}"></img></a><br>
                 <a href="{{url('rental/'.$book_list->book_id)}}">{{Book::find($book_list->book_id)->BookTitle}}</a><br>
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
 </div>     


@endsection