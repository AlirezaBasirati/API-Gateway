<?php

namespace App\Services\AuthorizationService\V1\Providers;

use App\Services\AuthorizationService\V1\Console\Commands\FetchServiceRoutes;
use App\Services\AuthorizationService\V1\Middleware\AddRolesToHeaderMiddleware;
use App\Services\AuthorizationService\V1\Repository\V1\Admin\Authorization\AuthorizationRepository;
use App\Services\AuthorizationService\V1\Repository\V1\Admin\Authorization\AuthorizationRepositoryInterface;
use App\Services\AuthorizationService\V1\Repository\V1\Admin\User\UserRepository;
use App\Services\AuthorizationService\V1\Repository\V1\Admin\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthorizationRepositoryInterface::class, AuthorizationRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function boot()
    {
        app('router')->aliasMiddleware('user-roles', AddRolesToHeaderMiddleware::class);
        app('router')->pushMiddlewareToGroup('api', 'user-roles');

        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        if ($this->app->runningInConsole()) {
            $this->commands(FetchServiceRoutes::class);
        }
}
}
