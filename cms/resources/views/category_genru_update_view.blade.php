@extends('layouts.app')

@section('content')
    <form action="{{ url('category_genru_update') }}" method="post">
        {{ csrf_field() }}
        <input type="text" name="category_genruname" value="{{$category_genru->category_genruname}}"/>
        <input type="hidden" name="id" value="{{ $category_genru->id }}"/>
        <button type="submit">更新する</button>
    </form>
@endsection