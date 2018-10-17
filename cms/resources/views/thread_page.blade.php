
@extends('layouts.app')

@section('content')
 <?php
use App\Book;
use App\Category;
use App\User;
use App\Key;
?>






<div style="margin-top:60px;" class="well">
      <div class="row">
        <p class="col-xs-2" hidden>{{ $thread->id }}</p>
        <h2 class="col-xs-10"><b>{{ $thread->thread_sub }}</b></h2>
      </div>
      <div class="row">
        <div class="col-xs-2">
            <span navbar-brand><img class="avater img-circle" src="{{$thread_user_name->avater}}"></img></span>
            <p>{{ $thread_user_name->name}}</p>
        </div>
         <div style="margin-bottom:5px;">
             <a href="{{url('category_page/'.$thread->category_id)}}"><p>{{ Category::find($thread->category_id)->category_name}}</p></a>
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
        		<h4 style="text-decoration:underline; text-decoration-color:#FFFF00;">オススメする本を選び、コメントを入力してください</h4>
        		
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
                        	  <div class="item active" style="margin-bottom:20px;">
                        	     
                                        <ul style="list-style:none; display:inline-block;">
                                            <li class="thread_page carousel_book_info col-sm-4"><img src="{{$user_comment->user_c->BookImage}}"></img></li>
                                            <li class="thread_page carousel_book_info col-sm-8">
                                                 <p class="itemid" hidden>{{$user_comment->id}}</p>
                                                 <h3 class="itemtitle">{{$user_comment->user_c->BookTitle}}</h3>
                                                 <p>{{$user_comment->user_c->BookAuthor}}</p>
                                                 <p>{{$user_comment->comment_text}}</p>
                                            </li>
                                        </ul>  
                                    <form id="aaa" action="{{url('thread_2')}}" method="post" class="horizontal">
                        	           {{csrf_field()}}
                        	          <input name='id' type="hidden" value="{{$user_comment->id}}">
                      	              <input type="hidden" name="thread_id" value="{{ $thread->id }}"></input>
                                        <div style="margin-top:30px;">
                                            <label>スレッドコメント</label>
                                                <input type="textarea" name="thread_comment"   placeholder="スレッドに対するコメント" rows="10" class="form-control">
                                       
                                        <div class="col-xs-offset-5 col-xs-7" style="margin-top:30px;">
                                            <button type="submit" class="btn btn-primary" value="">投稿する</button>
                                        </div> 
                                          </div> 
                                   </form>
                        	  </div>
                            @else
      <!--カルーセルnon actiive   -->                
                                <div class="item">
                                  	<form id="aaa" action="{{url('thread_2')}}" method="post" class="horizontal">
                                  	    {{csrf_field()}}
                                	          <input name='id' type="hidden" value="{{$user_comment->id}}"></input>
                              	              <input type="hidden" name="thread_id" value="{{ $thread->id }}"></input>
                                  		   <ul style="list-style:none; display: inline-block;">                                                                                     
                                            <li class="thread_page carousel_book_info col-sm-4"><img src="{{$user_comment->user_c->BookImage}}" alt="送信する"></img></li>
                                            <li class="thread_page carousel_book_info col-sm-8">
                                                 <p class="itemid" hidden>{{$user_comment->id}}</p>
                                                 <h3 class="itemtitle">{{$user_comment->user_c->BookTitle}}</h3>
                                                 <p>{{$user_comment->user_c->BookAuthor}}</p>
                                                 <p>{{$user_comment->comment_text}}</p>
                                            </li>
                                         </ul>    
                                      <div style="margin-top:30px;">
                                      <label>スレッドコメント</label>
                                        <input type="textarea" name="thread_comment"   placeholder="スレッドに対するコメント" rows="10" class="form-control">
                                     
                                            <div class="col-xs-offset-5 col-xs-7"style="margin-top:30px;">
                                                <button type="submit" class="btn btn-primary" value="">投稿する</button>
                                            </div> 
                                      </div> 
                                    </form>
                                  </div>
                           @endif
                        @endforeach
                      @endif
                </div>
                <a class="left carousel-control test" href="#sampleCarousel" role="button" data-slide="prev" >
              		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              		<span class="sr-only">前へ</span>
                </a>
              	<a class="right carousel-control test2" href="#sampleCarousel" role="button" data-slide="next" >
              		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true" ></span>
              		<span class="sr-only">次へ</span>
              	</a>
            </div>
    <!--カルーセル終了   		-->

    <!--モーダルフッター    --> 		  
    <!--		  <div class="modal-footer">-->
    <!--    		<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>-->
    <!--    	  </div>-->
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
                    <div class="form-group">
                <label  class="col-sm-3 control-label form-control-static" for="isbn">ISBNを入力してください:</label>
                <div class="col-sm-9">
                    <input  class="form-control-static col-sm-9" type="text" class="form-control" id="isbn" placeholder="978で始まる13桁の数字を入力（ーハイフンは含まない）">
                    <button id="btn" class="form-control-static">検索</button>
                </div>
                <div id="message"></div>
              </div>

                @if ($errors->any())
                  <div class="errors">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif


             
<form action="{{url('thread')}}" method="post" class="horizontal">
      
{{ csrf_field() }}
    
<div style="background-color:#DDFFFF;">
    <div class="row">    
        <div class="col-sm-3">
            <p>表紙画像:</p>
            <p id="BookThumbnail" class="type"></p>
                <div style="visibility: hidden;">
                    <p class="h4">aaaa:</p>
                    <p id="BookImage" class="type"></p>
                </div>
            <p id="message" class="type"></p>    
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
                <div class="col-sm-6">
                    <p>出版社:</p>
                    <p id="Publisher" class="type"></p>
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


<!--owner_info始まる-->

 <div class="owner_info">
     
   
　<!--<input type="hidden" name="owner" value="{{Auth::user()->name}}" >-->
            <!--@if(Auth::user()->kanri_flag == 1)-->
            <!--    <div class="form-group"> -->
            <!--    <p>ジーズの本の入力</p>-->
            <!--    <div class="center">-->
            <!--            <label>入力：<input type="number" name="gs" /></label>-->
            <!--     </div>-->
            <!--    </div>-->
            <!--@endif-->
    <div class="form-group">  
          <p><b>本の貸出はできますか？</b></p>
          <div class="block-contents">
          <label class="radio-inline"><input type="radio" name="rental_flag" value=0>はい</label>
        　<label class="radio-inline"><input type="radio" name="rental_flag" value=1>いいえ</label>
        　<label class="radio-inline"><input type="radio" name="rental_flag" value=2>同期内のみ可</label>
        　
        
        　</div>
    </div>
    
   <div class="form-group">   
          <p><b>カテゴリーを選択する</b><p>
          
            <?php $i=0 ?>
            @foreach($genres as $genre)
              
                <div  id="accordion-{{$i}}}" class="block-contents" style="margin-top:20px;">
                    @if(count($genres)>0)   
                    <a data-toggle="collapse" data-parent="#accordion-{{$i}}" href="#sample{{$i}}">
                    ・{{$genre->category_genrename}}
                    </a>
                    @endif    
                    
                    <div class="collapsing collapse block-contents_2" id="sample{{$i}}" style="margin-top:20px margin-left:10px;">
                	@foreach($genre->categories as $category)
    	               <label> <input type="checkbox" class="category" onClick="aaa({{$category->id}})" name="category_id" value="{{$category->id}}" id="check" data-name="{{$category->category_name}}">{{$category->category_name}}</input></label>
                    @endforeach
                </div>
            </div>  
            <?php $i = $i + 1 ?>
             @endforeach

   </div>   
    
    <div class="form-group"> 
          <p><b>関連するタグ</b></p>
          <div class="block-contents">
              <p id="tagslist"></p>
              	<div class="modal-body">
                  <div id="ajax_data"></div>
                  <div id ="new_tag"></div>
                   <div style="margin-top:20px; margin-bottom:20px;">
                        <p>タグを追加する</p>
                         <form id="form_id">
                            <select id="tags" form="form_id">
                            <option>カテゴリを選択</option>
                            </select>
                            <input type="text" name="tags" id="new_tag_name" form="form_id"></input>
                            <button id="bbb" form="form_id">タグを追加する</button>
                        </form>    
                        
                        
                      
                    </div>
                 </div>
         
         </div>    
    </div> 

     
    <div class="form-group"> 
          <p><b>オススメしたい人</b></p>
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
    
    <!--スレッドコメント２開始   -->     
      <div class="form-group">
            <label class="col-sm-3 control-label">スレッドコメント</label>
            <input type="textarea" name="thread_comment"  placeholder="コメントを入力" rows="10" class="form-control">
      </div>
    <input type="hidden" name="thread_id" value="{{ $thread->id }}"></input>
    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
    <div class="form-group">
        <div class="center">
            <button type="submit" class="btn btn-info"  style="width: 145px; font-size: 21px;">送信</button>
        </div>
    </div>    
</div> 
 </div>
<!--owner_info終わり-->      

</div>
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
        		<li class="user_name panel-heading-li"><a href="{{url('/user_search_page/'.$thread_comment_list->r->user_id)}}">{{User::find($thread_comment_list->r->user_id)->name}}さん</li>
        		<li class=panel-heading-li>{{$thread_comment_list->updated_at}}</li>	
        		</ul>
        	
        	</div>
        	<div class="panel-body row">
                  <ul style="list-style-type: none;" class="comment_body">
                    <li class="comment_body_list col-sm-4"><a href="{{url('')}}"><a href="{{url('rental/'.$thread_comment_list->r->book_id)}}"><img src="{{Book::find($thread_comment_list->r->book_id)->BookImage}}"></img></li></a>
                    <li class="comment_body_list col-sm-8">
                        </a>
                        <a href="{{url('rental/'.$thread_comment_list->r->book_id)}}"><h4>{{Book::find($thread_comment_list->r->book_id)->BookTitle}}</h4></a>
                        <p>{{Book::find($thread_comment_list->r->book_id)->BookAuthor}}</p>
                       
                            @foreach($thread_comment_list->r->user_c->tags as $tag)
                             <a href="{{url('tag_page/'.$tag->id)}}" class="tag">{{$tag->tags}}</a>
                            @endforeach
                            <div>
                              <p>特徴</p>  
                              <p>&nbsp;&nbsp;&nbsp;{{Key::find($thread_comment_list->r->key)->key}}</p>
                            </div>
                        
                            <div>
                               <p>評価</p>     
                                @if(($thread_comment_list->r->evaluation) == 1)
                                <div class="star-rating-icon">
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star-empty"></span>
                                  <span class="glyphicon glyphicon-star-empty"></span>
                                  <span class="glyphicon glyphicon-star-empty"></span>
                                  <span class="glyphicon glyphicon-star-empty"></span>
                                </div>
                                @endif 
                                @if(($thread_comment_list->r->evaluation) == 2)
                                <div class="star-rating-icon">
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star-empty"></span>
                                  <span class="glyphicon glyphicon-star-empty"></span>
                                  <span class="glyphicon glyphicon-star-empty"></span>
                                </div>
                                @endif 
                                @if(($thread_comment_list->r->evaluation) == 3)
                                <div class="star-rating-icon">
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphiconstar-empty"></span>
                                  <span class="glyphicon glyphicon-star-empty"></span>
                                </div>
                                @endif 
                                @if(($thread_comment_list->r->evaluation) == 4)
                                <div class="star-rating-icon">
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphiconstar-empty"></span>
                                  <span class="glyphicon glyphiconstar-empty"></span>
                                </div>
                                @endif
                                @if(($thread_comment_list->r->evaluation) ==5)
                                <div class="star-rating-icon">
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star"></span>
                                  <span class="glyphicon glyphicon-star-empty"></span>
                                </div>
                                @endif
                            </div>    
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
      
    
  <script>
     
        // $(".item").on("click",function(){
        //   $("#aaa").find("[name='id']").remove();
        //   $("#aaa").find("[name='BookTitle']").remove();
           
        // let id = $(this).find(".itemid").text();
        // let BookTitle =$(this).find(".itemtitle").text();
        // let form = $("<input name='id'>").attr("type","hidden");
        // let form2 =$("<input name='BookTitle'>").attr("type","text");
        
        // form.val(id);
        // form2.val(BookTitle);
        // $("#aaa").prepend(form);
        // $("#aaa").prepend(form2);
        
        
        // });
        
        $("#btn").on("click", function () {
            const isbn = $("#isbn").val();
            const googleUrl = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn;
            const openUrl   = "https://api.openbd.jp/v1/get?isbn=" + isbn;
          
           $.getJSON(openUrl , function (data1) {
                 if (!data1[0]==null) {
                    $.getJSON(googleUrl, function (data) {
                        if (!data.totalItems){
                            $("#isbn").val("");
                            $("#BookTitle").text("");
                            $("#BookAuthor").text("");
                            $("#isbn10").text("");
                            $("#isbn13").text("");
                            $("#PublishedDate").text("");
                            $("#BookThumbnail").text("");
                            $("#BookDiscription").text("");
                            $("#BookImage").text("");
                            $("#message").html(' <p class = "bg-warning" id = "warning" > 該当する書籍がありません。 < /p>');
                            $('#message > p').fadeOut(3000);
                        } else {
                            //googleURLの処理
                           
                            $("#BookTitle").html('<input class="form-control input-lg" name="BookTitle" readonly="readonly" type="text"  value="' + data.items[0].volumeInfo.title +
                            '">');
                            $("#isbn10").html('<input class="form-control input-sm" name ="isbn10" readonly="readonly" type="number" value="' + data.items[0].volumeInfo.industryIdentifiers[
                                0].identifier + '">');
                            $("#isbn13").html('<input class="form-control input-sm" name ="isbn13" readonly="readonly" type="number" value="' + data.items[0].volumeInfo.industryIdentifiers[
                                1].identifier + '">');
                            $("#BookAuthor").html('<input class="form-control input-sm" name="BookAuthor" readonly="readonly" type="text" value="' + data.items[0].volumeInfo.authors +
                                '">');
                            $("#PublishedDate").html('<input class="form-control input-sm" name="PublishedDate" readonly="readonly" type="text" value="' + data.items[0].volumeInfo
                                .publishedDate + '">');
                            $("#BookDiscription").html('<input class="form-control input-lg" name="BookDiscription"  readonly="readonly" type="text" value="' + data.items[0].volumeInfo
                                .description + '">');
                            $("#Publisher").html('<input class="form-control input-lg" name="Publisher"  readonly="readonly" type="text" value="' + data.items[0].volumeInfo
                                .publisher + '">');
                                 
                            try {
                                     $("#BookThumbnail").html(' <img src=\"' + data.items[0].volumeInfo.imageLinks.smallThumbnail +
                                '\ " />'); 
                            } catch(e) {
            
                                $("#BookThumbnail").html('<img src="http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg" width="100",heigt="200">');
                          
                            }
                            
                             try {
                                $("#BookImage").html(' <input name="BookImage" type="hidden" value="' + data.items[0].volumeInfo.imageLinks.smallThumbnail +
                                '">');
                            } catch(e) {
            
                                $("#BookImage").html('<input name="BookImage" type="hidden" value="http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg">');
                          
                            }
                        }
                    })
                } else {
    
                  
                        $("#BookTitle").html('<input class="form-control input-lg" name="BookTitle" readonly="readonly" type="text"  value="' + data1[0].summary.title +
                            '">');
                        $("#isbn10").html('<input class="form-control input-sm" name ="isbn10" readonly="readonly" type="number" value="' + data1[0].summary.isbn + '">');
                        $("#isbn13").html('<input class="form-control input-sm" name ="isbn13" readonly="readonly" type="number" value="' + data1[0].summary.isbn + '">');
                        $("#BookAuthor").html('<input class="form-control input-sm" name="BookAuthor" readonly="readonly" type="text" value="' + data1[0].summary.author +'">');
                        $("#PublishedDate").html('<input class="form-control input-sm" name="PublishedDate" readonly="readonly" type="text" value="' +data1[0].summary.pubdate  + '">');
                        $("#Publisher").html('<input class="form-control input-sm" name="Publisher" readonly="readonly" type="text" value="' +data1[0].summary.publisher  + '">');
                        
                        let description = "";
                        if(data1[0].onix.CollateralDetail.TextContent){
                            description = data1[0].onix.CollateralDetail.TextContent[0].Text;
                        }else{
                            description = "書誌情報なし";
                        }
                        $("#BookDiscription").html('<input class="form-control input-lg" name="BookDiscription"  readonly="readonly" type="text" value="' + description + '">');
                     
                     
                       
                       
                                
                        try {
                             $("#BookThumbnail").html(' <img src=\"' + data1[0].summary.cover +'\ " />');    
                        } catch(e) {
                            $("#BookThumbnail").html('<img src="http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg" width="100",heigt="200">');
                        }
                    
                    
                        try {
                            $("#BookImage").html(' <input name="BookImage" type="hidden" value="' + data1[0].summary.cover + '">');
                       
                        } catch(e) {
        
                            $("#BookImage").html('<input name="BookImage" type="hidden" value="http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg">');
                      
                        }
                      
                        
                        
                        
                }
                
                
            });
        });
// tag ajax
function aaa(){
    let category_ids = $("input[type=checkbox].category:checked").toArray().map((i) => {
        return Number(i.value);
    });
   
    
    var request = $.ajax({
        type: 'GET',
        url: "{{url('ajax')}}" + "/" + category_ids.join(","),
        cache: false,
        dataType: 'json',
        timeout: 1000
    });
    /* 成功時 */
        request.done(function(data){
            $("#ajax_data").empty();
            for(var i = 0; data.length > i; i++){
               
                $("#ajax_data").append('<label><input type="checkbox" value="'+data[i].id+'" class="check" name="tag_id[]" ></input>'+data[i].tags+"</label>");
　
            }
            // クリック時に選択済みのタグIdをglobal変数に保存する
            $("input[name='tag_id[]']").on('click', (i) => {
                selected_tag_ids = $("input[name='tag_id[]']:checked").toArray().map((i) => {
                    return i.value;
                });
            });
            // ajax_dataにinputをappendした後、選択済みのタグにチェックを入れる
            $("input[name='tag_id[]']").each((idx, i) => {
                var isSelected = selected_tag_ids.indexOf(Number(i.value)) >= 0;
                if (isSelected) {
                    i.checked = true;
                }
            })

        });
    /* 失敗時 */
        request.fail(function(e){
        });
};
$(".category").on("click",function(){
   
    const selectedvalue =[];
    const selectedname =[];
   $(".category:checked").each(function(){
       
       selectedvalue.push($(this).val());
       selectedname.push($(this).data("name"));
   });
   
    let selectBox ='';
    
    for(let i=0;i<selectedvalue.length;i++){
        if(i == 0) {
            selectBox += '<option value="'+selectedvalue[i]+'" name="option" checked="checked" >'+selectedname[i]+'</option>';
        }
        selectBox += '<option value="'+selectedvalue[i]+'" name="option" >'+selectedname[i]+'</option>';
    };
     
    $("#tags").empty();
    $("#tags").append(selectBox);
  
    
});
$("#bbb").on("click",function(){
const new_tag_category_id =$('#tags').val();
const new_tag_name = $('#new_tag_name').val();
console.log(new_tag_category_id);
    $("#new_tag").append('<label><input type="checkbox" checked="checked" class="check" value="'+ new_tag_name +'" name="tag_add[]"></input>'+new_tag_name+"</label>");
    $("#new_tag").append('<input type="hidden"  value="'+new_tag_category_id+'" class="check" name="tag_category_id[]" ></input>');
 });
 
 
 
 //配列のインデックス
//  var BCIndex = 0
//  var titleArr = Array.from(document.getElementsByClassName('itemtitle')).map(el=>el.textContent);
//  var idArr = Array.from(document.getElementsByClassName('itemid')).map(el=>el.textContent);
//  var slideClickFlag = true;
//  let form = $("<input name='id'>").attr("type","hidden");
//  let form2 =$("<input name='BookTitle'>").attr("type","text");
//  form.val(idArr[0]);
//  form2.val(titleArr[0]);

// $("#aaa").prepend(form);
// $("#aaa").prepend(form2);
 
// function ccc(){
//     // console.log(slideClickFlag)
//     //       if (slideClickFlag){
//     //           slideClickFlag=false;
//     //       }
    
//     $('test2').hide()
//           $("#aaa").find("[name='id']").remove();
//           $("#aaa").find("[name='BookTitle']").remove();
           
//         let id = $(".item.sitei").find(".itemid").text();
//         let BookTitle =$(".item.sitei").find(".itemtitle").text();
//         let form = $("<input name='id'>").attr("type","hidden");
//         let form2 =$("<input name='BookTitle'>").attr("type","text");
        
        
        
//         if (BCIndex == titleArr.length - 1) {
//             BCIndex = 0
//         }else{
//             BCIndex += 1;
//         }
//         console.log(BCIndex);
//         form.val(idArr[BCIndex]);
//         form2.val(titleArr[BCIndex]);

//         $("#aaa").prepend(form);
//         $("#aaa").prepend(form2);

//          setTimeout(function(){$('test2').show() }, 1000);
// };

// function ddd(){
//     console.log(slideClickFlag)
//     if (slideClickFlag){
//         slideClickFlag=false;
        
//     $("#aaa").find("[name='id']").remove();
//           $("#aaa").find("[name='BookTitle']").remove();
           
//         let id = $(".item.sitei").find(".itemid").text();
//         let BookTitle =$(".item.sitei").find(".itemtitle").text();
//         let form = $("<input name='id'>").attr("type","hidden");
//         let form2 =$("<input name='BookTitle'>").attr("type","text");
        
        
        
//         if (BCIndex == 0) {
//             BCIndex = titleArr.length - 1
//         }else{
//             BCIndex -= 1;
//         }
//         form.val(idArr[BCIndex]);
//         form2.val(titleArr[BCIndex]);

//         $("#aaa").prepend(form);
//         $("#aaa").prepend(form2);
        
   
//          setTimeout(function(){slideClickFlag  = true; }, 1000);
// }
// };

</script>

    



    @endsection
    @section('footer')
