<?php

namespace App\Services\SAPService\Repository;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SAPServiceInterface
{
    public function sendOrder($data): array;
}
