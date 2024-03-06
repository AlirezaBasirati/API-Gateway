<?php

namespace App\Services\AuthenticationService\V1\Requests\Internal;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'first_name' => ['required' . 'sometimes'],
            'last_name'  => ['required' . 'sometimes'],
        ];
    }
}
