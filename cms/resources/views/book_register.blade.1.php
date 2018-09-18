@extends('layouts.app')

@section('content')




<div class="owner_info">
<h2>Book Register</h2>    
 <p class="page-header">本を登録する</p>
  <div class="form-group row">
    <label  class="col-sm-3 control-label form-control-static" for="isbn">ISBNを入力してください:</label>
    <div class="col-sm-9">
        <input  class="form-control-static col-sm-9" type="text" class="form-control" id="isbn" placeholder="978で始まる13桁の数字を入力（ーハイフンは含まない）">
        <button id="btn" class="form-control-static">検索</button>
    </div>
  </div>

             
<form action="{{url('book')}}" method="post" class="horizontal">
      
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

 
   
     <input type="hidden" name="owner" value="{{Auth::user()->name}}" >

    
    @if(Auth::user()->kanri_flag == 1)
    <h1>ジーズ</h1>
        <label><input type="radio" name="gs" value=0 />普通</label>
        <label><input type="radio" name="gs" value=1 />ジーズ</label>
    @endif
    
    <div class="form-group">  
          <p><b>本の貸出はできますか？</b></p>
          <div class="center">
          <label class="radio-inline"><input type="radio" name="rental_flag" value=0>はい</label>
        　<label class="radio-inline"><input type="radio" name="rental_flag" value=1>いいえ</label>
        　</div>
    </div>
    
   
  
  <div class="form-group">   
          <p><b>カテゴリー</b><p>
          @if(count($genreCategories)>0)
          <?php $i=0 ?>
                @foreach($genreCategories as $genre => $categories)
                
                <div  id="accordion" class="center">
                        @if(count($categories)>0)   
                        <a data-toggle="collapse" data-parent="#accordion" href="#sample{{$i}}">
                        {{$genre}}
                        </a>
                        @endif    
                        
                        <div class="collapsing collapse" id="sample{{$i}}">
                    	@foreach($categories as $category)
    	                 <input type="checkbox" name="category_id" value="{{$category["id"]}}">{{$category["category_name"]}}</input>
                        @endforeach
                        </div>
                </div>      
                <?php $i = $i + 1 ?>
                @endforeach
            @endif
   </div>   

  
     
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
    
    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9 text-right">
            <button type="submit" >送信</button>
        </div>
    </div>    

 
<!--owner_info終わり-->      



</form>    
</div>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>





        $("#btn").on("click", function () {
            const isbn = $("#isbn").val();
            const googleUrl = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn;
            const openUrl   = "https://api.openbd.jp/v1/get?isbn=" + isbn;
          
           $.getJSON(openUrl , function (data1[0]) {
        
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
                            $("#message").html(' <p class = "bg-warning"id = "warning" > 該当する書籍がありません。 < /p>');
                            $('#message > p').fadeOut(3000);
                        } else {
                            //googleURLの処理
                            console.log(data1[0]);
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
    
                    //openURLの処理
                    console.log(data1[0]);
                        $("#BookTitle").html('<input class="form-control input-lg" name="BookTitle" readonly="readonly" type="text"  value="' + data1[0].summary.title +
                            '">');
                        $("#isbn10").html('<input class="form-control input-sm" name ="isbn10" readonly="readonly" type="number" value="' + data1[0].summary.isbn + '">');
                        $("#isbn13").html('<input class="form-control input-sm" name ="isbn13" readonly="readonly" type="number" value="' + data1[0].summary.isbn + '">');
                        $("#BookAuthor").html('<input class="form-control input-sm" name="BookAuthor" readonly="readonly" type="text" value="' + data1[0].summary.author +'">');
                        $("#PublishedDate").html('<input class="form-control input-sm" name="PublishedDate" readonly="readonly" type="text" value="' +data1[0].summary.pubdatey  + '">');
                        $("#BookDiscription").html('<input class="form-control input-lg" name="BookDiscription"  readonly="readonly" type="text" value="' + data1[0].onix.CollateralDetail.TextContent[0].Text + '">');
                        $("#BookThumbnail").html(' <img src=\"' + data1[0].summary.cover +'\ " />');
                        $("#BookImage").html(' <input name="BookImage" type="hidden" value="' + data1[0].summary.cover + '">');
                    
                            console.log(data1[0].summary.cover);
                                console.log("test");
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
                
                
            });
        });






    </script>


@endsection
@section('footer')