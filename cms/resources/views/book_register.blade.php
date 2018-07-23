@extends('layouts.app')

@section('content')

<h2>Book Mark</h2>
    <p>本を登録する</p>


                <div class="form-group">
                    <label for="isbn">ISBNを入力してください:</label>
                    <input type="text" class="form-control" id="isbn">
                    <button id="btn">送信</button>
                </div>

             
                <form action="{{url('book')}}" method="post">
                    {{ csrf_field() }}
                    <div>
                        <p class="h4">書籍タイトル:</p>
                        <p id="BookTitle" class="type"></p>
                    </div>
                    
                    <div>
                        <p class="h4">著者:</p>
                        <p id="BookAuthor" class="type"></p>
                    </div>
    
    　              <!--変更-->
                    <div>
                        <p class="h4">ISBN10:</p>
                        <p id="isbn10" class="type"></p>
                    </div>
    
                    <div>
                        <p class="h4">ISBN13:</p>
                        <p id="isbn13" class="type"></p>
                    </div>
                    <!--変更-->
    
                    <div>
                        <p class="h4">出版日:</p>
                        <p id="PublishedDate" class="type"></p>
                    </div>
    
                    <div>
                        <p class="h4">表紙画像:</p>
                        <p id="BookThumbnail" class="type"></p>
                    </div>
                    <div style="visibility: hidden;">
                        <p class="h4">aaaa:</p>
                        <p id="BookImage" class="type"></p>
                    </div>
                    <div>
                        <p class="h4">書籍概要:</p>
                        <p id="BookDiscription" class="type"></p>
                    </div>
                    <div>
                        <p>所有者の名前を記入してください。</p>
                        <input type="text" name="owner" value="">

                        
                        
                        <p>本を貸し出せますか？</p>
                          <input type="radio" name="rental_flag" value=0>はい
                        　<input type="radio" name="rental_flag" value=1>いいえ
                  
                        <p>カテゴリー一覧</p>
                            @foreach($categories as $category)
                            <input type="radio" name="category_id" value="{{$category->id}}">{{$category->category_name}}
                            @endforeach
                        </div>

                    
                            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                            <input type="hidden" name="life_flag" value="0">
                    
                    
                    
                    
                    
                      <button type="submit">送信</button>

            </form>        
                    

            
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
                    $("#BookTitle").html('<input name="BookTitle" readonly="readonly" type="text" value="' + data.items[0].volumeInfo.title +
                        '">');
                    $("#isbn10").html('<input name ="isbn10" readonly="readonly" type="number" value="' + data.items[0].volumeInfo.industryIdentifiers[
                        1].identifier + '">');
                    $("#isbn13").html('<input name ="isbn13" readonly="readonly" type="number" value="' + data.items[0].volumeInfo.industryIdentifiers[
                        0].identifier + '">');
                    $("#BookAuthor").html('<input name="BookAuthor" readonly="readonly" type="text" value="' + data.items[0].volumeInfo.authors +
                        '">');
                    $("#PublishedDate").html('<input name="PublishedDate" readonly="readonly" type="text" value="' + data.items[0].volumeInfo
                        .publishedDate + '">');
                    $("#BookDiscription").html('<input name="BookDiscription" readonly="readonly" type="text" value="' + data.items[0].volumeInfo
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
