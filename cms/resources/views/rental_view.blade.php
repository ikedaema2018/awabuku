@extends('layouts.app')

@section('content')

<?php

use App\Comment;
use App\User;
use App\Rental;
use App\Category;

?>


<div class="row">
    <div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">    
    <h3>Book Rental</h3>
    </div>
    <div class="col-sm-3" style="text-align: center;">
        <img src="{{ $book->BookImage}}" height="200px;"></img>
    </div>
    <div class="col-sm-9">
      <div style="margin-left:40px;">
        <h3>{{ $book->BookTitle }}</h3>
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
</div>  
<div class="col-xs-offset-1 col-xs-10 col-xs-offset-1">
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

<!--コメントを入力する　始まり-->

<div class="row">
<div class="text-center" style="margin-bottom:40px;">       
        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#sampleModal1" style="width:300px;">
    	  おすすめコメントを入力する
        </button>
</div>
<!-- コメント入力モーダル -->
<div class="modal fade" id="sampleModal1" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
    <!--モーダルヘッダー2    -->
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal"><span>×</span></button>
        	    <h3 class="modal-title">{{ $book->BookTitle }}</h3>
        	</div>
        			    
    <!--モーダルボディー2    -->    			    
        	<div class="modal-body" style="padding:43px;">
        		<h4 style="text-decoration:underline; text-decoration-color:#FFFF00;">コメントを入力してください</h4>
  
         <!--form開始   -->     
              <form action="{{url('rental')}}" method="post" class="horizontal">
                {{ csrf_field() }}
                    <div class="form-group"> 
                      <p><b>おすすめしたい人</b></p>
                      <div class="block-contents">
                          @if(count($keys)>0)
                          @foreach($keys as $key)
                            <label class="radio-inline"><input type ="radio" name="key" value="{{$key->id}}">{{$key->key}}</label>
                          @endforeach
                          @endif
         　　　　　　　　　　</div>    
    　　　　　　　　　　　　</div>  
                     <div class="form-group">
                      <p><b>評価</b></p> 
                        <div class="evaluation center">
                            <input id="star1" type="radio" name="evaluation" value="5" />
                            <label for="star1"class="radio-inline"><span class="text">最高</span>★</label>
                            <input id="star2" type="radio" name="evaluation" value="4" />
                            <label for="star2"class="radio-inline"><span class="text">良い</span>★</label>
                            <input id="star3" type="radio" name="evaluation" value="3" />
                            <label for="star3"class="radio-inline"><span class="text">普通</span>★</label>
                            <input id="star4" type="radio" name="evaluation" value="2" />
                            <label for="star4"class="radio-inline"><span class="text">悪い</span>★</label>
                            <input id="star5" type="radio" name="evaluation" value="1" />
                            <label for="star5"class="radio-inline"><span class="text">最悪</span>★</label>
                        </div>
                    </div> 
                    <div class="form-group">  
                          <label>おすすめポイント</label>
                          <div class="center">
                          <textarea  rows="5" class="form-control" name="comment_text" placeholder="例）++の勉強をしたい人におすすめ" autofocu></textarea>
                          </div>
                    </div>      
                    
    
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-9 text-right">
                       <input type="hidden" name="owner_id" value="{{ $book_owner->id }}"></input>
                      <input type="hidden" name="book_id" value="{{ $book->id }}"></input>
                      <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                      
                      <button type="submit" class="btn btn-primary"/ value="">投稿する</button>
                  </div>
                </div>
     
              </form>
            </div>
      <!--モーダルフッター    --> 		  
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
      </div>
      
          		
    </div>
　</div>
</div>

<!--コメントを入力する　終わり-->

<div class="col-xs-offset-1 col-xs-10 col-xs-offset-1"　style="margin-bottom:100px;">

<table class="table table-striped" style="display: table;" >
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
</div>
</div>
 @endsection
        
                    
                    




