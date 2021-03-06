@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Category;
?>

 



 @if(count($genreBooks)>0)
 <div class="row">  
 <div class="col-sm-3">
   @foreach($genreBooks as $genreBookKey  => $genreBookValues)
   
        <div class= "col-sm-12">  
            @if(count($genreBookValues)>0)
            <h3>{{$genreBookKey}}</h3>
            @endif
        </div>
        <div class="col-sm-12"> 
         <ul class="category">
          @foreach($categories as $category)
          <li><a href="{{url('category_page/'.$category->id)}}"><p>{{$category->category_name}}</a></p></li>
          @endforeach
         </ul>
       </div>
  </div>             
  <div class="col-sm-9">
         <?php $i=0?>
            @foreach($genreBookValues as $genreBookValue)
              @if($i==0)
               <div class="col-sm-12 border_bottom">
              @endif
                  <div class= "col-sm-2">
                   <ul class="sample">
                     <li>
                       <a href="{{url('category_page/'.current($genreBookValue["pivot"]))}}">{{Category::find(current($genreBookValue["pivot"]))->category_name}}</a><br>
                       <a href="{{url('rental/'.$genreBookValue["id"])}}"><img src="{{$genreBookValue["BookImage"]}}"></img></a><br>
                       <a href="{{url('rental/'.$genreBookValue["id"])}}">{{$genreBookValue["BookTitle"]}}</a><br>
                     </li>
                   </ul>
                  </div>
              <?php $i=$i+1?>
                   @if($i == 6 || $loop->last)
                   <?php $i=0 ?>
      　       </div>
             　   @endif
            @endforeach 　    
    @endforeach
    </div>
  @endif
               
               
               

</div> 


@endsection


