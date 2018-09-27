@extends('layouts.app')

@section('content')
<?php
use App\Tag;
?>

<h2>Book Register</h2>

<h1>isbn以外のデータをnullでも登録できるようにする※条件は再度検討すること</h1>


<div class="row">
<div class="col-sm-offset-2 col-sm-8 col-sm-offset-2">

 <p class="page-header">本を登録する</p>
  <div class="form-group">
    <label  class="col-sm-3 control-label form-control-static" for="isbn">ISBNを入力してください:</label>
    <div class="col-sm-9">
        <input  class="form-control-static col-sm-9" type="text" class="form-control" id="isbn" placeholder="978で始まる13桁の数字を入力（ーハイフンは含まない）">
        <button id="btn" class="form-control-static">検索</button>
    </div>
    <div id="message"></div>
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

 <div class="owner_info">
     
   
　<input type="hidden" name="owner" value="{{Auth::user()->name}}" >
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
          <div class="block-contents">
          <label class="radio-inline"><input type="radio" name="rental_flag" value=0>はい</label>
        　<label class="radio-inline"><input type="radio" name="rental_flag" value=1>いいえ</label>
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
    	                <input type="checkbox" class="category" onClick="aaa({{$category->id}})" name="category_id" value="{{$category->id}}" id="check" data-name="{{$category->category_name}}">{{$category->category_name}}</input>
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
                  
                   <div style="margin-top:20px; margin-bottom:20px;">
                        <p>タグを追加する</p>
             
                        <select id="tags" name="tag_category_id">
                        <option value="" >カテゴリを選択</option>
                        </select>
                        <input type="text" name="tags" value=""></input>
                      
                    </div>
                 </div>
         
         </div>    
    </div> 

     
    <div class="form-group"> 
          <p><b>特徴</b></p>
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
                        $("#PublishedDate").html('<input class="form-control input-sm" name="PublishedDate" readonly="readonly" type="text" value="' +data1[0].summary.pubdate  + '">');
                        
                        let description = "";
                        if(data1[0].onix.CollateralDetail.TextContent){
                            description = data1[0].onix.CollateralDetail.TextContent[0].Text;
                        }else{
                            description = "書誌情報なし";
                        }
                        $("#BookDiscription").html('<input class="form-control input-lg" name="BookDiscription"  readonly="readonly" type="text" value="' + description + '">');
                       console.log(data1[0].summary.cover);
                     
                       
                       
                                
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
    console.log('category_ids', category_ids);
    
    var request = $.ajax({
        type: 'GET',
        url: "{{url('ajax')}}" + "/" + category_ids.join(","),
        cache: false,
        dataType: 'json',
        timeout: 1000
    });

    /* 成功時 */
        request.done(function(data){
            console.log(data);
            $("#ajax_data").empty();
            for(var i = 0; data.length > i; i++){
                console.log(data[i]);
               
                $("#ajax_data").append('<input type="checkbox" value="'+data[i].id+'" class="check" name="tag_id[]" data-name="'+data[i].tags+'">'+data[i].tags+"</input>");

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
                console.log('isSelected=%O, tag id=%O, selected tag ids=%O', isSelected, i.value, selected_tag_ids)
                if (isSelected) {
                    i.checked = true;
                }
            })
            $('#sampleModal').modal('show');
        });

    /* 失敗時 */
        request.fail(function(e){
            console.error(e);
        });
};


$(".category").on("click",function(){
   

    const selectedvalue =[];
    const selectedname =[];

   $(".category:checked").each(function(){
       
       selectedvalue.push($(this).val());
       selectedname.push($(this).data("name"));
   });
   
    console.log(selectedvalue);  
    let selectBox ='';
    
    for(let i=0;i<selectedvalue.length;i++){
        selectBox += '<option value="'+selectedvalue[i]+'" >'+selectedname[i]+'</option>';
    };
    const name = selectedname.join(',');//"php python"  
      console.log(selectBox);

    $("#tags").empty();
    $("#tags").append(selectBox);
  
    
});





</script>











    </script>


@endsection
@section('footer')