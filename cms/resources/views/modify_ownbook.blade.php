@extends('layouts.app')

@section('content')
<?php
use App\Tag;
?>

<h2>登録内容を修正する</h2>

<div class="row">
<div class="col-sm-offset-2 col-sm-8 col-sm-offset-2">
    <div class="row">
        <div class="col-sm-4"　style="background:#CCCCCC height:200px;" style="text-align:center;">
            <img src="{{ $book->BookImage}}"></img>
        </div>
        <div class="col-sm-8"　style="background:C;height:200px;">
            <h2>{{ $book->BookTitle }}</h2>
            <p>{{ $book->BookAuthor }}</p>
            <p>{{ $book->isbn10 }}/&nbsp;{{ $book->isbn13 }}</p>
            <p>{{ $book->PublishedDate}}</p>
        </div>
    </div>    




<!--owner_info始まる--> 
<div class="owner_info">
        <form action="{{url('book')}}" method="post" class="horizontal">
              
        {{ csrf_field() }}
        
           
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
                    <button type="submit" class="btn btn-info"  style="width: 145px; font-size: 21px;">修正する</button>
                </div>
            </div>    
    </div> 
    </form>  
 </div>
 
<!--owner_info終わり-->      

</div>
</div>

                    
                            

 


<script>
var selected_tag_ids = [];
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
console.log(new_tag_category_id);

    $("#new_tag").append('<label><input type="checkbox" checked="checked" class="check" value="'+ new_tag_name +'" name="tag_add[]"></input>'+new_tag_name+"</label>");
    $("#new_tag").append('<input type="hidden"  value="'+new_tag_category_id+'" class="check" name="tag_category_id[]" ></input>');

 });

</script>
@endsection
@section('footer')