@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;


?>


 

<!--検索結果　始まり-->
@if(count($data)>0)
<h3 class="col-xs-12" style="border-bottom-style: outset;">{{$keyword}}の検索結果</h3>
@else
<h3 class="col-xs-12" style="border-bottom-style: outset;">{{$keyword}}の検索結果はありませんでした。</h3>
@endif

<div class="row">
<?php $i=0?>

            @foreach($data as $book_list)
        
              @if($i == 0)
                <div class="col-sm-12 border_bottom">
              @endif
                <div class="col-sm-2" style="text-align:center;">
                 <p></p><a href="{{url('rental/'.$book_list->id)}}"><img src="{{$book_list->BookImage}}" width="128" height="180"></img></a></p>
                　 <a href="{{url('rental/'.$book_list->id)}}"><p style="margin-right:auto margin-left:auto;">{{$book_list->BookTitle}}</p></a>
                </div>
                  <?php $i=$i+1 ?>
                  @if($i == 6 || $loop->last)
                  <?php $i=0 ?>
                 </div>
                 @endif
                
            @endforeach
</div>    



<div style=" margin-left: auto; margin-right: auto;">
  <div class="paginate">
  {{ $data->appends(Request::only('keyword'))->links() }}
  </div>
</div>


<!--検索結果　終わり-->
  

 @endsection
  