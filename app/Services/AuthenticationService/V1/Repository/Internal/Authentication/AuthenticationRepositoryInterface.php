<?php

namespace App\Services\AuthenticationService\V1\Repository\Internal\Authentication;

use App\Services\AuthenticationService\V1\Models\User;

interface AuthenticationRepositoryInterface
{
    public function adminLogin(array $parameters): array;

    public function appLogin(array $parameters): array;

    public function pickerLogin(array $parameters): array;

    public function managerLogin(array $parameters): array;

    public function check(array $parameters): ?User;

    public function fetch(array $parameters): User;

    public function appStore(array $parameters): array;

    public function logout(): bool;

    public function me(): User;

    public function changePassword(array $parameters): bool;

    public function appReset(array $parameters);

    public function adminReset(array $parameters);

    public function refresh(array $parameters): array;


}
