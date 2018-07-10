@extends('layouts.app')

@section('content')

<p>{{Auth::user()->facebook_id}}</p>
<p>{{Auth::user()->name}}</p>
<img src="{{Auth::user()->avater}}"></img>


@endsection