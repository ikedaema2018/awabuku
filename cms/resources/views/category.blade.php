@extends('layouts.app')

@section('content')
    <form action="{{url('category')}}" method="post">
                {{ csrf_field() }}
        カテゴリー：<input type="text" name="category_name">
        <button type="submit">送信</button>
    </form>
    
    <div>
        @if (count($categories) > 0)
        <table>
            <thead>
                <th>カテゴリー一覧</th>
            <th>
                <td>&nbsp;</td>
            </th>
            </thead>
            
            <tbody>
                @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->category_name }}</td>
                
                    <td>
                        <form action="{{url('category/'.$category->id)}}" method="post">
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