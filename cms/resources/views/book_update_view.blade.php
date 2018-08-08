@extends('layouts.app')

@section('content')

   <h1>書誌情報が登録されました。</h1>
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
<div style="background-color:#EDF7FF;">
<form action="{{ url('book_update') }}" method="post" class="form-horizontal">
    
    {{ csrf_field() }}
  
    <p>訂正がある場合は下記よりお願いいたします。
    
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
  
    <div class="form-group">
        <label class="col-sm-3" for="category">カテゴリー一覧</label>
        <div class="col-sm-9">
            @foreach($categories as $category)
            <input type="radio" name="category_id" value="{{ $category->id }}" <?php if($owner->books->category_id==$category->id):echo 'checked="checked"';endif;?>>{{$category->category_name}}
            @endforeach
        </div>    
    </div>  
  
    <div class="form-group">  
        <label class="col-sm-3" for="deleate">書籍のデータを削除する。</label> 
        <div class="col-sm-9">
          <input type="radio" name="life_flag" value="0"<?php if($owner->life_flag==1):echo 'checked="checked"';endif;?>>はい
        　<input type="radio" name="life_flag" value="1"<?php if($owner->life_flag==0):echo 'checked="checked"';endif;?>>いいえ
    </div> 

      <input type="hidden" name="id" value="{{ $owner->id }}">
      <input type="hidden" name="book_id" value="{{ $owner->book_id }}">
      
     <div class="form-group">  
     <div class="col-sm-offset-4 col-sm-8 text-center">
        <button type="submit" class="btn btn-warning">更新する</button>
     </div>
    </form>
</div>
@endsection