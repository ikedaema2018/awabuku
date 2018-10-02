<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Scopes\LivingBookScope;

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
        
        $authUser = User::where('facebook_id', $fuser->id)->first();
        if($authUser){
            Auth::login($authUser, true);
            return redirect('/');
        }
        $authUser = User::create([
            'name' => $fuser->name,
            'facebook_id' => $fuser->id,
            'avater' => $fuser->avatar_original
            
            //createするのではなく、上記３点をredirectでviewページに飛ばして一括処理する。
        ]);
        
        Auth::login($authUser, true);
        return redirect('user_class/');
        
        // $authUser = $this->findOrCreateUser($fuser);
        // Auth::login($authUser, true);
        // return redirect('/');
    }
    
    // private function findOrCreateUser($facebookUser){
    //     $authUser = User::where('facebook_id', $facebookUser->id)->first();
    //     if($authUser){
    //         return $authUser;
    //     }
    //     return User::create([
    //         'name' => $facebookUser->name,
    //         'facebook_id' => $facebookUser->id,
    //         'avater' => $facebookUser->avatar_original
            
    //         //createするのではなく、上記３点をredirectでviewページに飛ばして一括処理する。
    //     ]);
    
    // }
    
    //ログアウトの処理
    public function logout() {
        Auth::logout();
        return redirect('/');
    }
    //user・class選択画面
       public function user_class() {
        if(Auth::check()) {
            $user = Auth::user()->get();
            return view('/user_class', [
            
                'user' => $user,
                
                ]);
        }else{
            return redirect('/login');
        }
    }
   
   //user・classを登録する
   public function user_class_insert(Request $request){
       if(Auth::check()){
                 $validate_rule = [         
                'class'       => 'required',
                ];
        
       $error_msg=[
                'class'       => '所属の情報が入力されていません',
                ];
         $validator = Validator::make($request->all(), $validate_rule, $error_msg);
       // バリデーション:エラー 
        if ($validator->fails()) {
                return redirect('/user_insert')
                    ->withInput()
                    ->withErrors($validator);
       }
   
　　　$user = new User;
　　　$user->class_id = $request->class;
　　　$user->save();
       }
   }
}
    