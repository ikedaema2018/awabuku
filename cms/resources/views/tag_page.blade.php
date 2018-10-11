@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Category;
?>


<div>
   <h3 style="border-bottom-style: outset;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;タグ：{{$tag->tags}}</h3>
</div>    

<div class="row">   
<?php $i=0?>
   @if(count($tag)>0)
     @foreach($tag->books as $book)
     
      @if($i==0)
        <div class="col-sm-12 border_bottom">
      @endif
            <div class= "col-sm-2" style="text-align:center;">
             
                 <a href="{{url('rental/'.$book->id)}}"><img src="{{$book->BookImage}}"></img></a>
                 <a href="{{url('rental/'.$book->id)}}"><p style="margin-right:auto margin-left:auto;">{{$book->BookTitle}}</p></a>
              
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