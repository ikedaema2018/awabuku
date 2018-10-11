@extends('layouts.app')

@section('content')





<div class="row" style="margin-top:50px;">
<div style="text-align:center;">
<p>facebookアカウントでログインしてください。</p>

  <a href="{{url('/facebook')}}" class="btn btn-social btn-facebook" style="width:300px;">
    <span><i class="fab fa-facebook-square"></i></span> Sign in with Facebook
  </a>
</div>
</div>


@endsection