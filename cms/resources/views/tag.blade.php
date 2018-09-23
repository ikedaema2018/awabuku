@extends('layouts.app')

@section('content')
    <form action="{{url('tag_insert')}}" method="post">
                {{ csrf_field() }}
        <div class="form-group">             
            <label class="col-sm-3 control-label"><b>タグ：</b></label>
            <div class="col-sm-9">    
                <input type="text" name="tags">
            </div>
        </div>
        <div class="form-group"> 
            <p class="col-sm-3 control-label"><b>カテゴリ:</b></p>
             @foreach($categories as $category)
            <label class="radio-inline">
            <input type="radio" name="category_id" value="{{$category->id}}">{{$category->category_name}}
            </label>
            @endforeach
            
        </div>
        <div class="form-group">
            　　<div class="col-sm-offset-6 col-sm-6 text-left">
                　<button type="submit" >送信</button>
                </div>
        </div>
        
    　　<div>
    　　<a href="{{url('category')}}">カテゴリ登録ページへ</a>
    　　</div>
       
    </form>
    
    <div style="margin-top:60px;">
        @if (count($tags) > 0)
        <table class="table table-striped">
            <tr>
                <th>タグID</th>
                <th>タグ名</th>
                <th>カテゴリ名</th>
                <th>更新/削除</th>
            </tr>
     
            <tbody>
             
                @foreach ($tags as $tag)
                <tr>
                    <td>{{ $tag->id }}</td>
                    
                    <td>{{ $tag->tag_Category->category_name}}</td>
                    <td>{{ $tag->tags }}</td>
                    
                
                    <td>
                        <form action="{{url('tag/'.$tag->id)}}" method="post">
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