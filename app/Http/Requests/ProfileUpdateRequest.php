<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nickname' => [
                'required',
                'string',
                'max:50',
                'min:3',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    /**
     * Custom error messages (Bahasa Indonesia)
     */
    public function messages(): array
    {
        return [
            'nickname.required' => 'Nickname tidak boleh kosong.',
            'nickname.min'      => 'Nickname minimal 3 karakter.',
            'nickname.max'      => 'Nickname maksimal 50 karakter.',
            'nickname.unique'   => 'The nickname has already been taken.',
        ];
    }
}
