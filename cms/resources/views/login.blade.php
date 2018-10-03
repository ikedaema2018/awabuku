@extends('layouts.app')

@section('content')





<div class="row" style="margin-top:70px;">
<div class="col-sm-8 col-sm-offset-4">
<p>facebookアカウントでログインしてください。</p>

  <a href="{{url('/facebook')}}" class="btn btn-social btn-facebook col-sm-4" >
    <span><i class="fab fa-facebook-square"></i></span> Sign in with Facebook
    
  </a>
</div>
</div>


@endsection