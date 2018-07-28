@extends('layouts.app')

@section('content')

<h1>貸出機能</h1>
      

    <p>{{ $book->BookTitle }}</p>
    <p>{{ $book->BookAuthor }}</p>
    <p>{{ $book->isbn10 }}</p>
    <p>{{ $book->isbn13 }}</p>
    <p>{{ $book->PubrishedDate}}</p>
    <p>{{ $book->BookDiscription}}</p>
    <img src="{{ $book->BookImage}}"></img>
                    
<table>
    <tr>
        <th>本のowner</th>
        <th>貸与可否</th>
        <th>借りている人</th>
        <th>返却予定</th>
        <th>借ります！</th>
    </tr>
    
            @if(isset($owners)>0)
            @foreach($owners as $owner)
            <tbody>

    <tr>
                <td>
                    <p>{{$owner->owner}}</p>
                </td>  
                <td>
                    @if ($owner->rental_flag === 1)
                    <p>不可</p>
                    @elseif ($owner->rental_flag == 0 && $owner->return_flag == 0)
                    <p>貸出可能</p>
                    @elseif ($owner->rental_flag == 0 && $owner->return_flag== 1)
                    <p>貸出中</p>
                    @endif
                </td>
                 <td>
                   <p> aaa</a>
            
                   
                </td>                
                <td>
                    qqq
                </td> 
                 <td>
                    @if($owner->return_flag == 0)
                    <form action="{{ url('book_rental') }}" method="post">
                        {{ csrf_field() }} 
                     <input type="hidden" name="return_flag" value="{{ $owner->return_flag }}">
                     <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                     <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                    <button type="submit">本を借りる</button>
                    </form>
                    @elseif($owner->return_flag == 1)
                    <p>&nbsp;</p>
                    
                    @endif
                </td>   
                </tr>
                </tbody>
            
            @endforeach
            @endif
    </table>

 @endsection
        
                    
                    




