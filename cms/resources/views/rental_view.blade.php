@extends('layouts.app')

@section('content')

<h1>貸出機能</h1>
      
    <p>{{ $book->id }}</p>
    <p>{{ $book->BookTitle }}</p>
    <p>{{ $book->BookAuthor }}</p>
    <p>{{ $book->isbn10 }}</p>
    <p>{{ $book->isbn13 }}</p>
    <p>{{ $book->PubrishedDate}}</p>
    <p>{{ $book->BookDiscription}}</p>
    <img src="{{ $book->BookImage}}"></img>
                    
<table>
    <tr>
        <th>貸与可否</th>
        <th>借りている人</th>
        <th>返却予定</th>
        <th>借ります！</th>
    </tr>
    <tr>
        
         <td>
            @if ($book->rental_flag === 1)
            <p>貸出不可</p>
            @elseif ($book->rental_flag == 0 && $book->return_flag == 0)
            <p>貸出可能</p>
            @elseif ($book->rental_flag == 0 && $book->return_flag== 1)
            <p>貸出中</p>
            @endif
         </td>        
         <td><p>{{ $book->owner }}</p></td>
        <td></td>
        <td>
            @if($book->rental_flag == 0)
            <a href="">本を借りる</a>
            @else
            <a href="" >--</a>
            @endif
        </td>
    </tr>

 
        
                    
                    





@endsection