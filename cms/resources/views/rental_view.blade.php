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
    <img src="{{ $book[0]->BookImage}}"></img>
                    
<table>
    <tr>
        <th>本のowner</th>
        <th>貸与可否</th>
        <th>借りている人</th>
        <th>返却予定</th>
        <th>借ります！</th>
    </tr>
    <tbody>

    <tr>
            @if(isset($owners)>0)
            @foreach($owners as $owner)
                <td>
                    <p>{{$book->owners->owner}}</p>
                </td>  
                <td>
                    @if ($book->owners->rental_flag === 1)
                    <p>貸出不可</p>
                    @elseif ($book->owners->rental_flag == 0 && $book->owners->return_flag == 0)
                    <p>貸出可能</p>
                    @elseif ($book->owners->rental_flag == 0 && $book->owners->return_flag== 1)
                    <p>貸出中</p>
                    @endif
                </td>
                <td>
                    
                </td>    
            
            @endforeach
            @endif
           
            
         </td>        
         <td><p>{{ $book->owners->owner }}</p></td>
        <td></td>
        <td>
            @if($book->owners->rental_flag == 0)
            <a href="">本を借りる</a>
            @else
            <a href="" >--</a>
            @endif
        </td>
          </tr>

 
        
                    
                    




