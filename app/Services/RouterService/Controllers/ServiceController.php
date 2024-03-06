<?php

namespace App\Services\RouterService\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RouterService\Models\Microservice;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceController extends Controller
{
    private mixed $user;

    public function reroute(Request $request, string $platform, string $version, string $service, string $any = null): Response|JsonResponse|Application|ResponseFactory
    {
        $method = Str::lower($request->getMethod());

        if ($method == 'options') {
            return response(content: 204);
        }

        $route = "$platform/v$version/$service/$any";

        $microservice = $this->findService($route);

        $url = str_replace($microservice['path'], $microservice['url'], $route);

        $response = $this->send($request, $url, $method);

        return response($response->body(), $response->status(), $response->headers());
    }

    public function rerouteWeb(Request $request, string $service, string $any = null): Response|JsonResponse|Application|ResponseFactory
    {
        $method = Str::lower($request->getMethod());

        if ($method == 'options') {
            return response(content: 204);
        }

        $route = "web/$service/$any";

        $microservice = $this->findService($route);

        $url = $microservice['url'] . '/' . $any;

        $response = $this->send($request, $url, $method);

        return response($response->body(), $response->status(), $response->headers());
    }

    public function rerouteMedia(Request $request, $any): Response|JsonResponse|Application|ResponseFactory
    {
        $microservice = $this->findService('web/media');

        $response = Http::baseUrl($microservice['url'])
            ->get($request->path());

        return response($response->body(), $response->status(), $response->headers());
    }

    private function findService(string $route): array
    {
        /** @var array $microservices */
        $microservices = Cache::rememberForever('microservices', function () {
            return Microservice::query()
                ->get(['path', 'url'])
                ->toArray();
        });

        $microservice = current(array_filter($microservices, function ($microservice) use ($route) {
            return str_starts_with($route, $microservice['path']);
        }));

        if (!$microservice) {
            throw new NotFoundHttpException();
        }

        return $microservice;
    }

    private function send(Request $request, string $url, string $method): ClientResponse
    {
        $this->user = $request->user();

        $httpRequest = Http::withHeaders([
            config('authenticate.user_id')         => $this->user?->id,
            config('authenticate.user_name')       => $this->user ? ($this->user->first_name . ' ' . $this->user->last_name) : null,
            config('authenticate.user_first_name') => $this->user?->first_name,
            config('authenticate.user_last_name')  => $this->user?->last_name,
        ]);

        $data = $request->all();
        if (count($request->allFiles()) > 0) {
            $files = $this->flatParameters($request->allFiles());

            /** @var UploadedFile $file */
            foreach ($files as $name => $file) {
                $httpRequest = $httpRequest->attach($name, $file->getContent(), $file->getClientOriginalName());
            }

            return $httpRequest->$method($url, $this->flatParameters($data));

        }

        return $httpRequest->$method($url, $data);

    }

    private function flatParameters(array $data, string $preKey = '')
    {
        $output = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $output = array_merge($output, $this->flatParameters($value, $preKey == "" ? $key : "$preKey" . "[" . $key . "]"));
            } else {
                $output[$preKey == "" ? $key : "$preKey" . "[" . $key . "]"] = $value;

            }
        }
        return $output;
    }
}
