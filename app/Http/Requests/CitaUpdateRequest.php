<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CitaUpdateRequest extends FormRequest
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
            'paciente_id' => 'required|integer|exists:pacientes,id',
            'aseguradora_id' => 'required|integer',
            'especialidad_id' => 'required|integer',
            'medico_id' => 'required|integer',
            'fecha' => 'required|date',
            'hora' => 'required|string',
            'estado_id' => 'required|integer',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'paciente_id.required' => 'El ID del paciente es obligatorio.',
            'paciente_id.integer' => 'El ID del paciente debe ser un número entero.',
            'aseguradora_id.required' => 'El ID de la aseguradora es obligatorio.',
            'aseguradora_id.integer' => 'El ID de la aseguradora debe ser un número entero.',
            'especialidad_id.required' => 'El ID de la especialidad es obligatorio.',
            'especialidad_id.integer' => 'El ID de la especialidad debe ser un número entero.',
            'medico_id.required' => 'El ID del médico es obligatorio.',
            'medico_id.integer' => 'El ID del médico debe ser un número entero.',
            'medico_id.exists' => 'El médico seleccionado no existe.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser una fecha válida.',
            'hora.required' => 'La hora es obligatoria.',
            'hora.string' => 'La hora debe ser una hora válida.',
            'estado_id.required' => 'El estado_id es obligatoria.',
            'estado_id.string' => 'El estado_id debe ser un numero entero.',
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
