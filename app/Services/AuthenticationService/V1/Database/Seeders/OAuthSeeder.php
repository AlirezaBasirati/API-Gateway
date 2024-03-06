<?php

namespace App\Services\AuthenticationService\V1\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $now = now();

        $data = [
            [
                'id'                     => '996eab5f-fdd8-4752-9300-42b63b7df2e7',
                'name'                   => 'Personal Access Client',
                'secret'                 => 'uwpzVCtYW1k8SJs7x5Tjz2dj3422c8dgWj1IdZ7X',
                'provider'               => null,
                'redirect'               => 'http://localhost',
                'personal_access_client' => 1,
                'password_client'        => 0,
                'revoked'                => 0,
                'created_at'             => $now,
                'updated_at'             => $now
            ],
            [
                'id'                     => '996eab60-07ad-487b-8d76-975892a9f33a',
                'name'                   => 'Password Grant Client',
                'secret'                 => 'w4mTqTKZyjLkJM1lmMko7PPBdSxvCOR6cgKRq7X7',
                'provider'               => 'users',
                'redirect'               => 'http://localhost',
                'personal_access_client' => 0,
                'password_client'        => 1,
                'revoked'                => 0,
                'created_at'             => $now,
                'updated_at'             => $now
            ],
        ];

        DB::table('oauth_clients')->insert($data);
    }
}
