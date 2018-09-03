@extends('layouts.app')

@section('content')
<?php

use App\User;

?>
<div class="col-sm-offset-2 col-sm-8 col-sm-offset-2">
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
                <div class="row">
                <div class="form-group">  
                    <div class="col-sm-3 right"> 
                    <label class="control-label"><b>スレッド名：</b></label>
                    </div>
                    <div class="col-sm-6">    
                        <input type="text" name="thread_sub" class="col-xs-6" placeholder="件名を入力してください。">
                    </div>
                </div> 
                </div>
                
                <div class="row">
                <div class="form-group">  
                
                    <div class="col-sm-3 right">
                    <label class="control-label"><b>内容：</b></label>
                    </div>
                    <div class="col-sm-6">    
			            <textarea placeholder="質問内容を記入してください。" rows="3" class="form-control" id="InputTextarea"　name="thread_body" ></textarea>
		        
                        
                    </div>
                </div>
                </div>
                <div class="row">
                <div class="form-group">   
                      <div class="col-sm-3 right">
                          <label class="control-label"><b>カテゴリー:</b></label>
                      </div>
                      <div class="col-sm-9">
                              @if(count($genreCategories)>0)
                              <?php $i=0 ?>
                                    @foreach($genreCategories as $genre => $categories)
                                  
                                    <div  id="accordion"　class="col-sm-9">
                                            @if(count($categories)>0)   
                                            <a data-toggle="collapse" data-parent="#accordion" href="#sample{{$i}}">{{$genre}}</a>
                                            @endif    
                                            <div class="collapsing collapse" id="sample{{$i}}">
                                            	@foreach($categories as $category)
                            	                 <input type="checkbox" name="category" value="{{$category["id"]}}">{{$category["category_name"]}}</input>
                                                @endforeach
                                            </div>
                                    </div>  
                                    <?php $i = $i + 1 ?>
                                    @endforeach
                              @endif
                      </div>      
                </div>  
	            </div>
	            <div class="panel-footer inline">
	                <button type="submit">スレッドを投稿する</button>
	           </div> 
	       </form>
    </div>
</div>
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