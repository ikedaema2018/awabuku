@extends('layouts.app')

@section('content')
 <?php

use App\Book;
use App\User;



?>






<div style="margin-top:60px;" class="well">
      <div class="row">
        <p class="col-xs-2" hidden>{{ $thread->id }}</p>
        <h2 class="col-xs-10"><b>{{ $thread->thread_sub }}</b></h2>
      </div>
      <div class="row">
        <div class="col-xs-2">
            <span navbar-brand><img class="avater img-circle" src="{{$thread_user_name->avater}}"></img></span>
            <p>{{ $thread_user_name->name}}さんの質問</p>

        </div>
        <div class="col-xs-10">                    
             <p>{{ $thread->thread_body }}</p>
        </div> 
    　</div>    
    　
</div>

<div class="row">
    
    <!-- 登録済みの本からオススメする -->    
    <div class="col-sm-6 text-center">       
        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#sampleModal">
    	すでに登録済みの本からオススメする
        </button>
    </div>      
    
    <!-- モーダル・ダイアログ -->
    <div class="modal fade" id="sampleModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
    <!--モーダルヘッッダー    -->
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal"><span>×</span></button>
        	  <h3 class="modal-title">{{ $thread->thread_sub }}</h3>
        	</div>
        			    
    <!--モーダルボディー    -->    			    
        	 <div class="modal-body">
    
        		<h4 style="text-decoration:underline; text-decoration-color:#FFFF00;">オススメする本をタップしてください</h4>
        		
    <!--カルーセル開始   -->     		
        		<div id="sampleCarousel" class="carousel slide" data-interval="false" data-ride="carousel">
              <ul class="carousel-indicators" style="list-style:none;">
            		<li class="active" data-target="#sampleCarousel" data-slide-to="0"></li>
            		<li data-target="#sampleCarousel" data-slide-to="1"></li>
            		<li data-target="#sampleCarousel" data-slide-to="2"></li>
              </ul>
                
                <div class="carousel-inner" role="listbox">
                  <?php $i=0;?>
                    @if(isset($user_comments)>0)
                        @foreach($user_comments as $user_comment)
                    	    <?php $i++;?>
                            @if($i==1)
      <!--カルーセルactiive   -->                        
                        	  <div class="item active">
                                <ul style="list-style:none;" class="row">
                                    <li class="thread_page carousel_book_info col-sm-4"><img src="{{$user_comment->user_c->BookImage}}"></img></li>
                                    <li class="thread_page carousel_book_info col-sm-8">
                                      <div> 
                                        <p class="itemid" hidden>{{$user_comment->id}}</p>
                                        <h3 class="itemtitle">{{$user_comment->user_c->BookTitle}}</h3>
                                        <p>{{$user_comment->user_c->BookAuthor}}</p>
                                        <p>{{$user_comment->comment_text}}</p>
                                      </div> 
                                    </li>
                                </ul>    
                        	  </div>
                            @else
      <!--カルーセルnon actiive   -->                         
                          		<div class="item">
                          		 <ul style="list-style:none;">                                                                                       
                                    <li class="thread_page carousel_book_info col-sm-4"><img src="{{$user_comment->user_c->BookImage}}" alt="送信する"></img></li>
                                    <li class="thread_page carousel_book_info col-sm-8">
                                      <div>  
                                        <p class="itemid" hidden>{{$user_comment->id}}</p>
                                        <h3 class="itemtitle">{{$user_comment->user_c->BookTitle}}</h3>
                                        <p>{{$user_comment->user_c->BookAuthor}}</p>
                                        <p>{{$user_comment->comment_text}}</p>
                                      </div> 
                                    </li>
                                 </ul>      
                          		</div>                          
                           @endif
                        @endforeach
                      @endif
                </div>
                
                <a class="left carousel-control" href="#sampleCarousel" role="button" data-slide="prev">
              		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              		<span class="sr-only">前へ</span>
                </a>
              	<a class="right carousel-control" href="#sampleCarousel" role="button" data-slide="next">
              		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              		<span class="sr-only">次へ</span>
              	</a>
            </div>
    <!--カルーセル終了   				-->
    
    
    <!--コメント入力  				-->
    
        	<div class="row">
        	    <p>あなたがチェックした本</p>
            </div>
    
    	　　<form id="aaa" action="{{url('thread_2')}}" method="post" class="horizontal">
      　　    {{csrf_field()}}
    
      	 
      	       <input type="hidden" name="thread_id"  value="{{$thread->id}}" >
      	       <input type="textarea" name="thread_comment"  placeholder="コメントを入力" rows="10" class="form-control">
               <button type="submit" class="btn btn-primary"/ value="">投稿する</button>
    	    </form>
    		  
    <!--モーダルフッター    --> 		  
    		  <div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
        	  </div>
        	</div>
        </div>
    </div>
</div>
    
    
    
    
    
<!-- 本を新規登録してからオススメする -->    
<div class="col-sm-6 text-center">       
        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#sampleModal2">
    	  本を新規に登録してからオススメする
        </button>
</div>
    
    
    <!-- モーダル・ダイアログ2 -->
    <div class="modal fade" id="sampleModal2" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
    <!--モーダルヘッダー2    -->
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal"><span>×</span></button>
        	    <h3 class="modal-title">{{ $thread->thread_sub }}</h3>
        	</div>
        			    
    <!--モーダルボディー2    -->    			    
        	<div class="modal-body">
        		<h4 style="text-decoration:underline; text-decoration-color:#FFFF00;">本を新規登録してください</h4>
        <!--ISBN検索   --> 
                <div class="form-group">
                  <label  class="col-sm-3 control-label" for="isbn">ISBNを入力してください:</label>
                  <div class="col-sm-9">
                      <input  class="form-control-static" type="text" class="form-control" id="isbn">
                      <button id="btn">送信</button>
                  </div>
                </div>
  
         <!--form開始   -->     
              <form action="{{url('thread')}}" method="post" class="horizontal">
                {{ csrf_field() }}
            <!--書誌情報開始   -->     
                  <div style="background-color:#EDF7FF;">
                      <div class="row">    
                          <div class="col-sm-3">
                              <p>表紙画像:</p>
                              <p id="BookThumbnail" class="type"></p>
                                  <div style="visibility: hidden;">
                                      <p class="h4">aaaa:</p>
                                      <p id="BookImage" class="type"></p>
                                  </div>
                          </div>
                          <div class="col-sm-9">
                              <div>
                                  <p>書籍タイトル:</p>
                                  <p id="BookTitle" class="type"></p>
                              </div>
                                      
                              <div>
                                  <div class="col-sm-6">
                                      <p>著者:</p>
                                      <p id="BookAuthor" class="type"></p>
                                  </div>
                                  <div class="col-sm-6">
                                      <p>出版日:</p>
                                      <p id="PublishedDate" class="type"></p>
                                  </div>
                              </div>
                              <div>
                                  <div class="col-sm-6">
                                    <p>ISBN:</p>
                                    <p id="isbn10" class="type">
                                  </div>   
                                  <div class="col-sm-6">
                                     <h4>&nbsp;</h4> 
                                    <p id="isbn13" class="type"</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="row">                 
                          <div class="col-sm-12">
                              <p>書籍概要:</p>
                              <p id="BookDiscription" class="type"></p>
                          </div>
                      </div>
                  </div>                
                        
            <!--オーナー情報開始   -->                   
              <div class="form-group" style="margin-top:60px;">  
                    <label class="col-sm-3 control-label">所有者の名前を記入してください。</label>
                    <div class="col-sm-9">
                    <input type="text" name="owner" value="">
                    </div>
              </div> 
            　<div class="form-group">  
            　　　　<p class="col-sm-3 control-label"><b>本の貸出はできますか？</b></p>
            　　　　<label class="radio-inline"><input type="radio" name="rental_flag" value=0>はい</label>
            　　　　<label class="radio-inline"><input type="radio" name="rental_flag" value=1>いいえ</label>
            　</div>
              
              <div class="form-group">  
                    <p class="col-sm-3 control-label"><b>カテゴリ</b></p>
                      @foreach($categories as $category)
                    <label class="radio-inline"><input type="checkbox" name="category_id[]" value="{{$category->id}}">{{$category->category_name}}</label>
                      @endforeach
       　　　　</div>    
       　　
       　　　　<div class="form-group"> 
            　　<p class="col-sm-3 control-label"><b>こんなひとにおすすめ</b></p>
                     <label class="radio-inline"><input type ="radio" name="person" value="1">初心者</label>
                     <label class="radio-inline"><input type ="radio" name="person" value="2">中級者</label>
                     <label class="radio-inline"><input type ="radio" name="person" value="3">上級者</label>
                     <label class="radio-inline"><input type ="radio" name="person" value="4">その他</label>
            　</div>        
            
            　<div class="form-group"> 
                <p class="col-sm-3 control-label"><b>評価</b></p>       
                      <label class="radio-inline"><input type ="radio" name="evolution" value="1">❤️</label>
                      <input type ="radio" name="evolution" value="2">❤️❤️</label>
                      <label class="radio-inline"><input type ="radio" name="evolution" value="3">❤️❤️❤️</label>
                      <label class="radio-inline"><input type ="radio" name="evolution" value="4">その他</label>
        　　　</div>      
             <!--bookcomment開始   -->           
        　　　<div class="form-group">  
                    　<label class="col-sm-3 control-label">おすすめポイント</label>
                    　<div class="col-sm-9">
                    　　<textarea  rows="5" class="form-control" name="comment_text" placeholder="例）++の勉強をしたい人におすすめ" autofocu></textarea>
                    　</div>
      　　　　　</div>      
             <!--スレッドコメント２開始   -->     
              <div class="form-group">
                    <label class="col-sm-3 control-label">スレッドコメント</label>
                    <input type="textarea" name="thread_comment"  placeholder="コメントを入力" rows="10" class="form-control">
              </div>
              
      <div class="form-group">
      <div class="col-sm-offset-3 col-sm-9 text-right">
           <p>{{$thread->id}}</p>
          <input type="hidden" name="thread_id" value="{{ $thread->id }}"></input>
          <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
          <button type="submit" class="btn btn-primary"/ value="">投稿する</button>
         
      </div>
     
     
      </form>		  
      <!--モーダルフッター    --> 		  
      <div class="modal-footer">
      	
        <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
      </div>
      
          		
    </div>
　</div>
</div>
</div>
</div>  
</div>  

<!--コメントリストを表示する　　-->
<div class="col-sm-2-offset col-sm-8 col-sm-offset-2">
@if(isset($thread_comment_lists)>0)
      @foreach($thread_comment_lists as $thread_comment_list)   
        <div class="panel panel-info coment_list">
        	<div class="panel-heading">
        		<ul style="list-style:none;" >
                <li class="user_name panel-heading-li"><img class="thread_avater img-circle" src="{{User::find($thread_comment_list->r->user_id)->avater}}"></img></li>
        		<li class="user_name panel-heading-li">{{User::find($thread_comment_list->r->user_id)->name}}さん</li>
        		<li class=panel-heading-li>{{$thread_comment_list->updated_at}}</li>	
        		</ul>
        	
        	</div>
        	<div class="panel-body hoge row">
                  <ul style="list-style-type: none;" class="comment_body">
                    <li class="comment_body_list col-xs-4"><img src="{{Book::find($thread_comment_list->r->book_id)->BookImage}}"></img></li>
                    <li class="comment_body_list col-xs-8">
                        <a href="{{url('rental/'.$thread_comment_list->r->book_id)}}"></a>
                        <h3>{{Book::find($thread_comment_list->r->book_id)->BookTitle}}</h3>
                        <p>{{Book::find($thread_comment_list->r->book_id)->BookAuthor}}</p>
                        <p>{{Book::find($thread_comment_list->r->book_id)->id}}</p>
                        <p>おすすめコメント</p>
                        <p>{{$thread_comment_list->r->comment_text}}</p>
                      
                    </li>
                </ul>  
                <p>コメント</p>
            	<p class="well">{{$thread_comment_list->thread_comment}}</p>    
        	</div>
         
        </div>        	  
      @endforeach
@endif 
</div>
<!--コメントを表示する　終了-->
      
    
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script>
     
        $(".item").on("click",function(){
           $("#aaa").find("[name='id']").remove();
           $("#aaa").find("[name='BookTitle']").remove();
           
        let id = $(this).find(".itemid").text();
        let BookTitle =$(this).find(".itemtitle").text();
        let form = $("<input name='id'>").attr("type","hidden");
        let form2 =$("<input name='BookTitle'>").attr("type","text");
        
        form.val(id);
        form2.val(BookTitle);
        $("#aaa").prepend(form);
        $("#aaa").prepend(form2);
        
        console.log($('#aaa').val()); 
        });
        
        
   </script>
   <script>

        $("#btn").on("click", function () {
            const isbn = $("#isbn").val();
            const url = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn;

            $.getJSON(url, function (data) {
                if (!data.totalItems) {
                    $("#isbn").val("");
                    $("#BookTitle").text("");
                    $("#BookAuthor").text("");
                    $("#isbn10").text("");
                    $("#isbn13").text("");
                    $("#PublishedDate").text("");
                    $("#BookThumbnail").text("");
                    $("#BookDiscription").text("");
                    $("#BookImage").text("");

                    $("#message").html(' <p class = "bg-warning"id = "warning" > 該当する書籍がありません。 < /p>');
                    $('#message > p').fadeOut(3000);
                } else {
                    $("#BookTitle").html('<input class="form-control input-lg" name="BookTitle" readonly="readonly" type="text"  value="' + data.items[0].volumeInfo.title +
                        '">');
                    $("#isbn10").html('<input class="form-control input-sm" name ="isbn10" readonly="readonly" type="number" value="' + data.items[0].volumeInfo.industryIdentifiers[
                        1].identifier + '">');
                    $("#isbn13").html('<input class="form-control input-sm" name ="isbn13" readonly="readonly" type="number" value="' + data.items[0].volumeInfo.industryIdentifiers[
                        0].identifier + '">');
                    $("#BookAuthor").html('<input class="form-control input-sm" name="BookAuthor" readonly="readonly" type="text" value="' + data.items[0].volumeInfo.authors +
                        '">');
                    $("#PublishedDate").html('<input class="form-control input-sm" name="PublishedDate" readonly="readonly" type="text" value="' + data.items[0].volumeInfo
                        .publishedDate + '">');
                    $("#BookDiscription").html('<input class="form-control input-lg" name="BookDiscription"  readonly="readonly" type="text" value="' + data.items[0].volumeInfo
                        .description + '">');
                    $("#BookThumbnail").html(' <img src=\"' + data.items[0].volumeInfo.imageLinks.smallThumbnail +
                        '\ " />');
                    $("#BookImage").html(' <input name="BookImage" type="hidden" value="' + data.items[0].volumeInfo.imageLinks.smallThumbnail +
                        '">');
                }
                
                
            });
        });

    </script>



    @endsection
    @section('footer')