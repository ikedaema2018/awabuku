@extends('layouts.app')

@section('content')
     <div class="col-xs-12">
         <h3>本の貸出が完了しました。</h3>
     </div>
     <div class="col-xs-12">
         <h4 style="color:red;">{{ $rental->return_day->format('Y-m-d')}}までにご返却ください。</h4>
     </div>
     
     <div class="row">   	
        <div class="col-sm-3" style="text-align: center;">
            <img src="{{ $owner->books->BookImage}}" height="200px;"></img>
        </div>
  
        <div class="col-sm-9">
            
            <h2>{{ $owner->books->BookTitle }}</h2>
            <p>{{ $owner->books->BookAuthor }}</p>
            <p>{{ $owner->books->isbn13 }}</p>
        
            <p>{{ $owner->books->PubrishedDate}}</p>
        </div>    
    </div>


@endsection
@section('footer')