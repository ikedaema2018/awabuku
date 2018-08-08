@extends('layouts.app')

@section('content')
    <form action="{{url('category_genru')}}" method="post">
                {{ csrf_field() }}
        カテゴリー：<input type="text" name="category_genruname">
        <button type="submit">送信</button>
    </form>
    
    <div>
        @if (count($category_genrus) > 0)
        <table>
            <thead>
                <th>カテゴリージャンル一覧</th>
            <th>
                <td>&nbsp;</td>
            </th>
            </thead>
            
            <tbody>
                @foreach ($category_genrus as $category_genru)
                <tr>
                    <td>{{ $category_genru->id }}</td>
                    <td>{{ $category_genru->category_genruname }}</td>
                
                    <td>
                        <form action="{{url('category_genru/'.$category_genru->id)}}" method="post">
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