<?php

namespace Database\Seeders;

use App\Services\AuthenticationService\V1\Database\Seeders\OAuthSeeder;
use App\Services\AuthenticationService\V1\Database\Seeders\UserSeeder;
use App\Services\AuthorizationService\V1\Database\Seeders\RoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(OAuthSeeder::class);
    }
}
