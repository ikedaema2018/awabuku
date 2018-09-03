@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Category_genru;



?>



<div class="jumbotron text-center">
     <h1>アワブク<small>our books</small></h1>
     <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;g's library</h2>
</div>
    
    @if(Session::has('alert'))
    <div class="alert alert-danger" role="alert">
  　　メッセージ：{{ session('alert') }}
  　　 </div>
　　@endif
   
<!-- スレッド速報 始まり -->

    <div class=box>
        <h3 class="">新着のスレッド</h3>
      
            <div class="well" >
                <ul style="list-style:none;">
                    @if(isset($thread_lists)>0)
                    @foreach($thread_lists as $thread_list)
                        <li><h4><a href="{{url('/thread/'.$thread_list->id)}}">{{$thread_list->thread_sub}}/({{User::find($thread_list->user_name)->name}})</a></h4></li>
                    @endforeach
                    @endif
                </ul>
                <div class= "col-sm-offset-2 col-sm-10 text-center">
                
                    <a href="{{url('threads/')}}">もっと見る</a>
                </div>    
            </div>
       
    </div>
<!-- スレッド速報 終わり -->


    
<!-- カテゴリ別表示 始まり -->    
<div class="row">
    <div class= "col-sm-8">
        <div class="row">
             @if(count($genreBooks)>0)
                @foreach($genreBooks as $genreBookKey => $genreBookValues)
                
                        <div class= "col-sm-12">  
                            @if(count($genreBookValues)>0)
                            <h3>{{$genreBookKey}}</h3>
                            @endif
                        </div>
                
                        
                        @foreach($genreBookValues as $genreBookValue)
                                @if(($loop->iteration)<6)
                                
                                        <div class="col-sm-2" style="backgroud:#ccc,height:250px;">
                                            <ul style="list-style:none;">
                                            <li><a href="{{url('rental/'.$genreBookValue["id"])}}"><img src="{{$genreBookValue["BookImage"]}}" width="128" height="180"></img></a></li>
                                            <li><a href="{{url('rental/'.$genreBookValue["id"])}}">{{$genreBookValue["BookTitle"]}}</a></li>
                                            </ul>
                                        </div>
                                @endif
                                @if(($loop->iteration)==6)
                                @break
                                @endif
                       
                         @endforeach
                         @if(count($genreBookValues)>0)
                            <div class= "col-sm-12 text-right border_bottom">
                                <a href="{{url('category_genre_page/'.Category_genru::where('category_genruname', $genreBookKey)->first()->id)}}">もっと見る</a>
                            </div>  
                         @endif
           
                @endforeach
            @endif
    </div>
    </div>   
<!-- カテゴリ別表示 始まり -->   
    
<!-- レンタル別表示 始まり --> 

    <div class= "col-sm-4 well well-lg" >
     <h3>人気のレンタル書籍</h3>  
        @if(count($rentals)>0)
        @foreach($rentals as $rental)
    
          <ul style="list-style:none;">
             <li><img src="{{Book::find($rental->rental_books->book_id)->BookImage}}" width="128" height="180"></img></li>
             <li><a href="{{url('rental/'.Book::find($rental->rental_books->book_id)->id)}}">{{Book::find($rental->rental_books->book_id)->BookTitle}}</a></li>
        
          </ul>    
    
    
        @endforeach
        @endif
        
    </div>
<!-- レンタル別表示 終わり-->   
    
    
    
</div>    
@endsection