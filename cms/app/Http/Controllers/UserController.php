<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
//facebookログイン用
use Laravel\Socialite\Contracts\Factory as Socialite;

class UserController extends Controller
{
    
    //ログインページへ
    public function login_view(){
        return view('login');
    }
    
        //コンストラクタ
    public function __construct(Socialite $socialite)
    {
        $this->socialite = $socialite;
    }

    //facebookログイン
    public function loginFacebook()
    {
        //facebookへリダイレクト
        return $this->socialite->driver('facebook')->redirect();
    }

    //コールバック
    public function facebookCallback()
    {
        //ユーザー情報を取得
        try {
            $fuser = \Socialite::with('facebook')->user();
        } catch (Exception $e) {
            return redirect('facebook');
        }
        
        $authUser = $this->findOrCreateUser($fuser);
        Auth::login($authUser, true);
        return redirect('/');
    }
    
    private function findOrCreateUser($facebookUser){
        $authUser = User::where('facebook_id', $facebookUser->id)->first();
        if($authUser){
            return $authUser;
        }
        return User::create([
            'name' => $facebookUser->name,
            'facebook_id' => $facebookUser->id,
            'avater' => $facebookUser->avatar_original
        ]);
    
    }
    
    //ログアウトの処理
    public function logout() {
        Auth::logout();
        return redirect('/');
    }
    
    
        
        
    
}
    