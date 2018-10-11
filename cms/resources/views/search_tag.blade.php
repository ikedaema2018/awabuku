@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;


?>



<div class="row">


 

<!--検索結果　始まり-->

<!--検索結果　始まり-->
@if(count($data)>0)
<h3 class="col-xs-12" style="border-bottom-style: outset;">&nbsp;&nbsp;{{$keyword}}を含むタグの検索結果</h3>
@else
<h3 class="col-xs-12" style="border-bottom-style: outset;">{{$keyword}}の検索結果はありませんでした。</h3>
@endif



<div class="row">
<?php $i=0?>
        @foreach($data as $tags)
          @foreach($tags->books as $book)
          
            @if($i == 0)
               <div class="col-sm-12 border_bottom">
               <h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$tags->tags}}</h4> 
                  @endif
      
                   <div class="col-sm-2" style="text-align:center;">
                  
                           <p><a href="{{url('rental/'.$book->id)}}" class="img-responsive"><img src="{{$book->BookImage}}" width="128" height="180"></img></a></p><br>
                           <a href="{{url('rental/'.$book->id)}}"><p style="margin-right:auto margin-left:auto;">{{$book->BookTitle}}</p></a>
              
                   </div>
                 <?php $i=$i+1?>
                 @if($i == 6 || $loop->last)
                  <?php $i=0 ?>
                  </div>
                 @endif
                
        @endforeach
       @endforeach
 </div>      


<!--検索結果　終わり-->

</div>


<div class="col-sm-8" style="text-align:right;">
  <div class="paginate">
  {{ $data->appends(Request::only('keyword'))->links() }}
  </div>
</div>

 @endsection
  