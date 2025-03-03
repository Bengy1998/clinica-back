<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmpresaRequest extends FormRequest
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
            'ruc' => 'required|string|max:13|unique:empresas,ruc',
            'dominio' => 'required|string|max:255|unique:empresas,dominio',
            //'correo' => 'required|email|max:255',
            //'telefono' => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.unique' => 'El RUC ya está en uso.',
            'dominio.required' => 'El dominio es obligatorio.',
            'dominio.unique' => 'El dominio ya está en uso.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'El correo debe ser una dirección de correo válida.',
            'telefono.required' => 'El teléfono es obligatorio.',
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
