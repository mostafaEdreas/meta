<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define("changImg", function ($user,$userWillUpdate) {
            dd([$userWillUpdate,$user]);
         return $userWillUpdate->id;
        });
      
    }
}
