@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Comment;

?>
<!--ジャンボトロン-->
    <div class="container">

        <div class="jumbotron text-center">
           <span navbar-brand><img class="avater img-circle" src="{{$user->avater}}"></img></span>
            <h3>{{$user->name}}さんのBookList</h3>
        </div>
    
    </div>
<!--ジャンボトロン終わり-->



<!--所有している本-->
<h4 class="col-xs-12">{{$user->name}}さんがコメントをいれた書籍</h4>　
        @if(isset($user_books)>0)
        <?php $i=0 ?>
            @foreach($user_books as $user_book)

            
              @if($i == 0)
                <div class="col-sm-12 border_bottom">
              @endif
                <div class="col-sm-2">
                <ul class="sample">
                 <li>
                    <img src="{{$user_book->BookImage}}" class="img-responsive" width="128" height="180"></img>
                    <a href="">{{ $user_book->BookTitle }}</a>
                    <p>{{ $user_book->BookAuthor }}</p>
       
                   <div class="col-sm-6 text-center">
                       
                 <li>              
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#sampleModal1" onClick="aaa({{$user_book->id}},{{$user->id}})">
            	  コメントを見る
                </button>

        <!-- コメント入力モーダル -->
        <div class="modal fade" id="sampleModal1" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
            <!--モーダルヘッダー2    -->
                	<div class="modal-header">
                		<button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                	    <h3 class="modal-title">{{ $user_book->BookTitle }}</h3>
                	</div>
        
            <!--モーダルボディー2    -->
                	<div class="modal-body" style="padding:43px;">
                		<h4 style="text-decoration:underline; text-decoration-color:#FFFF00;">おすすめコメント</h4>
                       <div id="ajax_data"></div>
                         
                       
                    </div>
              <!--モーダルフッター    -->
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
              </div>
        
            </div>
        </div>
        </div>

                 </li>
                 <li>
                     <a href="{{url('/rental/'.$user_book->id)}}">レンタルする</a>
                 </li>     
                 </ul>
                </div>
                  <?php $i=$i+1 ?>
                  @if($i == 6 || $loop->last)
                  <?php $i=0 ?>
                 </div>
                @endif
                
            @endforeach
        @endif
<!--所有している本　終わり-->


<script>




function aaa(book_id, user_id){
    
        var request = $.ajax({
            type: 'GET',
            url: "{{url('ajax_comment')}}" + "/" + book_id + "/" + user_id,
            cache: false,
            
            
            dataType: 'json',
            timeout: 1000
        });

    /* 成功時 */
        request.done(function(data){
            console.log("data = %O", data);
            $("#ajax_data").empty();
            for(var i = 0; data.length > i; i++){
                console.log(data[i]);
               
                $("#ajax_data").append('<p>'+data[i].comment_text+"<p>");

            }
            $('#sampleModal').modal('show');
        });

    /* 失敗時 */
        request.fail(function(e){
            console.error(e);
        });
};

</script>





 @endsection
  