@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Category_genru;



?>

<!-- G'sの本一覧 -->    
<div class= "col-sm-12">  
    @if(count($owners)>0)
       </div>
       <?php $i=0?>
         @foreach($owners as $owner)
         @if($i == 0)
          <div class="col-sm-12 border_bottom">
         @endif      
            <div class="col-sm-2" style="backgroud:#ccc,height:250px;">
                <ul style="list-style:none;">
                    <li><a href="{{url('rental/'.$owner["id"])}}"><img src="{{Book::find($owner->book_id)->BookImage}}" width="128" height="180"></img></a></li>
                    <li><a href="{{url('rental/'.$owner["id"])}}">{{Book::find($owner->book_id)->BookTitle}}</a></li>
                    <li><a href="{{url('gsbook/'.$owner->id)}}"><button type="button" class="btn-success"n>コメントを読む/書く</button></a></li>
                </ul>
                
            </div>
         <?php $i=$i+1?>
         @if($i == 6 || $loop->last)
         <?php $i=$i+1 ?>
         </div>
         @endif
         @endforeach
     @endif
</div>
 
  
@endsection