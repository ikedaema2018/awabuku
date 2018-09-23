@extends('layouts.app')

@section('content')
    <form action="{{ url('category_genre_update') }}" method="post">
        {{ csrf_field() }}
        <input type="text" name="category_genrename" value="{{$category_genre->category_genrename}}" style="width:800px;">
        <input type="hidden" name="id" value="{{ $category_genre->id }}"/>
        <button type="submit">更新する</button>
    </form>
@endsection