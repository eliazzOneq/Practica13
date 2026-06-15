<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // o Gate::allows('crear-producto')
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

    public function messages(): array { 
        return [
            'nombre.required'   => 'El nombre del producto es obligatorio.', 
            'nombre.min'        => 'El nombre debe tener al menos :min caracteres.', 
            'precio.required'   => 'Debes indicar un precio.', 
            'precio.min'        => 'El precio debe ser mayor a cero.', 
            'stock.integer'     => 'El stock debe ser un número entero.', 
            'categoria_id.exists' => 'La categoría seleccionada no existe.', 
            'imagen.mimes'      => 'La imagen debe ser JPG, PNG o WEBP.', 
            'imagen.max'        => 'La imagen no puede superar 2 MB.', 
        ]; 
    } 
}
