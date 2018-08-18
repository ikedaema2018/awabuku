@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;



?>



 <div class="jumbotron text-center">
     <h1>アワブク<small>our books</small></h1>
     <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;g's library</h2>
</div>
    @if(isset($alert))
    <p>返却期限を過ぎている本があるため新規貸出は行えません</p>
    @endif

<!-- スレッド速報 始まり -->

                <div class=box>
                    <h1 class="">新着のスレッド</h1>
                    <div class="dekita_box">
                        <div class="dekita_padding well" >
                            <ul>
                    @if(isset($thread_lists)>0)
                    @foreach($thread_lists as $thread_list)
                    <li><a href="{{url('/thread/'.$thread_list->id)}}">{{$thread_list->thread_sub}}/({{User::find($thread_list->user_name)->name}})</a></li>
                    
                    @endforeach
                    @endif
                    </ul>
                        </div>
    
                    </div>
                </div>
<!-- スレッド速報 終わり -->


    
<!-- カテゴリ別表示 始まり -->    
    <div class="row">
          
        <div class= "col-sm-9">
        @if(count($genreBooks)>0)
            
             <div class="row">
                @foreach($genreBooks as $genreBook)
                    
                     <h2 class="col-xs-12">{{$genreBook['category_id']}}</h2>
                     <h2 class="col-xs-12">{{$genreBook->id}}</h2>
                
              
                            
                     
                   
                @endforeach
                </div>
            
        @endif
        
        
        
         <div><a href="{{url('book')}}">もっと見る</a></div>
    </div>
     
    
<!-- カテゴリ別表示 始まり -->   
    
<!-- レンタル別表示 始まり --> 

<div class= "col-sm-3">
 <h3>人気のレンタル書籍</h3>  
@if(count($rentals)>0)
@foreach($rentals as $rental)

  <ul style="list-style:none;">
     <li><img src="{{Book::find($rental->rental_books->book_id)->BookImage}}"></img></li>
     <li><a href="{{url('rental/'.Book::find($rental->rental_books->book_id)->id)}}">{{Book::find($rental->rental_books->book_id)->BookTitle}}</a></li>

  </ul>    


@endforeach
@endif

</div>
<!-- レンタル別表示 終わり-->   
    
    
    
</div>    
@endsection