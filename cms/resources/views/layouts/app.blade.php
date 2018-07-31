<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    
    <title>Document</title>
    <link rel="stylesheet" href="{{secure_asset('css/head.css')}}" type="text/css" />
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">    </style>
</head>
<body>
<div class="container">    
    <nav class="navbar navbar-default">
    
        <div class="container-fluid">
    
        <ul class="nav navbar-nav">
            <li class="active">
                <a href="#"><span class="glyphincon glyphincon-home"></span>Home</a>
            </li> 
            <li>
                <a href="#"><span class="glyphincon glyphincon-book"></span>Book register</a>
            </li>
            <li>
                <a href="#"><span class="glyphincon glyphincon-glass"></span>My page</a>
            </li>
        </ul>        
            
        <ul class="nav navbar-nav navbar-right">    
            @if (Auth::check()) 
            
            <!--<li>-->
            <!--    <span navbar-brand>facebookID:{{Auth::user()->facebook_id}}</span>-->
            <!--</li><br>-->

            <!--<li>-->
            <!--    <span navbar-brand><img class="avater" src="{{Auth::user()->avater}}"></img></span>-->
            <!--</li><br>-->
            <li>
                <span navbar-brand>ID:{{Auth::user()->id}}<br>{{Auth::user()->name}}さん</span>
            </li>
         
            <li>
                <a href="{{url('logout')}}">ログアウトする</a>
            @else
                <li><span navbar-brand>ゲストさん</span></li><br>
                <li><a href="{{url('facebook')}}">ログインする</a></li>
            @endif
        </ul>
        
        </div>
    </nav>    
 <div class="jumbotron">
     <h1>アワブク<small>our books</small></h1>
     <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;g's library</h2>
     

</div>

    @yield('content')
    
</div>
    
</body>
</html>