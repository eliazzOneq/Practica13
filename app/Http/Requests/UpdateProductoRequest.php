<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre'       => 'required|string|min:3|max:100',
            'descripcion'  => 'nullable|string|max:500',
            'precio'       => 'required|numeric|min:0.01|max:99999',
            'stock'        => 'required|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagen'       => 'nullable|image|mimes:jpg,png,webp|max:2048',
        ];
    }
}
