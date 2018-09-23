@extends('layouts.app')

@section('content')
    <form action="{{url('category')}}" method="post">
                {{ csrf_field() }}
        <div class="form-group">             
            <label class="col-sm-3 control-label"><b>カテゴリー：</b></label>
            <div class="col-sm-9">    
                <input type="text" name="category_name">
            </div>
        </div>
        <div class="form-group"> 
            <p class="col-sm-3 control-label"><b>ジャンル:</b></p>
             @foreach($category_genres as $category_genre)
            <label class="radio-inline">
            <input type="radio" name="category_genre" value="{{$category_genre->id}}">{{$category_genre->category_genrename}}
            </label>
            @endforeach
            
        </div>
        <div class="form-group">
            　　<div class="col-sm-offset-6 col-sm-6 text-left">
                　<button type="submit" >送信</button>
                </div>
        </div>
        
    　　<div>
    　　<a href="{{url('category_genre')}}">カテゴリジャンル登録ページへ</a>
    　　</div>
       
    </form>
    
    <div style="margin-top:60px;">
        @if (count($categories) > 0)
        <table class="table table-striped">
            <tr>
                <th>カテゴリーID</th>
                <th>カテゴリー名</th>
                <th>カテゴリージャンル</th>
                <th>更新/削除</th>
            </tr>
     
            <tbody>
                @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->category_Genre->category_genrename }}</td>
                    <td>{{ $category->category_name }}</td>
                    
                
                    <td>
                        <form action="{{url('category/'.$category->id)}}" method="post">
                            {{ csrf_field() }}
                    　　<button type="submit">変更</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    </div> 
@endsection
@section('footer')