<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrendaRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'talla' => 'required|string|max:5',
            'marca' => 'nullable|string|max:255',
            'estado' => 'required|in:nuevo,usado',
            'categorias' => 'required|array|min:1',
            'categorias.*' => 'exists:categorias,id',
            'imagen' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
