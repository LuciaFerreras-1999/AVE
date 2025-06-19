<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'phone' => ['nullable', 'regex:/^[0-9]+$/'],
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ];
    }
}
