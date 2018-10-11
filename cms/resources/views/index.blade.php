@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Category_genre;



?>

@if(count($topic) > 0 )

        <div class="jumbotron"  style="background-color:#fffaf0;">
           <div> 
                <div>
                    <ul style="list-style:none;" class="sample">
                     <li><span><img class="avater img-circle" src="{{$topic_user->avater}}"></img></span></li>
                     <li><p style="font-size:15px;"><a href="{{url('user_search_page/'.$topic_user->id)}}">{{$topic_user->name}}</a>さんのオススメ</p></li>
                    </ul>
                </div>
                <div class="row">
                    <div  class="col-sm-3" style="text-align: center;">
                        <a href="{{url('rental/'.$topic_book->id)}}"><img src="{{$topic_book->BookImage}}" width="128" height="180"></img></a></li>
                    </div>
                    <div  class="col-sm-9">    
                        <ul style="list-style-type: none;">    
                            <li>
                                <div>
                                    
                                    <a href="{{url('rental/'.$topic_book->id)}}"><p><b>{{$topic_book->BookTitle}}</b></p></a>
                                      <div class="col-sm-12"> 
                                    　 <p class="cut_txt">{{$topic->comment_text}}</p>
                                    　</div>
                                    　
                                
                                    <div class="col-sm-offset-10 col-sm-2">
                                      <a href="{{url('book_comment/'.$topic->id)}}">もっとみる</a>
                                    </div>
                                
                                </div>    
                            </li>
                        </ul>
                    </div> 
                </div>    
           </div>
        </div>
    @endif
    

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
                        <li><p><b><a href="{{url('/thread/'.$thread_list->id)}}">{{$thread_list->thread_sub}}/({{User::find($thread_list->user_name)->name}})</a></b></p></li>
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
    
    <div class="col-sm-offset-1 col-sm-7">
        <div class="row">
             @if(count($genreBooks)>0)
                @foreach($genreBooks as $genreBookKey => $genreBookValues)
                
                        <div class="col-sm-12">  
                            @if(count($genreBookValues)>0)
                            <h3>{{$genreBookKey}}</h3>
                            @endif
                        </div>
                
                        
                        @foreach($genreBookValues as $genreBookValue)
                                @if(($loop->iteration)<6)
                                
                                        <div class="col-sm-2" style="text-align:center;">
                                           
                                            <a href="{{url('rental/'.$genreBookValue["id"])}}"><img src="{{$genreBookValue["BookImage"]}}" width="128" height="180"></img></a>
                                            <a href="{{url('rental/'.$genreBookValue["id"])}}"><p>{{$genreBookValue["BookTitle"]}}</p></a>
                                            
                                        </div>
                                @endif
                                @if(($loop->iteration)==6)
                                @break
                                @endif
                       
                         @endforeach
                         @if(count($genreBookValues)>0)
                            <div class= "col-sm-12 text-right border_bottom">
                                <a href="{{url('category_genre_page/'.Category_genre::where('category_genrename', $genreBookKey)->first()->id)}}">もっと見る</a>
                            </div>  
                         @endif
           
                @endforeach
            @endif
    </div>
    </div>   
<!-- カテゴリ別表示 始まり -->   
    
<!-- レンタル別表示 始まり --> 

    <div class= "col-sm-4" >
    
        
     <div>
        <p style="border-bottom-style: outset;">詳細検索</p>
          <form class="form-inline" action="{{url('/search')}}">
            <div class="input-group serach_box col-xs-12">
          
              <input type="text" class="form-control keyword_search" name="keyword" value="" placeholder="書名・著者名を検索する。" >
              <span class="input-group-btn">
                <button type="submit"class="btn btn-info" name="search_button">
                  <i class='glyphicon glyphicon-search'></i></button>
                
              </span>
           </div>  
          </form>
          <form class="form-inline" action="{{url('/search_tag')}}">
            <div class="input-group serach_box col-xs-12">
         
              <input type="text" class="form-control keyword_search_tag" name="keyword" value="" placeholder="タグを検索する" >
              <span class="input-group-btn">
                <button type="submit"class="btn btn-info" name="search_button">
                  <i class='glyphicon glyphicon-search'></i></button>
                
              </span>
           </div>  
          </form>
    </div>      
    <div>
      <p>タグリスト<p>
      @if(isset($tags)>0)  
      @foreach($tags as $tag)
      <a  class="tag" href="{{url('tag_page/'.$tag->id)}}">{{$tag->tags}}</a>
     
      @endforeach
      @endif
        
    </div>      
      
    <div>    
     <h3>人気のレンタル書籍</h3>  
        @if(count($rentals)>0)
        @foreach($rentals as $rental)
          <ul style="list-style:none;">
             <li>
                 <div style="text-align:center;">
                     <img src="{{Book::find($rental->rental_books->book_id)->BookImage}}" width="128" height="180"></img></br>
                     <a href="{{url('rental/'.Book::find($rental->rental_books->book_id)->id)}}"><p style="margin-right:auto margin-left:auto;"">{{Book::find($rental->rental_books->book_id)->BookTitle}}</p></a>
                 </div>
             </li>  
           </ul>    
        @endforeach
        @endif
    </div>
        
          
          
          
            
</div>    
       
</div>
<!-- レンタル別表示 終わり-->   
    
    
    
</div> 

@endsection
@section('footer')