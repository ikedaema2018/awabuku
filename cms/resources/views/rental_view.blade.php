@extends('layouts.app')

@section('content')

<?php

use App\Comment;
use App\User;
use App\Rental;
use App\Category;

?>


<div class="row">
    <div class="col-sm-3" style="text-align: center;">
        <img src="{{ $book->BookImage}}" height="200px;"></img>
    </div>
    <div class="col-sm-9">
        <h2>{{ $book->BookTitle }}</h2>
        <p>{{ $book->BookAuthor }}</p>
        <p>{{ $book->isbn10 }}/&nbsp;{{ $book->isbn13 }}</p>
        <p>{{ $book->PublishedDate}}</p>
         @if(isset($comment_lists)>0)
         @foreach($category_lists as $category_list)
        <p>{{ Category::find($category_list->category_id)->category_name}}</p>
         @endforeach
         @endif
    </div>
</div>  
<div class="col-xs-offset-1 col-xs-10 col-xs-offset-2">
    <div class="row">
        <p>書籍の内容</p>
        <p class="col-xs-12">{{ $book->BookDiscription}}</p>
    </div>
    
    <div class="row">
        <p>カテゴリ</p>
        @if(isset($category_lists)> 0 )
        @foreach($category_lists as $category_list)
        <a  href="{{url('category_page/'.$category_list->category_id)}}"class="col-xs-12">{{Category::find($category_list->category_id)->category_name}}</a></p>
        @endforeach
        @endif
    </div>
    <div class="row">
        <p>タグ</p>
        @foreach($book -> tags as $tag)
         <a  class="tag" href="{{url('tag_page/'.$tag->id)}}" class="col-xs-12">{{$tag->tags}}</a>
        @endforeach
    </div> 
    
        
    <div class="row" style="margin-bottom:30px;">
        <p style="border-bottom-style: outset;">おすすめコメント</p>
       
             @if(isset($comments)>0)
             @foreach($comments as $comment)
             <div>
                 <a href="{{url('book_comment/'.$comment->id)}}"><p class="col-xs-12">{{$comment->comment_text}}({{User::find($comment->user_id)->name}}</a>)</p>
             </div>  
             @endforeach
             @endif
    </div>
</div>    
    
                   
<table class="table table-striped" style="display: table"; >
    <tr>
        <th>id</th>
        <th>book's</th>
        <th>貸与可否</th>
        <th>借りている人</th>
        <th>返却予定</th>
        <th>借ります！</th>
    </tr>
            
    @if(isset($owners)>0)
    @foreach($owners as $owner)
     <tbody>
        
            <tr>
                <td style="vertical-align: middle;">
                  <p>{{$owner->id}}</p>
                </td>
                <td style="vertical-align: middle;">
                    <p>{{User::find($owner->user_id)->name}}</p>
                </td>  
                <td class="warning" style="vertical-align: middle;">
                    @if ($owner->rental_flag === 1)
                    <p>不可</p>
                    @elseif ($owner->rental_flag == 0 && $owner->return_flag == 0)
                    <p>貸出可能</p>
                    @elseif ($owner->rental_flag == 2 && $owner->return_flag == 0 && $owner->user->group_id == Auth::user()->group_id)
                    <p>同期内のみ可能</p>
                    @elseif ($owner->rental_flag == 0 && $owner->return_flag== 1)
                    <p>貸出中</p>
                    @endif
                </td>
                 <td style="vertical-align: middle;">
                   
                   <p>
                    @foreach($rentals as $rental)
                    @if($rental->owner_id == $owner->id)
                    {{User::find($rental->user_id)->name}}
                    @endif
                    @endforeach
                   </p>
            
                   
                </td>                
                <td style="vertical-align: middle;">
                　<p>
                　  @foreach($rentals as $rental)
                    @if($rental->owner_id == $owner->id)
                    {{$rental->return_day->format('Y-m-d')}}
                    @endif
                    @endforeach
                　</p>
                </td> 
                 <td style="vertical-align: middle; text-align: center;">
                    @if ($owner->rental_flag == 0 && $owner->return_flag == 0)
                        <form action="{{ url('book_rental') }}" method="post">
                            {{ csrf_field() }} 
                         <input type="hidden" name="return_flag" value="{{ $owner->return_flag }}">
                         <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                         <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                         <button type="submit">本を借りる</button>
                        </form>
                     @elseif ($owner->rental_flag == 2 && $owner->return_flag == 0 && $owner->user->group_id == Auth::user()->group_id)
                        <form action="{{ url('book_rental') }}" method="post">
                            {{ csrf_field() }} 
                         <input type="hidden" name="return_flag" value="{{ $owner->return_flag }}">
                         <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                         <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                         <button type="submit">本を借りる</button>
                        </form>
                     @else
                    <p>---</p>
                    
                    @endif
                </td>   
            </tr>
        </tbody>
            
            @endforeach
            @endif
    </table>

 @endsection
        
                    
                    




