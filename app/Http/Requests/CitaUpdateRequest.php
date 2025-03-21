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
            'nombre' => 'sometimes|string|max:255',
            'paciente_id' => 'sometimes|integer|exists:pacientes,id',
            'aseguradora_id' => 'sometimes|integer|exists:aseguradoras,id',
            'especialidad_id' => 'sometimes|integer|exists:especialidades,id',
            'medico_id' => 'sometimes|integer|exists:medicos,id',
            'fecha' => 'sometimes|date',
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
            'nombre.sometimes' => 'El nombre es obligatorio si está presente.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'paciente_id.sometimes' => 'El ID del paciente es obligatorio si está presente.',
            'paciente_id.integer' => 'El ID del paciente debe ser un número entero.',
            'paciente_id.exists' => 'El paciente seleccionado no existe.',
            'aseguradora_id.sometimes' => 'El ID de la aseguradora es obligatorio si está presente.',
            'aseguradora_id.integer' => 'El ID de la aseguradora debe ser un número entero.',
            'aseguradora_id.exists' => 'La aseguradora seleccionada no existe.',
            'especialidad_id.sometimes' => 'El ID de la especialidad es obligatorio si está presente.',
            'especialidad_id.integer' => 'El ID de la especialidad debe ser un número entero.',
            'especialidad_id.exists' => 'La especialidad seleccionada no existe.',
            'medico_id.sometimes' => 'El ID del médico es obligatorio si está presente.',
            'medico_id.integer' => 'El ID del médico debe ser un número entero.',
            'medico_id.exists' => 'El médico seleccionado no existe.',
            'fecha.sometimes' => 'La fecha es obligatoria si está presente.',
            'fecha.date' => 'La fecha debe ser una fecha válida.',
        ];
    }
}
