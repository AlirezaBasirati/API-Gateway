<?php

namespace App\Services\AuthorizationService\V1\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreBatchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role_id'   => ['required', 'exists:roles,slug'],
            'users' => ['required', 'array', 'min:1'],
            'users.*.id' => ['required', 'sometimes', 'int'],
            'users.*.username' => ['required', 'sometimes', 'string'],
        ];
    }
}
