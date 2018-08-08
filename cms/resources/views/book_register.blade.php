@extends('layouts.app')

@section('content')

<h2>Book Register</h2>


 <p class="page-header">本を登録する</p>
  <div class="form-group">
    <label  class="col-sm-3 control-label" for="isbn">ISBNを入力してください:</label>
    <div class="col-sm-9">
        <input  class="form-control-static" type="text" class="form-control" id="isbn">
        <button id="btn">送信</button>
    </div>
  </div>

             
<form action="{{url('book')}}" method="post" class="horizontal">
      
{{ csrf_field() }}
    
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
         
      <div class="form-group">  
          <label class="col-sm-3 control-label">おすすめポイント</label>
          <div class="col-sm-9">
          <textarea  rows="5" class="form-control" name="comment_text" placeholder="例）++の勉強をしたい人におすすめ" autofocu></textarea>
          </div>
    </div>      
    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9 text-right">
        <button type="submit" >送信</button>
        </div>
    </div>
       

            
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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
