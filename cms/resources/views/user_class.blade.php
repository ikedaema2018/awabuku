@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Class;
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
                         @if(isset($classes)>0)
                         @foreach($classes as $class)
                      
                           <option value="{{$class->id}}">{{$class->class_name}}</option>
                        
                         @foreach
                         @endif
                  </select>
                            <button type="submit" id="bbb">選択</button>
                            
                 </form> 
           
        </div>
    </div>
<!--ジャンボトロン終わり-->



@endsection
