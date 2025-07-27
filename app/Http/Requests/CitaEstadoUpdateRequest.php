<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CitaEstadoUpdateRequest extends FormRequest
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
            'estado_id' => 'required|integer|exists:cita_estado,id'
        ];
    }

     public function messages(): array
    {
        return [
            'estado_id.required' => 'El estado es obligatorio.',
            'estado_id.integer' => 'El estado debe ser un número entero.',
            'estado_id.exists' => 'El estado seleccionado no es válido.'
        ];
    }

    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            response()->json(
                [
                    'success' => false,
                    'message' => 'Validar Errores',
                    'errors' => $validator->errors()
                ],
                400
            )
        );
    }
}
