<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\CustomRequest;

class LoginRequest extends CustomRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'max:50'],
        ];
    }
}
