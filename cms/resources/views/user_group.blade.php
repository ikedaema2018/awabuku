@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Group;
use App\Comment;
use App\Scopes\LivingBookScope;


?>
<!--ジャンボトロン-->
    <div class="container">
        <div class="jumbotron text-center">
           <span navbar-brand><img class="avater img-circle" src="{{Auth::user()->avater}}"></img></span>
            <h3>{{Auth::user()->name}}さんのグループ登録ページ</h3>
                <p>クラスを選択してください</p>
                <form action="{{url('user_group_insert')}}" method="post">
                {{ csrf_field() }}
                  <select name="group" id="" style="font-size:200%;border:1px;">
                         @if(isset($groups)>0)
                         @foreach($groups as $group)
                           <option value="{{$group->id}}">{{$group->group_name}}</option>
                         @endforeach
                         @endif
                  </select>
                            <button type="submit" id="bbb" class="btn btn-primary">選択</button>
                            
                 </form> 
           
        </div>
    </div>
<!--ジャンボトロン終わり-->



@endsection
