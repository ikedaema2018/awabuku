<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Group;


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
        return redirect('group_class/');
        
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
       public function user_group() {
        if(Auth::check()) {
            $user = Auth::user()->get();
            $groups = Group::all();
            return view('/user_group', [
                'user' => $user,
                'groups'=>$groups,
                ]);
        }else{
            return redirect('/login');
        }
    }
   
  //user・classを登録する
  public function user_group_insert(Request $request){
                 $validate_rule = [         
                'group'       => 'required',
                ];
        
      $error_msg=[
                'group'       => '所属の情報が入力されていません',
                ];
         $validator = Validator::make($request->all(), $validate_rule, $error_msg);
      // バリデーション:エラー 
        if ($validator->fails()) {
                return redirect('/user_group')
                    ->withInput()
                    ->withErrors($validator);
      }
   
       $user = User::find(Auth::user()->id);
       $user->group_id = $request->group;
       $user->save();
       
       return redirect('/');
  }
  
    public function user_page() { 
     $groups= Group::all();
       return view('user_page', [
              'groups' =>$groups,
        ]);
    }
  
  
  
}
    