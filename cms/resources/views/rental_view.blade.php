@extends('layouts.app')

@section('content')

<?php

use App\Comment;
use App\User;
use App\Rental;
use App\Category;

?>


    <div class="row">
        <div class="col-xs-3"　style="background:#CCC;height:200px;"style="text-align:center;">
            <img src="{{ $book->BookImage}}"></img>
        </div>
        <div class="col-xs-9"　style="background:#CCC;height:200px;">
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
    <div style="margin-left:40px;">
    <div class="row">
        <p >書籍の内容</p>
        <p class="col-xs-12">{{ $book->BookDiscription}}</p>
    </div>
   
    <div class="row">
        
        <p class=class="col-xs-offset-1 col-xs-9 col-xs-offset-2" style="border-bottom-style: outset;">おすすめコメント</p>
       
             @if(isset($comments)>0)
             @foreach($comments as $comment)
                 <p class="col-xs-12">{{$comment->comment_text}}(<a href="{{url('user_search_page/'.$comment->user_id)}}">{{User::find($comment->user_id)->name}}</a>)</p>
                
             @endforeach
             @endif
    </div>
    </div>
                 
                 
                    
<table class="table table-striped" style="display: table"; >
    <tr>
        <th>id</th>
        <th>book's Owner</th>
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
                    @if($owner->return_flag == 0)
                    <form action="{{ url('book_rental') }}" method="post">
                        {{ csrf_field() }} 
                     <input type="hidden" name="return_flag" value="{{ $owner->return_flag }}">
                     <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                     <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                     <button type="submit">本を借りる</button>
                    </form>
                    @elseif($owner->return_flag == 1)
                    <p>---</p>
                    
                    @endif
                </td>   
            </tr>
        </tbody>
            
            @endforeach
            @endif
    </table>

 @endsection
        
                    
                    




