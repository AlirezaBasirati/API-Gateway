<?php

namespace App\Services\AuthenticationService\V1\Requests\Internal;

use Illuminate\Foundation\Http\FormRequest;

class AppChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }
}
