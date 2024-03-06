<?php

namespace App\Services\AuthorizationService\V1\Console\Commands;

use App\Services\RouterService\Models\Microservice;
use Celysium\Permission\Models\Permission;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchServiceRoutes extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:fetch-service-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all services routes';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Microservice::query()
            ->where('path', 'like', 'web/%')
            ->chunk(100, function ($query) {
                foreach ($query as $service) {
                    $data = [];

                    $serviceName = \Str::after($service->path, 'web/');


                    try {
                        $exceptRoutes['except'] = [
                            'l5-swagger',
                            'sanctum',
                            'ignition',
                            'routes'
                        ];

                        $url = $service->url . '/api/routes?' . http_build_query($exceptRoutes);

                        $request = Http::timeout(5)->get($url);
                    } catch (\Exception $exception) {
                        continue;
                    }


                    if ($request->successful()) {
                        $routes = $request->json();
                        $routeNames = array_keys($request->json());

                        DB::beginTransaction();

                        $checkRoute = Permission::query()
                            ->where('service', $serviceName)
                            ->get(['name', 'id'])
                            ->pluck('name', 'id')->toArray();

                        $createRoutes = array_diff($routeNames, $checkRoute);

                        foreach ($createRoutes as $route) {
                            $data[] = [
                                'name'    => $route,
                                'service' => $serviceName,
                                'route'   => json_encode(
                                    [
                                        'url' => $routes[$route]['url'],
                                        'methods' => $routes[$route]['methods']
                                    ]
                                ),
                            ];
                        }


                        Permission::query()->insert($data);

                        $deleteRoutes = array_diff($checkRoute, $routeNames);

                        Permission::query()
                            ->whereIn('id', array_keys($deleteRoutes))
                            ->delete();

                        DB::commit();
                    }
                }
            });
    }
}
