@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;


?>



<div class="row">
 

 
<div class="<col-sm-12</col-sm->" style="text-align:right;">
  <div class="paginate">
  {{ $data->appends(Request::only('keyword'))->links() }}
  </div>
</div>
 

<!--検索結果　始まり-->

<?php $i=0?>
        @foreach($data as $tags)
       
         @foreach($tags->books as $book)
         
         @if($i == 0)
                <div class="col-sm-12 border_bottom">
              @endif
                <div class="col-sm-2">
                <ul class="sample">
                 <li>
                    <a href="{{url('rental/'.$book->id)}}" class="img-responsive" width="128" height="180">
                        <img src="{{$book->BookImage}}" width="128" height="180"></img></a>
                     <a href="{{url('rental/'.$book->id)}}">{{$book->BookTitle}}</a><br>
                 </li>
                 </ul>
                </div>
                      @if($i == 6 || $loop->last)
                      <?php $i=0 ?>
                      </div>
                       @endif
                
        @endforeach
       @endforeach
    


<!--検索結果　終わり-->


</div>


 @endsection
  