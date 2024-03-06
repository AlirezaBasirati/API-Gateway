<?php

namespace App\Services\DMService\Providers;

use App\Services\DMService\Repository\DMServiceInterface;
use App\Services\DMService\Repository\DMServiceRepository;
use Illuminate\Support\ServiceProvider;

class DMServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(DMServiceInterface::class, DMServiceRepository::class);
    }

    public function boot()
    {
    }
}
