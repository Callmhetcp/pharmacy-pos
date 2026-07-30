<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use App\View\Composers\NotificationComposer;

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
    Paginator::useBootstrapFive();

    View::composer('*', function ($view) {

        $view->with('setting', Setting::first());

    });
     View::composer('*', NotificationComposer::class);
}
}
