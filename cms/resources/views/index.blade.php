@extends('layouts.app')

@section('content')
                        <div class="box">
                            <h3 class="study_title">html/css</h3>
                            <table>
                            <tr>
                            @if(isset($categories_1)>0)
                            @foreach($categories_1 as $category_1)
                               <td><a href="{{url('book')}}">{{$category_1->BookTitle}}</a></td>
                            </tr>
                            @endforeach
                            @endif
                            </table>
                        </div>
                        
                       
                </div>
                <div><a href="{{url('book')}}">もっと見る</a></div>
            </div>

@endsection