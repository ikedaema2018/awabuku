@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Comment;
use App\Scopes\LivingBookScope;


?>
<!--ジャンボトロン-->
    <div class="container">
        <div class="jumbotron text-center">
           <span navbar-brand><img class="avater img-circle" src="{{Auth::user()->avater}}"></img></span>
            <h3>{{Auth::user()->name}}さんのマイページ</h3>
                <p>クラスを選択してください</p>
                <form action="{{url('user_class_insert')}}" method="post">
                {{ csrf_field() }}
                  <select name="class" id="" style="font-size:200%;border:1px;">
                           <option value="1">LAB5</option>
                           <option value="2">LAB4</option>
                           <option value="3">Teacher</option>
                           <option value="4">Tuter</option>
                  </select>
                            <button id="bbb" form="form_id">選択</button>
                            
                 </form> 
           
        </div>
    </div>
<!--ジャンボトロン終わり-->



@endsection
