<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PacienteUpdateRequest extends FormRequest
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
            'empresa_id' => 'required|integer|exists:empresas,id',
            'nombres' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',

            'tipo_documento_identidad_id' => 'required|integer|exists:tipo_documento_identidad,id',
            'numero_documento_identidad' => 'required|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'fecha_nacimiento' => 'required|date',
        ];
    }

     public function messages(): array
    {
        return [
            'empresa_id.required' => 'El ID de la empresa es obligatorio.',
            'empresa_id.integer' => 'El ID de la empresa debe ser un número entero.',
            'empresa_id.exists' => 'La empresa seleccionada no existe.',
            'nombres.required' => 'El nombre es obligatorio.',
            'nombres.string' => 'El nombre debe ser una cadena de texto.',
            'nombres.max' => 'El nombre no puede tener más de 255 caracteres.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_paterno.string' => 'El apellido paterno debe ser una cadena de texto.',
            'apellido_paterno.max' => 'El apellido paterno no puede tener más de 255 caracteres.',
            'apellido_materno.required' => 'El apellido materno es obligatorio.',
            'apellido_materno.string' => 'El apellido materno debe ser una cadena de texto.',
            'apellido_materno.max' => 'El apellido materno no puede tener más de 255 caracteres.',

            'tipo_documento_identidad_id.required' => 'El tipo de documento de identidad es obligatorio.',
            'tipo_documento_identidad_id.integer' => 'El tipo de documento de identidad debe ser un número entero.',
            'tipo_documento_identidad_id.exists' => 'El tipo de documento de identidad seleccionado no existe.',
            'numero_documento_identidad.required' => 'El número de documento de identidad es obligatorio.',
            'numero_documento_identidad.string' => 'El número de documento de identidad debe ser una cadena de texto.',
            'numero_documento_identidad.max' => 'El número de documento de identidad no puede tener más de 20 caracteres.',
            'numero_documento_identidad.unique' => 'El número de documento de identidad ya está registrado.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
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
