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
    　<div class="row">
          <div class="col-sm-offset-3 col-sm-9 text-right">
            <form action="{{url('thread/'.$thread->id)}}" method="post">
            {{ csrf_field() }}
            <button type="submit">更新</button>
            </form>
          </div>
      </div>
</div>

<div class="row">
    
<!-- 登録済みの本からオススメする -->    
<div class="col-sm-6 text-center">       
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#sampleModal">
	すでに登録済みの本からオススメする
    </button>
    

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
          <ol class="carousel-indicators">
        		<li class="active" data-target="#sampleCarousel" data-slide-to="0"></li>
        		<li data-target="#sampleCarousel" data-slide-to="1"></li>
        		<li data-target="#sampleCarousel" data-slide-to="2"></li>
          </ol>
            
            <div class="carousel-inner" role="listbox">
              <?php $i=0;?>
                @if(isset($user_comments)>0)
                    @foreach($user_comments as $user_comment)
                	    <?php $i++;?>
                        @if($i==1)
  <!--カルーセルactiive   -->                        
                    		  <div class="item active">
                            <ul>
                                <li><img src="{{$user_comment->user_c->BookImage}}" alt="送信する"></img></li>
                                <li>
                                  <div> 
                                    <p class="itemid" hidden>{{$user_comment->id}}</p>
                                    <p>{{$user_comment->user_c->BookTitle}}</p>
                                    <p>{{$user_comment->user_c->BookAuthor}}</p>
                                    <p>{{$user_comment->comment_text}}</p>
                                  </div> 
                                </li>
                            </ul>    
                    		  </div>
                        @else
  <!--カルーセルnon actiive   -->                         
                      		<div class="item">
                      		    <ul>                                                                                       
                                <li><img src="{{$user_comment->user_c->BookImage}}" alt="送信する"></img></li>
                                <li>
                                  <div>  
                                    <p class="itemid" hidden>{{$user_comment->id}}</p>
                                    <p>{{$user_comment->user_c->BookTitle}}</p>
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
    				    <p>おすすめの本</p>
    				    <h>
 				    
      </div>

	　　<form id="aaa">
  　　    {{csrf_field()}}
  	       <input type="textarea" name="thread_comment" valie="" placeholder="コメントを入力" rows="10" class="form-control">
           <button type="button" class="btn btn-primary"/ value="">投稿する</button>
	    </form>
		  
<!--モーダルフッター    --> 		  
		  <div class="modal-footer">
    				<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
    	</div>

    		
    	</div>
    </div>
</div>



 
    
  
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
   <script>
     console.log("test")
        $(".item").on("click",function(){
        let id = $(this).find(".itemid").text();
        let form = $("<input name='id'>").attr("type","text");
        form.val(id);
        $("#aaa").prepend(form);
        });
        
        
      

 
            
    </script>




    @endsection