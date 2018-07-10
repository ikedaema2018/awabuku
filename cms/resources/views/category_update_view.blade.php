@extends('layouts.app')

@section('content')
    <form action="{{ url('category_update') }}" method="post">
        {{ csrf_field() }}
        <input type="text" name="category_name" value="{{$category->category_name}}"/>
        <input type="hidden" name="id" value="{{ $category->id }}"/>
        <button type="submit">更新する</button>
    </form>
@endsection