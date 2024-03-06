<?php

namespace App\Services\DMService\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route as RouteFacade;

class DMServiceRepository implements DMServiceInterface
{
    public function sendOrder($data, $shouldMap = true): array
    {
        if($shouldMap)
            $data = $this->mapOrderData($data);

        $response = Http::post(env('DM_BASE_URL'). '/integration/parcels/new', $data);

        return [
            'body' => $response->body(),
            'status' => $response->status(),
        ];
    }

    private function mapOrderData($data)
    {
        return $data;
    }
}
