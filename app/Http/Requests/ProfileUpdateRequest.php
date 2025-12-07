<?php

namespace App\Http\Requests;

use App\Models\Akun;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        
        return [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('akun', 'username')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('akun', 'email')->ignore($user->id),
            ],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}
