<?php

namespace App\Http\Requests\User;

use App\Http\Requests\CustomRequest;

class UserUpdateRequest extends CustomRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => ['string'],
            'last_name' => ['string'],
            'email' => ['email'],
            'image' => ['file', 'mimes:png,jpg,jpeg'],
        ];
    }
}
