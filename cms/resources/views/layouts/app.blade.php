<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    
    <title>OurBooks</title>
    
    <!--bootstrap-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    


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
    <link rel="stylesheet" href="{{asset('css/gsbook.css')}}">    
    <link rel="stylesheet" href="{{asset('css/book_comment.css')}}">
    <link rel="stylesheet" href="{{asset('css/key.css')}}">    
    <link rel="stylesheet" href="{{asset('css/head.css')}}">
    
    <!--<link rel="stylesheet" href="{{secure_asset('css/head.css')}}" type="text/css" />-->
 
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
    <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
  



    
<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    
<!-- Optional theme -->

<!-- Latest compiled and minified JavaScript -->
    
<!--fontaweome-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script type="text/javascript">
        var DECODER_WORKER_URL = "{{asset('js/DecoderWorker.js')}}";
        var EXIFJS_URL = "{{asset('js/exif.js')}}";
    </script>
    <script type="text/javascript" src="{{asset('js/JOB.js')}}"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
  <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

</head>
<body>
    
 

    
    
    <header>
        
        
    <div style="display:flex; width:100%; margin:5px auto;" class="head_wrapper">    
        <div class="col-xs-12 col-sm-6" style="display:table-cell;width:50%;"> 
            <div class="titile_wrapper" > 
                <h2><img src="{{asset('img/OurBooks.svg')}}" style="width: 300px; height: 40px;"></h2>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6" style="display:table-cell;position: relative;text-align:right;" >
          @if (Auth::check()) 
          <ul style="list-style-type:none; display: flex; justify-content: space-between;">
                    <li >
                        <a href="{{url('mypage')}}"><p　style="display:none">{{Auth::user()->name}}さん</p></a>
                    </li>    
                    <li >
                        <a href="{{url('book')}}"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> 本を登録する</a>
                    </li>
                    <li >
                        <a href="{{url('logout')}}">ログアウトする</a>
                    </li>
                @else
                    <li ><span navbar-brand>ゲストさん</span></li><br>
                    <li >><a href="{{url('facebook')}}">ログインする</a></li>
                @endif 
          </ul>        
        </div>    
    </div>


<!--navbarの表示 -->
        <nav class="navbar navbar-default" >
          <div class="container-fluid" style="background-color: #343a40;">


            <div class="navbar-header">
            
                <!--スマホ用トグルボタンの設置-->
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".target">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
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
                    <!--<li>-->
                    <!--    <a href="{{url('book')}}"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> 本を登録する</a>-->
                    <!--</li>-->
                    <!--<li>-->
                    <!--    <a href="{{url('mypage')}}"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> マイページ</a>-->
                    <!--</li>-->
                    <li>
                        <a href="{{url('threads')}}"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> スレッド</a>
                    </li>  
                    <li>
                        <a href="{{url('user_page')}}"><img src="{{asset('img/group.png')}}" style="width: 20px; height: 20px;"><span> メンバー<span></a>
 
                    </li> 
                 
                </ul> 
   
            <ul class="nav navbar-nav navbar-right">   
            <li>
            <form class="form-inline my-2 my-lg-0" action="{{url('/search')}}">
                  <input class="form-control mr-sm-2" type="search" placeholder="キーワードを入力してください。" aria-label="Search"name="keyword" value="">
                  <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Search</button>
               
            </li>  
             </ul>
    </form>
            
            
            
            
            
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