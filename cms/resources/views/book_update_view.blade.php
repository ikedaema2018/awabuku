@extends('layouts.app')

@section('content')

   <h1>書誌情報が登録されました。</h1>
      
                    <p>{{ $owner->books->id }}</p>
                    <p>{{ $owner->books->BookTitle }}</p>
                    <p>{{ $owner->books->BookAuthor }}</p>
                    <p>{{ $owner->books->isbn10 }}</p>
                    <p>{{ $owner->books->isbn13 }}</p>
                    <p>{{ $owner->books->PubrishedDate}}</p>
                    <p>{{ $owner->books->BookDiscription}}</p>
                    <img src="{{ $owner->books->BookImage}}"></img>
                    

    <form action="{{ url('book_update') }}" method="post">
        {{ csrf_field() }}
        
                   <div>
                     <p>訂正があり場合は下記よりお願いいたします。
                        <p>所有者の名前を記入してください。</p>
                        <input type="text" name="owner" value="{{ $owner->owner}}">

                        
                        
                        <p>本を貸し出せますか？</p>
                          <input type="radio" name="rental_flag" value="0"<?php if($owner->rental_flag==0):echo 'checked="checked"';endif;?>>いいえ
                        　<input type="radio" name="rental_flag" value="1"<?php if($owner->rental_flag==1):echo 'checked="checked"';endif;?>>いいえ
                  
                        <p>カテゴリー一覧</p>
                            @foreach($categories as $category)
                            <input type="radio" name="category_id" value="{{ $category->id }}" <?php if($owner->books->category_id==$category->id):echo 'checked="checked"';endif;?>>{{$category->category_name}}
                            
                            @endforeach
                            
                        <p>書籍のデータを削除する。</p> 
                          <input type="radio" name="life_flag" value="0"<?php if($owner->life_flag==1):echo 'checked="checked"';endif;?>>はい
                        　<input type="radio" name="life_flag" value="1"<?php if($owner->life_flag==0):echo 'checked="checked"';endif;?>>いいえ
  
                          
                </div>
                      <input type="hidden" name="id" value="{{ $owner->id }}">
                      <input type="hidden" name="book_id" value="{{ $owner->book_id }}">
                      
        <button type="submit">更新する</button>
        
    </form>
@endsection