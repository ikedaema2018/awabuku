@extends('layouts.app')

@section('content')
 <div class="jumbotron text-center">
     <h1>アワブク<small>our books</small></h1>
     <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;g's library</h2>
</div>
    @if(isset($alert))
    <p>返却期限を過ぎている本があるため新規貸出は行えません</p>
    @endif
    <div class="box">
        @if(count($categories)>0)
            @if(count($category_lists)>0)
             <div class="row">
                @foreach($categories as $category)
                　@if($category_lists->get($category->id) != null)
                     <h2 class="col-xs-12">{{$category->category_name}}</h2>
                           
                            @for ($i=0; count($category_lists->get($category->id)) > $i ;$i++)
                             <div class="col-xs-2" style="backgroud:#ccc,height:250px;">
                                <ul style="list-style:none;">
                                  <li><img src="{{$category_lists->get($category->id)[$i]->book_Name->BookImage}}"><img></li>
                                  <li>{{$category_lists->get($category->id)[$i]->book_Name->BookAuthor}}</li>
                                  <li><a href="{{url('rental/'.$category_lists->get($category->id)[$i]->book_Name->id)}}">{{$category_lists->get($category->id)[$i]->book_Name->BookTitle}}</a></li>
                                  
                                  
                                </ul>
                             </div>
                               @if($i==6)
                               @break
                               @endif
                            @endfor
                     
                    @endif
                @endforeach
                </div>
            @endif
        @endif
         <div><a href="{{url('book')}}">もっと見る</a></div>
    </div>
@endsection