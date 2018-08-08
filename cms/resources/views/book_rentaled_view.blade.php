@extends('layouts.app')

@section('content')

<h1>本の貸出が完了しました。</h1>

    <div class="row">
        
        <div class="col-xs-12"　style="background:#CCC;height:200px;">
        	<h3 style="color:red;">{{ $rental->return_day}}までにご返却ください。</h3>
     </div>
     
    <div class="row">   	
        <div class="col-xs-3"　style="background:#CCC;height:200px;">
            
                <img src="{{ $owner->books->BookImage}}"></img>
        </div>
  
        <div class="col-xs-9"　style="background:#CCC;height:200px;">
            <p>{{ $owner->owner}}さんの本</p>
            <h2>{{ $owner->books->BookTitle }}</h2>
            <p>{{ $owner->books->BookAuthor }}</p>
            <p>{{ $owner->books->isbn13 }}</p>
        
            <p>{{ $owner->books->PubrishedDate}}</p>
        </div>    
    </div>


@endsection