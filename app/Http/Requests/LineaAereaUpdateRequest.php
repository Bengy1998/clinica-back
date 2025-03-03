<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LineaAereaUpdateRequest extends FormRequest
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
            'nombre'     => 'sometimes|required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'     => 'El campo nombre es obligatorio.',
            'nombre.string'       => 'El campo nombre debe ser una cadena de texto.',
            'nombre.max'          => 'El campo nombre no debe exceder 255 caracteres.',
            'empresa_id.required' => 'El campo empresa es obligatorio.',
            'empresa_id.exists'   => 'La empresa seleccionada no existe.',
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
