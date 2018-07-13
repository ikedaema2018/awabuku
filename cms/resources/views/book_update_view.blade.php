@extends('layouts.app')

@section('content')

   <h1>書誌情報が登録されました。</h1>
      
                    <p>{{ $book->id }}</p>
                    <p>{{ $book->BookTitle }}</p>
                    <p>{{ $book->BookAuthor }}</p>
                    <p>{{ $book->isbn10 }}</p>
                    <p>{{ $book->isbn13 }}</p>
                    <p>{{ $book->PubrishedDate}}</p>
                    <p>{{ $book->BookDiscription}}</p>
                    <img src="{{ $book->BookImage}}"></img>
                    

    <form action="{{ url('book_update') }}" method="post">
        {{ csrf_field() }}
        
                   <div>
                     <p>訂正があり場合は下記よりお願いいたします。
                        <p>所有者の名前を記入してください。</p>
                        <input type="text" name="owner" value="{{ $book->owner}}">

                        
                        
                        <p>本を貸し出せますか？</p>
                          <input type="radio" name="rental_flag" value="0"<?php if($book->rental_flag==0):echo 'checked="checked"';endif;?>>はい
                        　<input type="radio" name="rental_flag" value="1"<?php if($book->rental_flag==1):echo 'checked="checked"';endif;?>>いいえ
                  
                        <p>カテゴリー一覧</p>
                            @foreach($categories as $category)
                            <input type="radio" name="category_id" value="{{ $category->id }}" <?php if($book->category_id==$category->id):echo 'checked="checked"';endif;?>>{{$category->category_name}}
                            <input type="hidden" name="id" value="{{ $category->id }}"/>
                            @endforeach
                            
                        <p>書籍のデータを削除する。</p> 
                          <input type="radio" name="life_flag" value="0"<?php if($book->life_flag==1):echo 'checked="checked"';endif;?>>はい
                        　<input type="radio" name="life_flag" value="1"<?php if($book->life_flag==0):echo 'checked="checked"';endif;?>>いいえ
  
                          
                </div>
                    1  <input type="hidden" name="id" value="{{ $book->id }}">
        <button type="submit">更新する</button>
        
    </form>
@endsection