@extends('layouts.app')

@section('content')

   <h3>書誌情報が登録されました。</h3>
      <div class="row">
                 <div class="col-sm-3" style="text-align:center;">
                 <img src="{{ $owner->books->BookImage}}"></img>
                 </div>
                <div class="col-sm-9">
                    <h2>{{ $owner->books->BookTitle }}</h2>
                    <p>{{ $owner->books->BookAuthor }}</p>
                    <p>{{ $owner->books->isbn10 }}/{{ $owner->books->isbn13 }}</p>
                    <p>{{ $owner->books->PubrishedDate}}</p>
                    <p>{{ $owner->books->BookDiscription}}</p>
                 </div>   
    　</div>   
    　<div  style="text-align: center;">
    　   <a href="{{url('mypage/')}}"><h4><b>マイページへ</b></h4></a>
     </div>


@endsection
@section('footer')