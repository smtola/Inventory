<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Models\User;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        User::observe(UserObserver::class);
    }
}
