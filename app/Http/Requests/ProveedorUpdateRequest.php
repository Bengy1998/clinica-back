<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProveedorUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nombre'     => 'sometimes|required|string|max:255',
            'cliente_id' => 'sometimes|required|exists:clientes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'     => 'El campo nombre es obligatorio.',
            'nombre.string'       => 'El campo nombre debe ser una cadena de texto.',
            'nombre.max'          => 'El campo nombre no debe exceder 255 caracteres.',
            'cliente_id.required' => 'El campo cliente es obligatorio.',
            'cliente_id.exists'   => 'El cliente seleccionado no existe.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ],
                400
            )
        );
    }
}
