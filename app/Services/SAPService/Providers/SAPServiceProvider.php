<?php

namespace App\Services\SAPService\Providers;

use App\Services\SAPService\Repository\SAPServiceInterface;
use App\Services\SAPService\Repository\SAPServiceRepository;
use Illuminate\Support\ServiceProvider;

class SAPServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SAPServiceInterface::class, SAPServiceRepository::class);
    }

    public function boot()
    {
    }
}
