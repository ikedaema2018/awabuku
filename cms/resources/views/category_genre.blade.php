@extends('layouts.app')

@section('content')
    <form action="{{url('category_genre')}}" method="post">
                {{ csrf_field() }}
        カテゴリージャンル：<input type="text" name="category_genrename" style="width:800px;">
        <button type="submit">送信</button>
    </form>
    
    <div>
        @if (count($category_genres) > 0)
        <table>
            <thead>
                <th>カテゴリージャンル一覧</th>
            <th>
                <td>&nbsp;</td>
            </th>
            </thead>
            
            <tbody>
                @foreach ($category_genres as $category_genre)
                <tr>
                    <td>{{ $category_genre->id }}</td>
                    <td>{{ $category_genre->category_genrename }}</td>
                
                    <td>
                        <form action="{{url('category_genre/'.$category_genre->id)}}" method="post">
                            {{ csrf_field() }}
                            <button type="submit">更新</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
@endsection