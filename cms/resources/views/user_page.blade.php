@extends('layouts.app')

@section('content')

<?php

use App\User;
use App\Book;
use App\Category;
use App\Tag;
?>



  
<?php $i=0?>
   @if(count($groups)>0)
     @foreach($groups as $group)
      
          @foreach($group->users as $user)
          <h2><b>{{$group->group_name}}</b></h2>
             @if($i==0)
              <div class="col-sm-12 border_bottom">
             @endif
                 <div class= "col-sm-2">
                  <ul class="sample">
                   <li>
                     <a href="{{url('user_search_page/'.$user->id)}}"><span navbar-brand><img class="avater img-circle" src="{{$user->avater}}"></img></span><br>
                     <a href="{{url('user_search_page/'.$user->id)}}">{{$user->name}}</a><br>
                     <p>{{$user->group->group_name}}</p>
                   </li>          
                  </ul>
                 </div>
               <?php $i=$i+1?>
               @if($i == 6 || $loop->last)
               <?php $i=0 ?>
        　    </div> 
        　      @endif
        　 @endforeach
    @endforeach
  @endif             
    


@endsection