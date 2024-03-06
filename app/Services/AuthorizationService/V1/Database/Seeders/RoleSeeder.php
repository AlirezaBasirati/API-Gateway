<?php

namespace App\Services\AuthorizationService\V1\Database\Seeders;

use Celysium\Permission\Enumerations\RoleStatus;
use Celysium\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'     => 1,
                'title'  => 'مشتری',
                'name'   => 'customer',
                'status' => RoleStatus::ACTIVE->value,
            ],
            [
                'id'     => 2,
                'title'  => 'مدیریت',
                'name'   => 'admin',
                'status' => RoleStatus::ACTIVE->value,
            ],
            [
                'id'     => 3,
                'title'  => 'مارکت',
                'name'   => 'vendor',
                'status' => RoleStatus::ACTIVE->value,
            ],
            [
                'id'     => 4,
                'title'  => 'فروشنده',
                'name'   => 'merchant',
                'status' => RoleStatus::ACTIVE->value,
            ],
            [
                'id'     => 5,
                'title'  => 'جمع کننده محصول در فروشگاه',
                'name'   => 'picker',
                'status' => RoleStatus::ACTIVE->value,
            ],
            [
                'id'     => 6,
                'title'  => 'مدیر فروشگاه',
                'name'   => 'manager',
                'status' => RoleStatus::ACTIVE->value,
            ],
        ];
        foreach ($roles as $role) {
            Role::query()->updateOrCreate([
                'title'  => $role['title'],
                'name'   => $role['name'],
                'status' => $role['status'],
            ]);
        }
    }
}
