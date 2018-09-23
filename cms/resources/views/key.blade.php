@extends('layouts.app')

@section('content')
    <form action="{{url('key_insert')}}" method="post">
                {{ csrf_field() }}
        key：<input type="text" name="key" style="width:800px;">
        <button type="submit">送信</button>
    </form>
    
    <div>
        @if (count($keys) > 0)
        <table>
            <thead>
                <th>カテゴリージャンル一覧</th>
            <th>
                <td>&nbsp;</td>
            </th>
            </thead>
            
            <tbody>
                @foreach ($keys as $key)
                <tr>
                    <td>{{ $key->id }}</td>
                    <td>{{ $key->key }}</td>
                
                    <td>
                        <form action="{{url('key/'.$key->id)}}" method="post">
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