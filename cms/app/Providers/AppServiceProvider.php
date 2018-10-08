<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
     if (isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR'])) {
            $request = \Request::instance();
            $request->server->set('HTTPS', "on");

            $_SERVER['HTTPS'] = 'on';
            $_ENV['HTTPS'] = 'on';
        }  
       
       
        Schema::defaultStringLength(191);
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
