@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Category;
?>

 
<h2>{{$tag->tags}}</h2>
<div class="row">   
<?php $i=0?>
   @if(count($tag)>0)
     @foreach($tag->books as $book)
     
      @if($i==0)
        <div class="col-sm-12 border_bottom">
      @endif
            <div class= "col-sm-2">
              <ul class="sample">
               <li>
                 <a href="{{url('rental/'.$book->id)}}"><img src="{{$book->BookImage}}"></img></a><br>
                 <a href="{{url('rental/'.$book->id)}}">{{$book->BookTitle}}</a><br>
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