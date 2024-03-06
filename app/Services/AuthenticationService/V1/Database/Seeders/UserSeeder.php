<?php

namespace App\Services\AuthenticationService\V1\Database\Seeders;

use App\Services\AuthenticationService\V1\Models\User;
use Celysium\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /** @var User $adminUser */
        $adminUser = User::query()
            ->create([
                'username' => 'admin@email.com',
                'first_name' => 'admin',
                'last_name' => 'admin',
                'password' => Hash::make('admin'),
            ]);

        $adminUser->attachRoles(['admin']);
    }
}
