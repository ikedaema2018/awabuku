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
    <link rel="stylesheet" href="{{asset('css/mypage_detail.css')}}">
    <link rel="stylesheet" href="{{asset('css/category_genre_page.css')}}">
    <link rel="stylesheet" href="{{asset('css/category_page.css')}}">
    <link rel="stylesheet" href="{{asset('css/thread_page.css')}}">
    <link rel="stylesheet" href="{{asset('css/threads.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-social.css')}}">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    
    
    <link rel="stylesheet" href="{{secure_asset('css/head.css')}}" type="text/css" />
    
    
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
    <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
    
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    
    <!-- Optional theme -->

    <!-- Latest compiled and minified JavaScript -->
    
    <!--fontaweome-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    

</head>
<body>
    
 

    
    
    <header>
        
        
    <div style="display:flex; width:100%; margin:5px auto;" class="head_wrapper">    
        <div class="col-xs-12 col-sm-6" style="display:table-cell;width:50%;"> 
            <div class="titile_wrappert" > 
                <h2>Our Books</h2>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 search" style="display:table-cell;">
        
          <form class="form-inline" action="{{url('/search')}}">
            <div class="input-group serach_box col-xs-12">
          
              <input type="text" class="form-control keyword_search" name="keyword" value="" placeholder="キーワードを入力してください。" >
              <span class="input-group-btn">
                <button type="submit"class="btn btn-info" name="search_button">
                  <i class='glyphicon glyphicon-search'></i></button>
                
              </span>
           </div>  
          </form>
            
        </div>    
    </div>

        <nav class="navbar navbar-default"style="background-color: #00CCCC;">
          <div class="container-fluid">


            <div class="navbar-header">
            
            <!--スマホ用トグルボタンの設置-->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".target">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            
            <!--ロゴ表示の指定-->
           
          </div>
            
            
            <div class="container-fluid">
             <div class="collapse navbar-collapse target">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="{{url(' ')}}"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Home</a>
                    </li> 
                    <li>
                        <a href="{{url('gsbooks')}}">g's library</a>
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
    


    
        
        
  
</body>
<footer>
<div class="row">
  <div class="col-lg-12">
    <address>Copyright(C)OurBooks,Allright Reserved.</address>
    @section('footer')
   @show
 </div>
</div>
</html>