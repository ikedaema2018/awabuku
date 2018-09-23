@extends('layouts.app')

@section('content')
    <form action="{{ url('tag_update') }}" method="post">
        {{ csrf_field() }}
        <input type="text" name="tags" value="{{$tag->tags}}"/>
        <input type="hidden" name="id" value="{{ $tag->id }}"/>
        <button type="submit">更新する</button>
    </form>
@endsection