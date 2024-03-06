<?php

namespace App\Services\AuthorizationService\V1\Repository\V1\Admin\User;

use App\Services\AuthenticationService\V1\Models\User;
use Celysium\Base\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        return parent::__construct($model);
    }

    public function conditions(Builder $query): array
    {
        return [
            'username' => fn($value) => $query->where('username', 'LIKE', "%$value%")
        ];
    }

    public function storeBatch(array $parameters): array
    {
        $users = [];
        foreach ($parameters['users'] as $items) {
            $parameters = [
                'id'       => $items['id'] ?? null,
                'username' => $items['username'],
            ];
            /** @var User $user */
            $user = $this->find($parameters['id']);
            if (!$user) {
                $user = parent::store($parameters);
            }

            $user->attachRoles([$parameters['role_id']]);

            $users[] = $user;
        }

        return $users;
    }

    public function store(array $parameters): Model
    {
        if (isset($parameters['password']) && trim($parameters['password']) != '') {
            $parameters['password'] = Hash::make(trim($parameters['password']));
        }
        else {
            unset($parameters['password']);
        }

        /** @var User $user */
        $user = $this->model->query()
            ->where('username', $parameters['username'])
            ->first();

        if (!$user) {
            $user = parent::store($parameters);
        }

        $user->attachRolesById($parameters['roles']);

        return $user->refresh();
    }

    public function update(Model $model, array $parameters): Model
    {
        if (isset($parameters['password']) && trim($parameters['password']) != '') {
            $parameters['password'] = Hash::make(trim($parameters['password']));
        }
        else {
            unset($parameters['password']);
        }

        if(array_key_exists('roles', $parameters) && count($parameters['roles'])) {
            /** @var User $model */
            $model->roles()->sync($parameters['roles']);
        }
        return parent::update($model, $parameters);
    }
}
