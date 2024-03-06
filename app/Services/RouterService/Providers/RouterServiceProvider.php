<?php

namespace App\Services\RouterService\Providers;

use App\Services\RouterService\Commands\SeedMicroservice;
use Illuminate\Support\ServiceProvider;

class RouterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->commands([
            SeedMicroservice::class
        ]);
    }
}
