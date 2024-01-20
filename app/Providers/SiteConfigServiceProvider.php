<?php

namespace App\Providers;

use App\Models\SiteConfig;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class SiteConfigServiceProvider extends ServiceProvider
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
        $configurations = SiteConfig::pluck('value','key')->toArray();
            Config::set('site',$configurations);
    }
}
