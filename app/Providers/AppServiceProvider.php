<?php

namespace App\Providers;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\JwtTokens;
use App\Models\OrderStatuses;
use App\Models\Payments;
use App\Models\User;
use App\Observers\BrandsObserver;
use App\Observers\CategoriesObserver;
use App\Observers\JwtTokensObserver;
use App\Observers\OrderStatusesObserver;
use App\Observers\PaymentsObserver;
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
        Categories::observe(CategoriesObserver::class);
        OrderStatuses::observe(OrderStatusesObserver::class);
        Payments::observe(PaymentsObserver::class);
    }
}
