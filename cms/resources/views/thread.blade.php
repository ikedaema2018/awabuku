@extends('layouts.app')

@section('content')
    <form action="{{url('thread')}}" method="post">
                {{ csrf_field() }}
        <div class="form-group">             
            <label class="col-sm-3 control-label"><b>件名：</b></label>
            <div class="col-sm-9">    
                <input type="text" name="thread_sub">
            </div>
        </div>        
        <div class="form-group">             
            <label class="col-sm-3 control-label"><b>スレッド：</b></label>
            <div class="col-sm-9">    
                <input type="text" name="thread_body">
            </div>
        </div>
        <div class="form-group"> 
            <p class="col-sm-3 control-label"><b>ジャンル:</b></p>
             @foreach($categories as $category)
            <label class="radio-inline">
            <input type="radio" name="category" value="{{$category->id}}">{{$category->category_name}}
            </label>
            @endforeach
            
        </div>
           　<div class="form-group">
        　　<div class="col-sm-offset-6 col-sm-6 text-left">
            　<button type="submit" >送信</button>
            </div>
    </div>
       
    </form>
    
    <div style="margin-top:60px;">
        @if (count($categories) > 0)
        <table class="table table-striped">
            <tr>
                <th>スレッドーID</th>
                <th>スレッド名</th>

                <th>更新/削除</th>
            </tr>
     
            <tbody>
                @foreach ($threads as $thread)
                <tr>
                    <td>{{ $thread->id }}</td>
                    <td>{{ $thread->thread_sub }}</td>

                
                    <td>
                        <form action="{{url('thread/'.$thread->id)}}" method="post">
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
    </div> 
@endsection