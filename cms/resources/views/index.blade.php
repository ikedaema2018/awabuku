@extends('layouts.app')

@section('content')

    @if(isset($alert))
    <p>返却期限を過ぎている本があるため新規貸出は行えません</p>
    @endif


    <div class="box">
        <!--<h3 class="study_title">html/css</h3>-->
        
        @if(count($categories)>0)
            @if(count($books)>0)
            
                
                @foreach($categories as $category)
        
        <!--テーブルタグの長さの揃えたいときはcssで調整してください-->
        <table>
            
        <!--ここの長さを調節すれば揃うはずです。俺はCSSダメなのでできないときは秦さんにでも聞いてください-->
        <tr>
            <th>
                
            </th>
            <th>
            
            </th>
            <th>
                
            </th>
        <tr>
        <!--ここの長さを調節すれば揃うはずです。俺はCSSダメなのでできないときは誰かに聞いてください-->
        
                　　@if($books->get($category->id) != null)
                　　<tr>
                　　<p>{{$category->category_name}}</p>
                　　
                        @foreach($books->get($category->id) as $book)
                            
                              <td>
                                   {{$book->id}}
                              </td>
                              <td>
                               <td><a href="{{url('/rental/'.$book->id)}}">{{$book->BookTitle}}</a></td>
                              <td>
                                   {{$book->BookAuthor}}
                              </td>
                            
                        @endforeach
                    </tr>
                    @endif
                @endforeach
            
            
            @endif
        @endif
        </table>
    </div>
                        
                       
    
    <div><a href="{{url('book')}}">もっと見る</a></div>
</div>

@endsection