@extends('layouts.app')

@section('content')
<?php
use App\Tag;
?>

<h2>Book Register</h2>

<div class="row">
<div class="col-sm-offset-2 col-sm-8 col-sm-offset-2">

    @if(count($errors)> 0)
        <p>入力項目を確認してください</p>
    @endif
    
 <p class="page-header">本を登録する</p>
 
  <div class="form-group">
    <p><b>①背表紙のバーコードを読みとる場合<b></p>
    <div id="container">
        <div  id="canvas_wrapper" style="display:none;" >
		<canvas width="320" height="240" id="picture"></canvas>
		</div>
		<p id="textbit"></p>
		<div class="upload_btn">
			<input id="Take-Picture" type="file" accept="image/*;capture=camera" />
		</div>
	</div>
	
	<!--タイトル検索-->
	<div>
    	<p><b>②タイトルで検索する場合</b></p>    
    	<input type="text" id="book_title"/>
    	<button onClick="send_title()">検索</button>
    	<div id="books_wraper"></div>
	</div>
　　<div>
        <p>③ISBNを直接入力する場合<p style="font-size:10px;">※裏表紙や奥付に記載されている１３桁のユニークコード</p></p>
    <div>
        <input  class="form-control-static col-sm-9" type="text" class="form-control" id="isbn" placeholder="ISBNとは978始まる13桁の数字を入力（ーハイフンは含まない）">
        <button id="btn" class="form-control-static" onclick="send_ISBN()">検索</button>
    </div>
    </div>
    <div id="message"></div>
  </div>

<form action="{{url('book')}}" method="post" class="horizontal">
      
{{ csrf_field() }}
    
<div style="background-color:#DDFFFF;">
    
    <div class="row">
          @if($errors->has('BookTitle'))
          <p style="color:#CC0033">エラー:{{$errors->first('BookTitle')}}</p>
          @endif
        
        <div class="col-sm-3" style=text-align:center;>
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
                   <p>ISBN13:</p>
                  <p id="isbn13" class="type"</p>
                  <p id="isbn10" class="type">
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
     
   

            @if(Auth::user()->kanri_flag == 1)
                <div class="form-group"> 
                <p>ジーズの本の入力</p>
                <div class="center">
                        <label>入力：<input type="number" name="gs" /></label>
                 </div>
                </div>
            @endif
    <div class="form-group">  
          <p><b>本の貸出はできますか？</b></p>
          
          @if($errors->has('rental_flag'))
          <p style="color:#CC0033">エラー:{{$errors->first('rental_flag')}}</p>
          @endif
          
          <div class="block-contents">
          <label class="radio-inline"><input type="radio" name="rental_flag" value=0 @if(old('rental_flag')=="0") checked @endif>はい</label>
        　<label class="radio-inline"><input type="radio" name="rental_flag" value=1 @if(old('rental_flag')=="1") checked @endif>いいえ</label>
        　<label class="radio-inline"><input type="radio" name="rental_flag" value=2 @if(old('rental_flag')=="2") checked @endif>同期内のみ</label>
        　</div>
    </div>
    
   <div class="form-group">   
          <p><b>カテゴリーを選択する</b><p>
             
          @if($errors->has('category_id'))
          <p style="color:#CC0033">エラー:{{$errors->first('category_id')}}</p>
          @endif
          
          
          <div class="block-contents">   
           <p style="font-size:10px;">※複数回答可</p>
            <?php $i=0 ?>
            @foreach($genres as $genre)
              
                <div  id="accordion-{{$i}}}"  style="margin-top:20px;">
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
   </div>   
    
    <div class="form-group"> 
          <p><b>関連するタグを選択する</b></p>
          <div class="block-contents">
           <p style="font-size:10px;">※複数回答可</p>
              <p id="tagslist"></p>
              	<div class="modal-body">
                  <div id="ajax_data">
                      <!--もしtags_idに値が入っていたら-->
                      
                      @if(count(old('tag_id')) > 0)
                      @foreach($tags as $tag)
                      <?php
                        if (in_array($tag->id, old('tag_id'))){
                            echo '<label><input type="checkbox" value="' .$tag->id. '" class="check" name="tag_id[]" checked ></input> '.$tag->tags.' </label>';
                        }
                      ?>
                      @endforeach
                      @endif
                      
                      @if(count(old('tag_add')) > 0)
                      @for($i = 0; $i < count(old('tag_add')); $i++)
                      <label><input type="checkbox" checked="checked" class="check" value="{{ old('tag_add')[$i] }}" name="tag_add[]"></input>{{old('tag_add')[$i]}}</label>
                      <input type="hidden"  value="{{old('tag_category_id')[$i]}}" class="check" name="tag_category_id[]" ></input>
                      @endfor
                      @endif
                      
                  </div>
                  <div id ="new_tag"></div>
                   <div style="margin-top:20px; margin-bottom:20px;">
                        <p>カテゴリを選択してタグを追加する</p>
                        <!--ここで赤くするなりエラー文っぽくしてください-->
                        <p class="tag_add_error" style="display: none;">カテゴリーを選択して、追加したいタグ名を追加してね！</p>
                        
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
           @if($errors->has('key'))
          <p style="color:#CC0033">エラー:{{$errors->first('key')}}</p>
          @endif
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
           @if($errors->has('evaluation'))
          <p style="color:#CC0033">エラー:{{$errors->first('evaluation')}}</p>
          @endif
        <div class="evaluation center">
            <input id="star1" type="radio" name="evaluation" value="5" @if(old('evaluation')=="5") checked @endif/>
            <label for="star1"class="radio-inline"><span class="text">最高</span>★</label>
            <input id="star2" type="radio" name="evaluation" value="4" @if(old('evealuation')=="4") checked @endif/>
            <label for="star2"class="radio-inline"><span class="text">良い</span>★</label>
            <input id="star3" type="radio" name="evaluation" value="3" @if(old('evaluation')=="3") checked @endif/>
            <label for="star3"class="radio-inline"><span class="text">普通</span>★</label>
            <input id="star4" type="radio" name="evaluation" value="2" @if(old('evaluation')=="2") checked @endif/>
            <label for="star4"class="radio-inline"><span class="text">悪い</span>★</label>
            <input id="star5" type="radio" name="evaluation" value="1" @if(old('evaluation')=="1") checked @endif/>
            <label for="star5"class="radio-inline"><span class="text">最悪</span>★</label>
        </div>
        <p>{{old('evealuation')}}</p>
    </div> 
    
    <div class="form-group"> 
          @if($errors->has('comment_text'))
          <p>エラー:{{$errors->first('comment_text')}}</p>
          @endif
          <label>おすすめポイント</label>
          
          <div class="center">
          <textarea  rows="5" class="form-control" name="comment_text" placeholder="例）++の勉強をしたい人におすすめ">{{old('comment_text')}}</textarea>
          </div>
    </div>      
    
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
 



    <script>
        
        
        
        var selected_tag_ids = [];
       
        var send_title = function(){
            $('#books_wraper').empty();
            const title = $('#book_title').val();
            const googleUrl = "https://www.googleapis.com/books/v1/volumes?q=intitle:" + title;
            $.getJSON(googleUrl, function (data) {
                for (i = 0; i < data.items.length; i++) {
                   console.log(data.items[i].volumeInfo);
                    
                    let book_img;
                    try {
                             book_img = data.items[i].volumeInfo.imageLinks.smallThumbnail;
                             if (data.items[i].volumeInfo.imageLinks.smallThumbnail == ""){ throw new Error() }
                        } catch(e) {
                            book_img = "http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg";
                        }
                    
                    var book_isbn10 = "";
                    var book_isbn13 = "";
                　　try{
                　　  book_isbn10 =data.items[i].volumeInfo.industryIdentifiers[0].identifier;
                　　}catch(e){
                　　    book_isbn10=""
                　　}       
                　　
                　　try{
                　　  book_isbn13 =data.items[i].volumeInfo.industryIdentifiers[1].identifier;
                　　}catch(e){
                　　    book_isbn13=""
                　　}
                
                if (book_isbn10.length != 10) {
                    book_isbn10 = "";
                    book_isbn13 = "";
                }
                　　
                    $('#books_wraper').append('<div id="book' +i+ '" onClick="book_select('+i+')"><p class="titletitle">' +data.items[i].volumeInfo.title+ '</p><p class="isbn10_2" style="display:none">' + isbn10+  '</p><p class="isbn13_2" style="display:none">' + book_isbn13+ '</p><p class="BookAuthor_2"style="display:none">' + data.items[i].volumeInfo.authors + '</p><p class="PublishedDate_2"style="display:none">' + data.items[i].volumeInfo.publishedDate + '</p><p class="BookDiscription_2"style="display:none">' + data.items[i].volumeInfo.description + '</p><p class="Publisher_2" style="display:none">' + data.items[i].volumeInfo.publisher +  '</p><p class="BookImage_2" style="display:none">' + book_img + '</p><img class="imgimg" src="'+ book_img +'"></div>');
                }
            })
        }
        
        function book_select(num){
            $("#BookTitle").html('<input class="form-control input-lg" name="BookTitle" readonly="readonly" type="text"  value="' + $('#book' + num).children('.titletitle').text() +
                            '">');
            $("#isbn10").html('<input class="form-control input-sm" name ="isbn10" readonly="readonly" type="hidden" value="' + $('#book' + num).children('.isbn10_2').text() +
                            '">');  
            $("#isbn13").html('<input class="form-control input-sm" name ="isbn13" readonly="readonly" type="number" value="' + $('#book' + num).children('.isbn13_2').text() +
                            '">');    
            $("#BookAuthor").html('<input class="form-control input-sm" name="BookAuthor" readonly="readonly" type="text" value="' + $('#book' + num).children('.BookAuthor_2').text() +
                            '">');   
            $("#PublishedDate").html('<input class="form-control input-sm" name="PublishedDate" readonly="readonly" type="text" value="' + $('#book' + num).children('.PublishedDate_2').text() +
                            '">'); 
            $("#Publisher").html('<input class="form-control input-sm" name="Publisher" readonly="readonly" type="text" value="'+ $('#book' + num).children('.Publisher_2').text() +
                            '">');  
            $("#BookDiscription").html('<input class="form-control input-lg" name="BookDiscription"  readonly="readonly" type="text" value="'+ $('#book' + num).children('.BookDiscription_2').text() +
                            '">');               
            $("#BookThumbnail").html('<img src="'+$('#book' + num).children('.imgimg').attr('src')+'">');
            $("#BookImage").html('<input name="BookImage" type="hidden" value="' + $('#book' + num).children('.BookImage_2').text() +
                            '">');
            $('#books_wraper').empty();
        }
       
        var send_ISBN =function(){
            const isbn = $("#isbn").val();
            const googleUrl = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn;
            const openUrl   = "https://api.openbd.jp/v1/get?isbn=" + isbn;
          
           $.getJSON(openUrl , function (data1) {
                 if (data1[0]===null) {
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
                            $("#message").html(' <p class = "bg-warning" id = "warning" > 該当する書籍がありません。 </p>');
                            $('#message > p').fadeOut(3000);
                        } else {
                            //googleURLの処理
                           
                            $("#BookTitle").html('<input class="form-control input-lg" name="BookTitle" readonly="readonly" type="text"  value="' + data.items[0].volumeInfo.title +
                            '">');
                            $("#isbn10").html('<input class="form-control input-sm" name ="isbn10" readonly="readonly" type="hidden" value="' + data.items[0].volumeInfo.industryIdentifiers[
                                0].identifier + '">');
                            $("#isbn13").html('<input class="form-control input-sm" name ="isbn13" readonly="readonly" type="number" value="' + data.items[0].volumeInfo.industryIdentifiers[
                                1].identifier + '">');
                            $("#BookAuthor").html('<input class="form-control input-sm" name="BookAuthor" readonly="readonly" type="text" value="' + data.items[0].volumeInfo.authors +
                                '">');
                            $("#PublishedDate").html('<input class="form-control input-sm" name="PublishedDate" readonly="readonly" type="text" value="' + data.items[0].volumeInfo
                                .publishedDate + '">');
 
                            let description = "";
                         
                            $("#BookDiscription").html('<input class="form-control input-lg" name="BookDiscription"  readonly="readonly" type="text" value="' + data.items[0].volumeInfo
                             .description + '">');
                            
                            
                            $("#Publisher").html('<input class="form-control input-sm" name="Publisher"  readonly="readonly" type="text" value="' 
                            + data.items[0].volumeInfo.publisher + '">');
      
                                 
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
                        $("#isbn10").html('<input class="form-control input-sm" name ="isbn10" readonly="readonly" type="hidden" value="' + data1[0].summary.isbn + 
                        '">');
                        $("#isbn13").html('<input class="form-control input-sm" name ="isbn13" readonly="readonly" type="number" value="' + data1[0].summary.isbn + 
                        '">');
                        $("#BookAuthor").html('<input class="form-control input-sm" name="BookAuthor" readonly="readonly" type="text" value="' + data1[0].summary.author +
                        '">');
                        $("#PublishedDate").html('<input class="form-control input-sm" name="PublishedDate" readonly="readonly" type="text" value="' +data1[0].summary.pubdatey  +
                         '">');
                        $("#Publisher").html    ('<input class="form-control input-sm" name="Publisher" readonly="readonly" type="text" value="' +data1[0].summary.publisher  + 
                        '">');

                        let description = "";
                        if(data1[0].onix.CollateralDetail.TextContent){
                            description = data1[0].onix.CollateralDetail.TextContent[0].Text;
                        }else{
                            description = "書誌情報なし";
                        }
                        $("#BookDiscription").html('<input class="form-control input-lg" name="BookDiscription"  readonly="readonly" type="text" value="'
                        + description + '">');
                       console.log(data1[0].summary.cover);
                     
                     
                        try {
                             $("#BookThumbnail").html('<img src=\"' + data1[0].summary.cover +'\ " />');   
                             if (data1[0].summary.cover == ""){ throw new Error() }
                        } catch(e) {
                            $("#BookThumbnail").html('<img src="http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg" width="100",heigt="200">');
                        }

                        try {
                            $("#BookImage").html('<input name="BookImage" type="hidden" value="' + data1[0].summary.cover + '">');
                             if (data1[0].summary.cover == ""){ throw new Error() }
                        } catch(e) {
                            $("#BookImage").html('<input name="BookImage" type="hidden" value="http://www.tatemachi.com/wp/wp-content/themes/tatemachi/img/shopimage-noimage.jpg">');
                        }
                }
            });
        };
      
      



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
            $('#sampleModal').modal('show');
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
    if (new_tag_category_id == "カテゴリを選択" || new_tag_name == "") {
        $('.tag_add_error').show()
        return true;
    }

    $("#new_tag").append('<label><input type="checkbox" checked="checked" class="check" value="'+ new_tag_name +'" name="tag_add[]"></input>'+new_tag_name+"</label>");
    $("#new_tag").append('<input type="hidden"  value="'+new_tag_category_id+'" class="check" name="tag_category_id[]" ></input>');

 });


// バーコードリーダー


			var takePicture = document.querySelector("#Take-Picture"),
			showPicture = document.createElement("img");
			Result = document.querySelector("#textbit");
			var canvas =document.getElementById("picture");
			var canvas_wrapper =document.getElementById("canvas_wrapper");
			
			var ctx = canvas.getContext("2d");
			JOB.Init();
			JOB.SetImageCallback(function(result) {
				if(result.length > 0){
				    /*
					var tempArray = [];
					for(var i = 0; i < result.length; i++) {
						tempArray.push(result[i].Format+" : "+result[i].Value);
					}
					Result.innerHTML=tempArray.join("<br />");
					*/
					var isbns = result.filter((_isbn) => {
					    return _isbn.Value.indexOf('978') == 0;
					    
					});
					if (isbns.length == 1) {
					    document.getElementById("isbn").value = isbns[0].Value;
					    send_ISBN();
					}
				}else{
					if(result.length === 0) {
					    document.getElementById("isbn").value = "ISBNコードを読み込めませんでしたので、入力して下さい。";
					}
				}
			});
			JOB.PostOrientation = true;
			JOB.OrientationCallback = function(result) {
				canvas.width = result.width;
				canvas.height = result.height;
				var data = ctx.getImageData(0,0,canvas.width,canvas.height);
				for(var i = 0; i < data.data.length; i++) {
					data.data[i] = result.data[i];
				}
				ctx.putImageData(data,0,0);
			};
			JOB.SwitchLocalizationFeedback(true);
			JOB.SetLocalizationCallback(function(result) {
				ctx.beginPath();
				ctx.lineWIdth = "2";
				ctx.strokeStyle="red";
				for(var i = 0; i < result.length; i++) {
					ctx.rect(result[i].x,result[i].y,result[i].width,result[i].height); 
				}
				ctx.stroke();
			});
			if(takePicture && showPicture) {
				takePicture.onchange = function (event) {
					var files = event.target.files;
					if (files && files.length > 0) {
						file = files[0];
						try {
							var URL = window.URL || window.webkitURL;
							showPicture.onload = function(event) {
						        canvas_wrapper.style="display:block";
								Result.innerHTML="";
								JOB.DecodeImage(showPicture);
								URL.revokeObjectURL(showPicture.src);
                                
							};
							showPicture.src = URL.createObjectURL(file);
						}
						catch (e) {
							try {
								var fileReader = new FileReader();
								fileReader.onload = function (event) {
									showPicture.onload = function(event) {
										Result.innerHTML="";
										JOB.DecodeImage(showPicture);
									};
									showPicture.src = event.target.result;
								};
								fileReader.readAsDataURL(file);
							}
							catch (e) {
								Result.innerHTML = "Neither createObjectURL or FileReader are supported";
							}
						}
					}
				};
			}
		


</script>
@endsection
@section('footer')