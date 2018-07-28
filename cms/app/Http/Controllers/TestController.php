<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Owner;
use App\Book;
use App\Rental;
use App\User;
use App\Comment;
use Validator;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function mypagetest() {
        if(Auth::check()) {
            $owners = Owner::where('user_id', Auth::user()->id)->get();
            $rentals = Rental::where('user_id', Auth::user()->id)
            ->where('return_flag',1)
            ->where('return_day', '<', date("Y-m-d 00:00:00"))
            ->orderBy('updated_at', 'desc')
            ->get();
           
            return view('/mypage', [
            
                'owners' => $owners,
                'rentals' => $rentals,
                ]);
        }else{
            return redirect('/login');
        }
    }
    
    public function datetest() {
        $rentals = Rental::where('user_id', Auth::user()->id)
            ->where('return_flag',1)
            ->where('return_day', '>', date("Y-m-d 00:00:00"))
            ->orderBy('updated_at', 'desc')
            ->get();
            
            // dd($rentals);
            $aaa = date("Y-m-d 00:00:00");
            dd($aaa);
    }
}
