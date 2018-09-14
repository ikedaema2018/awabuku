@extends('layouts.app')

@section('content')

<?php

use App\Comment;
use App\User;
use App\Rental;
use App\Category;
use App\Category_genru;

?>
<div class=row>
 <div class="col-sm-offset-2 col-sm-8 col-sm-offset-2">    
    <h1>personテーブル作成</h1>
    <div class="row">
        <div class="col-xs-3"　style="background:#CCC;height:200px;">
            <img src="{{ $book["BookImage"]}}"></img>
        </div>
        <div class="col-xs-9"　style="background:#CCC;height:200px;">
            <h2>{{ $book->BookTitle }}</h2>
            <p>{{ $book->BookAuthor }}</p>
            <p>{{ $book->isbn10 }}/&nbsp;{{ $book->isbn13 }}</p>
        </div>
    </div>  
    
    <div class="row">
        <p class="col-xs-12">書籍の内容</p>
        <p class="col-xs-12">{{ $book->BookDiscription}}</p>
    </div>

    <table>
        <tr>
            <th>カテゴリ</th>
            <td>
                 <ul style="list-style:none; justify-content:between;">
                @foreach($category_names as $category_name)
                   <li style="display: inline-block;"><a href="{{url('category_genre_page/'.Category_genru::find($category_name["category_genru"])->id)}}">{{Category_genru::find($category_name["category_genru"])->category_genruname}}&nbsp;&nbsp</a></li>
                 @endforeach 
                  <ul>
            </td>
        </tr>
        <tr>
            <th>ジャンル</th>
            <td>
                <ul style="list-style:none; justify-content:between;">
                @foreach($category_names as $category_name)
                 <li style="display: inline-block;">
                     <a href="{{url('category_page/'.$category_name["id"])}}">
                         
                         <p>{{$category_name["category_name"]}}&nbsp;&nbsp</p>
                         
                     </a>
                </li>
                 @endforeach 
                </ul>
            </td>
        </tr>
    </table>       
  </div>
</div> 

<!-- 本を新規登録してからオススメする -->    
<div class="col-sm-6 text-center">       
        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#sampleModal1">
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
              <form action="{{url('gsbook')}}" method="post" class="horizontal">
                {{ csrf_field() }}
                    <div class="form-group"> 
                          <p><b>こんなひとにおすすめ</b></p>
                          <div class="center">
                            <label class="radio-inline"><input type ="radio" name="person" value="1">初心者</label>
                            <label class="radio-inline"><input type ="radio" name="person" value="2">中級者</label>
                            <label class="radio-inline"><input type ="radio" name="person" value="3">上級者</label>
                            <label class="radio-inline"><input type ="radio" name="person" value="4">その他</label>
                         </div>    
                    </div>        
                     
                    <div class="form-group">
                          <p><b>評価</b></p> 
                        <div class="evaluation center">
                            <input id="star1" type="radio" name="evolution" value="5" />
                            <label for="star1"class="radio-inline"><span class="text">最高</span>★</label>
                            <input id="star2" type="radio" name="evolution" value="4" />
                            <label for="star2"class="radio-inline"><span class="text">良い</span>★</label>
                            <input id="star3" type="radio" name="evolution" value="3" />
                            <label for="star3"class="radio-inline"><span class="text">普通</span>★</label>
                            <input id="star4" type="radio" name="evolution" value="2" />
                            <label for="star4"class="radio-inline"><span class="text">悪い</span>★</label>
                            <input id="star5" type="radio" name="evolution" value="1" />
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
                       <input type="hidden" name="owner_id" value="{{ $owner->id }}"></input>
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

<div class="col-sm-6 text-center"> 

        
    	 <a href="{{url('rental/'.$book->id)}}">
    	     <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#sampleModal2">本をレンタルする</button>
        </a>
</div>


<p class="col-xs-12">おすすめコメント</p>
        
<table class="table table-striped">
    <tr>
        <th>ユーザー名</th>
        <th>こんな人</th>
        <th>評価</th>
        <th>オススメポイント</th>
    
    </tr>        
      
      
      
     @if(isset($comments)>0)
     @foreach($comments as $comment)
    <tbody>
        <tr>
            <td>
                <p>{{$comment->user_id}}</p>
            </td>
            <td>
                <p>{{$comment->person}}</p>
            </td>
            <td>
                <p>{{$comment->evolution}}</p>
            </td>
            <td>
                <p class="col-xs-12">{{$comment->comment_text}}({{User::find($comment->user_id)->name}})</p>
            </td>
            </tr>
        </tbody>
     @endforeach
     @endif
 </table>   
                 
                 



 @endsection
        
                    
                    




