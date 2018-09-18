@extends('layouts.app')

@section('content')

   <h3>書誌情報が登録されました。</h3>
      <div class="row">
                <p hidden>{{ $owner->books->id }}</p>
            
                 <div class="col-xs-3">
                 <img src="{{ $owner->books->BookImage}}"></img>
                 </div>
                <div class="col-xs-9">
                    <h2>{{ $owner->books->BookTitle }}</h2>
                    <p>{{ $owner->books->BookAuthor }}</p>
                    <p>{{ $owner->books->isbn10 }}/{{ $owner->books->isbn13 }}</p>
                    <p>{{ $owner->books->PubrishedDate}}</p>
                    <p>{{ $owner->books->BookDiscription}}</p>
                 </div>   
    　</div>   
    　
<div class="col-sm-offset-2 col-sm-8">
    <div class="panel panel-warning">
    	<div class="panel-heading">
    		<h3 class="panel-title inline">
    			<a data-toggle="collapse" href="#sampleCollapseListGroup1">
    				登録内容を修正する
    			</a>
    		</h3>
    	</div> 
    	
<form action="{{ url('book_update') }}" method="post" class="form-horizontal">
{{ csrf_field() }}   

<div id="sampleCollapseListGroup1" class="panel-collapse collapse in">    　

    <div class="form-group">
        <label class="col-sm-3" for="name">所有者の名前を記入してください。</label>
        <div class="col-sm-9"><input type="text" name="owner" value="{{ $owner->owner}}"></div>
    </div> 
    
    <div class="form-group">
        <label class="col-sm-3" for="rental">本を貸し出せますか？</label>
        <div class="col-sm-9">
          <input type="radio" name="rental_flag" value="0"<?php if($owner->rental_flag==0):echo 'checked="checked"';endif;?>>はい
        　<input type="radio" name="rental_flag" value="1"<?php if($owner->rental_flag==1):echo 'checked="checked"';endif;?>>いいえ
        </div>
    </div>  
  
    <div class="form-group row">   
          <div class="col-sm-3">
          <p><b>カテゴリー</b><p>
          <p>複数回答可</p>
          </div> 
          <div class="col-sm-9">
          @if(count($genreCategories)>0)
          <?php $i=0 ?>
                @foreach($genreCategories as $genre => $categories)
                
                <div  id="accordion">
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
    </div>  
  
    <div class="form-group">  
        <label class="col-sm-3" for="deleate">書籍のデータを削除する。</label> 
        <div class="col-sm-9">
          <input type="radio" name="life_flag" value="1"<?php if($owner->life_flag==1):echo 'checked="checked"';endif;?>>はい
        　<input type="radio" name="life_flag" value="0"<?php if($owner->life_flag==0):echo 'checked="checked"';endif;?>>いいえ
        </div> 
    </div> 

      <input type="hidden" name="id" value="{{ $owner->id }}">
      <input type="hidden" name="book_id" value="{{ $owner->book_id }}">
    <div style="text-align:center;">   
        <button type="submit" class="btn btn-warning">内容を訂正する。</button>
    </div>    
   
  </div>  
        
        
       
    
    	</form>
  </div>
</div>

@endsection
@section('footer')