@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;


?>
 <div class="jumbotron text-center">
     
     <span navbar-brand><img class="avater img-circle" src="{{Auth::user()->avater}}"></img></span>
     <h3>{{Auth::user()->name}}さんのマイページ</h3>

</div>

<!--所有している本-->

<div class="container-fluid">
<div class="row">
 

 
<div class="col-sm-8" style="text-align:right;">
  <div class="paginate">
  {{ $data->appends(Request::only('keyword'))->links() }}
  </div>
</div>
 

<!--検索結果　始まり-->
<h2 class="col-xs-12">検索結果</h2>
<?php $i=0?>
            @foreach($data as $book_list)
        
              @if($i == 0)
                <div class="col-sm-12 border_bottom">
              @endif
                <div class="col-sm-2">
                <ul class="sample">
                 <li>
                     <a href="{{url('rental/'.$book_list->id)}}" class="img-responsive" width="128" height="180">
                         <img src="{{$book_list->BookImage}}" width="128" height="180"></img></a>
                     <a href="{{url('rental/'.$book_list->id)}}">{{$book_list->BookTitle}}</a><br>
                 </li>
                 </ul>
                </div>
                  <?php $i=$i+1 ?>
                  @if($i == 6 || $loop->last)
                  <?php $i=0 ?>
                 </div>
                 @endif
                
            @endforeach
        


<!--検索結果　終わり-->



 

 @endsection
  