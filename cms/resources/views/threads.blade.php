@extends('layouts.app')

@section('content')
<?php

use App\User;

?>
<div class="panel panel-warning">
	<div class="panel-heading">
		<h3 class="panel-title inline">
			<a data-toggle="collapse" href="#sampleCollapseListGroup1">
				スレッドを投稿する
			</a>
		</h3>
	</div>
	<div id="sampleCollapseListGroup1" class="panel-collapse collapse in">
		
		    <form action="{{url('threads')}}" method="post">
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
                    <p class="col-sm-3 control-label"><b>カテゴリ:</b></p>
                     @foreach($categories as $category)
                    <label class="radio-inline">
                    <input type="radio" name="category" value="{{$category->id}}">{{$category->category_name}}
                    </label>
                    @endforeach
                </div>
                 
                
           
	            <div class="panel-footer"><button type="submit" >送信</button></div> 
	            </form>
	</div>
</div>


  <div style="margin-top:60px;">   
    <h3>スレッド一覧</h3>    
        @if (count($categories) > 0)
        <table class="table table-striped">
            <tr>
                <th>教えてほしい人！</th>　
                <th>スレッド名</th>

                <th>更新/削除</th>
            </tr>
     
            <tbody>
                @foreach ($threads as $thread)
                <tr>
                    <td>{{User::find($thread->user_name)->name }}</td>
                    <td><a href="{{url('thread/'.$thread->id)}}">{{ $thread->thread_sub }}</a></td>
                    
                
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