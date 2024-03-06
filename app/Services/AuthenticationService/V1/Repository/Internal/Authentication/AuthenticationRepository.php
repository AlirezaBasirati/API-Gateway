<?php

namespace App\Services\AuthenticationService\V1\Repository\Internal\Authentication;

use App\Services\AuthenticationService\V1\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthenticationRepository implements AuthenticationRepositoryInterface
{
    /**
     * @param array $parameters
     * @return array
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function adminLogin(array $parameters): array
    {
        $user = $this->check($parameters);

        if (!$user->hasRoles('personnel', 'admin', 'store', 'merchant')) {
            throw new AuthorizationException();
        }

        $auth = $this->retrieveToken($parameters);

        return compact('user', 'auth');
    }


    /**
     * @param array $parameters
     * @return array
     * @throws ValidationException
     */
    private function retrieveToken(array $parameters): array
    {
        $data = [
            'client_id'     => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
            'grant_type'    => 'password',
            'username'      => $parameters['username'],
            'password'      => $parameters['password'],
            'scope'         => '*'
        ];

        return Http::asForm()
            ->baseUrl(env('PASSPORT_BASE_URL'))
            ->post('/oauth/token', $data)
            ->onError(fn() => throw ValidationException::withMessages([
                'password' => [__('authentication::authentication.password')]
            ]))
            ->json();
    }

    /**
     * @param array $parameters
     * @return array
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function appLogin(array $parameters): array
    {
        $parameters['role'] = 'customer';
        $parameters['criteria'] = 'app';

        return $this->createAppToken($parameters);
    }


    /**
     * @param array $parameters
     * @return array
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function pickerLogin(array $parameters): array
    {
        $parameters['role'] = 'picker';
        $parameters['criteria'] = 'picker';

        return $this->createAppToken($parameters);
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function managerLogin(array $parameters): array
    {
        $user = $this->check($parameters);

        $parameters['role'] = 'manager';
        $parameters['criteria'] = 'manager';

        $auth = $this->retrieveToken($parameters);

        return compact('user', 'auth');
    }

    /**
     * @param array $parameters
     * @return array
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function createAppToken(array $parameters): array
    {
        $user = $this->check($parameters);

        if (!$user->hasRoles($parameters['role'])) {
            throw new AuthorizationException();
        }

        if (!Hash::check($parameters['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => [__('authentication::authentication.password')]
            ]);
        }

        $auth['token'] = $user->createToken($parameters['criteria'])->accessToken;

        return compact('user', 'auth');
    }

    /**
     * @param array $parameters
     * @return null|User
     */
    public function check(array $parameters): ?User
    {
        /** @var User $user */
        $user = User::query()
            ->where('username', $parameters['username'])
            ->first();

        return $user;
    }

    /**
     * @param array $parameters
     * @return User
     */
    public function fetch(array $parameters): User
    {
        /** @var User $user */
        $user = User::query()
            ->where('id', $parameters['id'])
            ->firstOrFail();

        return $user;
    }

    public function logout(): bool
    {
        /** @var User $user */
        $user = Auth::user();
        return $user->tokens()->delete();
    }

    public function me(): User
    {
        /** @var User $user */
        $user = Auth::user();
        return $user;
    }

    public function appStore(array $parameters): array
    {
        DB::beginTransaction();

        /** @var User $user */
        $user = User::withTrashed()->updateOrCreate([
            'username' => $parameters['username']
        ], [
            'deleted_at' => null
        ]);

        $user->attachRoles(['customer']);

        $auth['token'] = $user->createToken('app')->accessToken;

        DB::commit();

        return compact('user', 'auth');
    }

    /**
     * @param array $parameters
     * @return bool
     * @throws ValidationException
     */
    public function changePassword(array $parameters): bool
    {
        /** @var User $user */
        $user = Auth::user();

        if (isset($parameters['current'])) {
            if (!Hash::check($parameters['current'], $user->password)) {
                throw ValidationException::withMessages([
                    'password' => [__('authentication::authentication.current-password-wrong')]
                ]);
            }
        }

        if (trim($parameters['password']) == '') {
            throw ValidationException::withMessages([
                'password' => [__('authentication::authentication.password-week')]
            ]);
        }

        $user->status = User::STATUS_ACTIVE;
        $user->password = Hash::make($parameters['password']);
        return $user->save();

    }

    /**
     * @param array $parameters
     * @return array
     * @throws AuthorizationException
     */
    public function appReset(array $parameters): array
    {
        $user = $this->check($parameters);

        if (!$user->hasRoles('customer')) {
            throw new AuthorizationException();
        }

        $user->status = User::STATUS_ACTIVE;
        $user->password = Hash::make($parameters['password']);
        $user->save();
        $auth['token'] = $user->createToken('app')->accessToken;

        return compact('user', 'auth');
    }

    /**
     * @param array $parameters
     * @return array
     * @throws ValidationException
     */
    public function AdminReset(array $parameters): array
    {
        $user = $this->check($parameters);

        $user->status = User::STATUS_ACTIVE;
        $user->password = Hash::make($parameters['password']);
        $user->save();

        $auth = $this->retrieveToken($parameters);

        return compact('user', 'auth');
    }


    /**
     * @param array $parameters
     * @return array
     * @throws ValidationException
     */
    public function refresh(array $parameters): array
    {
        $data = [
            'client_id'     => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
            'grant_type'    => 'refresh_token',
            'refresh_token' => $parameters['refresh_token'],
            'scope'         => '*'
        ];


        return Http::asForm()
            ->baseUrl(env('PASSPORT_BASE_URL'))
            ->post('/oauth/token', $data)
            ->onError(fn() => throw ValidationException::withMessages([
                'refresh_token' => [__('authentication::authentication.token')]
            ]))
            ->json();
    }
}
