@extends('layouts.app')

@section('content')

    @if(isset($alert))
    <p>返却期限を過ぎている本があるため新規貸出は行えません</p>
    @endif


    <div class="box">
        <!--<h3 class="study_title">html/css</h3>-->
        
        @if(count($categories)>0)
            @if(count($books)>0)
            
                <div class="row">
                @foreach($categories as $category)

        
                　　@if($books->get($category->id) != null)
                　　
                　　  <h2 class="col-xs-12">{{$category->category_name}}</h2>
                   　   
                   　   
                           <!--{{count($books->get($category->id))}}-->
                            @for ($i=0; count($books->get($category->id)) > $i ;$i++)
                             <div class="col-xs-2" style="backgroud:#ccc,height:250px;">
                                <ul style="list-style:none;">
                              <li><img src="{{$books->get($category->id)[$i]->BookImage}}"><img></li>
                            　<li><a href="{{url('/rental/'.$books->get($category->id)[$i]->id)}}">{{$books->get($category->id)[$i]->BookTitle}}</a></li>
                              <li>{{$books->get($category->id)[$i]->BookAuthor}}</li>
                              
                              </ul>
                              </div>
                              @if($i==6)
                               @break
                              @endif
                              
                            @endfor
                         
                            
                            
                
                
            </div>
                @endif
                @endforeach
            @endif
        @endif
       
  
                        
                       
  

  
    <div><a href="{{url('book')}}">もっと見る</a></div>
      </div>
@endsection