<?php

namespace App\Providers;

use App\Models\Brands;
use App\Models\JwtTokens;
use App\Models\User;
use App\Observers\BrandsObserver;
use App\Observers\JwtTokensObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        JwtTokens::observe(JwtTokensObserver::class);
        Brands::observe(BrandsObserver::class);
    }
}
