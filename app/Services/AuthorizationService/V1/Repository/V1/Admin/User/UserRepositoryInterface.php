<?php

namespace App\Services\AuthorizationService\V1\Repository\V1\Admin\User;

use App\Services\AuthenticationService\V1\Models\User;
use Celysium\Base\Repository\BaseRepositoryInterface;


interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function storeBatch(array $parameters): array;
}
