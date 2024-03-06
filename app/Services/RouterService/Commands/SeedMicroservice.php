<?php

namespace App\Services\RouterService\Commands;

use App\Services\RouterService\Models\Microservice;
use Illuminate\Console\Command;

class SeedMicroservice extends Command
{
    protected $signature = 'seed:microservice {host : host address} {port? : port number}';
    protected $description = 'Seed microservices table';

    public function handle(): void
    {
        Microservice::query()->truncate();

        $host = $this->argument('host');

        $items = [
            ['path' => 'app/v1/account', 'url' => "$host:9000/api/app/v1"],
            ['path' => 'admin/v1/account', 'url' => "$host:9000/api/admin/v1"],
            ['path' => 'internal/v1/account', 'url' => "$host:9000/api/internal/v1"],
            ['path' => 'picker/v1/account', 'url' => "$host:9000/api/picker/v1"],
            ['path' => 'store/v1/account', 'url' => "$host:9000/api/store/v1"],
            ['path' => 'web/account', 'url' => "$host:9000"],

            ['path' => 'app/v1/content', 'url' => "$host:9001/api/app/v1"],
            ['path' => 'admin/v1/content', 'url' => "$host:9001/api/admin/v1"],
            ['path' => 'internal/v1/content', 'url' => "$host:9001/api/internal/v1"],
            ['path' => 'web/content', 'url' => "$host:9001"],

            ['path' => 'app/v1/inventory', 'url' => "$host:9002/api/app/v1"],
            ['path' => 'admin/v1/inventory', 'url' => "$host:9002/api/admin/v1"],
            ['path' => 'store/v1/inventory', 'url' => "$host:9002/api/store/v1"],
            ['path' => 'internal/v1/inventory', 'url' => "$host:9002/api/internal/v1"],
            ['path' => 'web/inventory', 'url' => "$host:9002"],

            ['path' => 'app/v1/order', 'url' => "$host:9003/api/app/v1"],
            ['path' => 'admin/v1/order', 'url' => "$host:9003/api/admin/v1"],
            ['path' => 'store/v1/order', 'url' => "$host:9003/api/store/v1"],
            ['path' => 'picker/v1/order', 'url' => "$host:9003/api/picker/v1"],
            ['path' => 'internal/v1/order', 'url' => "$host:9003/api/internal/v1"],
            ['path' => 'external/v1/order', 'url' => "$host:9003/api/external/v1"],
            ['path' => 'web/order', 'url' => "$host:9003"],

            ['path' => 'internal/v1/media', 'url' => "$host:9004/api/internal/v1"],
            ['path' => 'web/media', 'url' => "$host:9004"],

            ['path' => 'app/v1/marketing', 'url' => "$host:9005/api/app/v1"],
            ['path' => 'admin/v1/marketing', 'url' => "$host:9005/api/admin/v1"],
            ['path' => 'internal/v1/marketing', 'url' => "$host:9005/api/internal/v1"],
            ['path' => 'web/marketing', 'url' => "$host:9005"],

            ['path' => 'app/v1/payment', 'url' => "$host:9006/api/app/v1"],
            ['path' => 'admin/v1/payment', 'url' => "$host:9006/api/admin/v1"],
            ['path' => 'internal/v1/payment', 'url' => "$host:9006/api/internal/v1"],
            ['path' => 'web/payment', 'url' => "$host:9006"],
        ];

        foreach ($items as $item) {
            Microservice::query()->create([
                'path' => $item['path'],
                'url'  => $item['url']
            ]);
        }
    }
}
