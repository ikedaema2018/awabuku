<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    
    <title>アワブク</title>
    <link rel="stylesheet" href="{{secure_asset('css/head.css')}}" type="text/css" />
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
    

</head>
<body>
    
 

    <div class="container">    
    
    
    <header>
      <h2>アワブク<small>Our Books</small></h2>
      <h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp~g's library~</h4>
        <nav class="navbar navbar-default">
        
            <div class="container-fluid">
        
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="{{url(' ')}}"><span class="glyphincon glyphincon-home"></span>Home</a>
                </li> 
                <li>
                    <a href="{{url('book')}}"><span class="glyphincon glyphincon-book"></span>本を登録する</a>
                </li>
                <li>
                    <a href="{{url('mypage')}}"><span class="glyphincon glyphincon-glass"></span>マイページ</a>
                </li>
                <li>
                    <a href="{{url('thread')}}"><span class="glyphincon glyphincon-glass"></span>スレッド</a>
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
    </header>
    
        @yield('content')
        
    </div>


   
   
    <!--<script src="js/bootstrap.min.js"></script>-->
    
</body>
</html>