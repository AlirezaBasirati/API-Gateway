<?php

namespace App\Services\AuthenticationService\V1\Providers;

use App\Services\AuthenticationService\V1\Middlewares\Authentication;
use App\Services\AuthenticationService\V1\Middlewares\MeshAuthentication;
use App\Services\AuthenticationService\V1\Repository\Internal\Authentication\AuthenticationRepository;
use App\Services\AuthenticationService\V1\Repository\Internal\Authentication\AuthenticationRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AuthenticationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthenticationRepositoryInterface::class, AuthenticationRepository::class);
    }

    public function boot()
    {
        app('router')->aliasMiddleware('mesh-authentication', MeshAuthentication::class);
        app('router')->aliasMiddleware('authentication', Authentication::class);
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__.'/../Language', 'authentication');
    }
}
