<?php

namespace App\Services\AuthorizationService\V1\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['sometimes', 'numeric'],
            'password' => ['sometimes','string','nullable'],
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['required', 'exists:roles,id'],
        ];
    }
}
