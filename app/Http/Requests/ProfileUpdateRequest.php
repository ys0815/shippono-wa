<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'display_name' => ['required', 'string', 'max:255'],
            'sns_x' => ['nullable', 'string', 'max:255'],
            'sns_instagram' => ['nullable', 'string', 'max:255'],
            'sns_facebook' => ['nullable', 'string', 'max:255'],
        ];
    }
}
