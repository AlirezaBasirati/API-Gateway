<?php

namespace App\Services\DMService\Repository;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DMServiceInterface
{
    public function sendOrder($data): array;
}
