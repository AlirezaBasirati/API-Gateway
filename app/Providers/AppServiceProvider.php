<?php

namespace App\Providers;

use Celysium\Responser\Exceptions\Handler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Handler::class
        );
    }
}
