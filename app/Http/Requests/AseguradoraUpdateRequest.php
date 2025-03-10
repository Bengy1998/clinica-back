<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AseguradoraUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'nombre' => 'nullable|string|max:255',
            'ruc' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'empresa_id' => 'nullable|integer',
        ];
    }

     public function messages(): array
    {
        return [

            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.string' => 'El RUC debe ser una cadena de texto.',
            'ruc.max' => 'El RUC no puede tener más de 50 caracteres.',
            'ruc.unique' => 'El RUC ya está registrado.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no puede tener más de 50 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'empresa_id.required' => 'El ID de la empresa es obligatorio.',
            'empresa_id.integer' => 'El ID de la empresa debe ser un número entero.',
            'empresa_id.exists' => 'La empresa seleccionada no existe.',
        ];
    }


}
