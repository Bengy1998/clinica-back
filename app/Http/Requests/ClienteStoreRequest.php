<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClienteStoreRequest extends FormRequest
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
            'correo'  => 'required|email',
            // Agrega más validaciones para otros campos según tu modelo
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string'   => 'El campo nombre debe ser una cadena de texto.',
            'nombre.max'      => 'El campo nombre no debe exceder 255 caracteres.',
            'correo.required'  => 'El campo correo electrónico es obligatorio.',
            'correo.email'     => 'El campo correo electrónico debe ser una dirección válida.',
            // Agrega más mensajes personalizados según tus validaciones
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors'  => $validator->errors()
                ],
                400
            )
        );
    }
}
