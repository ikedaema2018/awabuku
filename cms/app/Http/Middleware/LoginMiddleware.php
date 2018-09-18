<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($r, Closure $next)
    {
       if(Auth::check()) {
            $user = Auth::user();
            $r->merge(['user'=>$user]);
        }else{
            
        return redirect('/login');  
        }
        
        return $next($r);  
     
    }
}
