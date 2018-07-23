<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{secure_asset('css/head.css')}}" type="text/css" />
        
    </style>
</head>
<body>
    <div class="header">
        @if (Auth::check())
        <span>名前:{{Auth::user()->name}}さん</span>
        <span>facebookID:{{Auth::user()->facebook_id}}</span>
        <span>ID:{{Auth::user()->id}}</span>
        <span><img class="avater" src="{{Auth::user()->avater}}"></img></span>
        <a href="{{url('logout')}}">ログアウトする</a>
        @else
        <span>ゲストさん</span>
        <a href="{{url('facebook')}}">ログインする</a>
        
        @endif
    </div>
    @yield('content')
</body>
</html>