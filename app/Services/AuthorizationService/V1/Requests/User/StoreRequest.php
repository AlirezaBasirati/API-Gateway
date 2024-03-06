<?php

namespace App\Services\AuthorizationService\V1\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'sometimes', 'string'],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'exists:roles,id'],
        ];
    }
}
