<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    
    <title>アワブク</title>
    <link rel="stylesheet" href="{{asset('css/thread_page.css')}}">
    <link rel="stylesheet" href="{{asset('css/book_register.css')}}">
    
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
    <link rel="stylesheet" href="{{asset('css/mypage.css')}}">
    <link rel="stylesheet" href="{{asset('css/category_genre_page.css')}}">
    <link rel="stylesheet" href="{{asset('css/category_page.css')}}">
    <link rel="stylesheet" href="{{asset('css/thread_page.css')}}">
    <link rel="stylesheet" href="{{asset('css/threads.css')}}">
    
    <link rel="stylesheet" href="{{secure_asset('css/head.css')}}" type="text/css" />
    
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->

    
    <!-- Optional theme -->

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    

</head>
<body>
    
 

    <div class="container-fluid">    
    
    
    <header>
    <div class="row" style="display:table; width:100%; margin:5px auto;"">    
        <div class="col-sm-6 text-left" style="display:table-cell;width:50%;"> 
            <div class="titile_wrappert" > 
                <h2>Our Books</h2>
            </div>
        </div>
        <div class="col-sm-6 text-right search" style="display:table-cell;width:50%;">
            <div class="input-group serach_box">
              <input type="text" class="form-control">
              <span class="input-group-btn">
                <button class="btn btn-default" type="submit">
                  <i class='glyphicon glyphicon-search'></i>
                </button>
              </span>
            </div>  
        </div>    
    </div>
      
    
        <nav class="navbar navbar-default">
            <div class="container-fluid">
            <div class="navbar-header">
                <a href="{{url(' ')}}" class="navbar-brand">g's library</a>
            </div>
            
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="{{url(' ')}}"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Home</a>
                </li> 
                <li>
                    <a href="{{url('book')}}"><span class="glyphicon glyphicon-book" aria-hidden="true"></span>本を登録する</a>
                </li>
                <li>
                    <a href="{{url('mypage')}}"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>マイページ</a>
                </li>
                <li>
                    <a href="{{url('threads')}}"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></span>スレッド</a>
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
                    <li style="display:none">
                        <p　style="display:none">ID:{{Auth::user()->id}}<br>{{Auth::user()->name}}さん</p>
                    </li>    
                    <li>
                        <span navbar-brand><img class="avater img-circle" src="{{Auth::user()->avater}}"></img></span>                    
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


   
    
</body>
</html>