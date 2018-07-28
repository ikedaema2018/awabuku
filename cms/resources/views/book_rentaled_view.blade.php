@extends('layouts.app')

@section('content')

<h1>本の貸出が完了しました。</h1>

	<p>{{ $rental->return_day}}までにご返却ください。</p>
    
    <p>{{ $owner->owner}}の本</p>
    <p>{{ $owner->books->BookTitle }}</p>
    <p>{{ $owner->books->BookAuthor }}</p>
    <p>{{ $owner->books->isbn10 }}</p>
    <p>{{ $owner->books->isbn13 }}</p>
    <p>{{ $owner->books->PubrishedDate}}</p>
    <p>{{ $owner->books->BookDiscription}}</p>
    <img src="{{ $owner->books->BookImage}}"></img>
    

@endsection