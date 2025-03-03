<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AvisoUpdateRequest extends FormRequest
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
            'nro_vuelo'       => 'required|string|max:50',
            'linea_id'        => 'required|exists:linea_aereas,id',
            'hawg'            => 'required|string|max:100',
            'origen'          => 'required|string|max:100',
            'destino'         => 'required|string|max:100',
            'cliente_id'      => 'required|exists:clientes,id',
            'proveedor_id'    => 'required|exists:proveedores,id',
            'contenido'       => 'required|string',
            'bultos'          => 'required',
            'peso'            => 'required',
            'tipo_flete_term' => 'required|in:1,2',
            'eta_aprox'       => 'required|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'nro_vuelo.required'       => 'El campo número de vuelo es obligatorio.',
            'nro_vuelo.string'         => 'El campo número de vuelo debe ser una cadena de texto.',
            'nro_vuelo.max'            => 'El campo número de vuelo no debe exceder 50 caracteres.',
            'linea_id.required'        => 'El campo línea aérea es obligatorio.',
            'linea_id.exists'          => 'La línea aérea seleccionada no existe.',
            'hawg.required'            => 'El campo HAWG es obligatorio.',
            'hawg.string'              => 'El campo HAWG debe ser una cadena de texto.',
            'hawg.max'                 => 'El campo HAWG no debe exceder 100 caracteres.',
            'origen.required'          => 'El campo origen es obligatorio.',
            'origen.string'            => 'El campo origen debe ser una cadena de texto.',
            'origen.max'               => 'El campo origen no debe exceder 100 caracteres.',
            'destino.required'         => 'El campo destino es obligatorio.',
            'destino.string'           => 'El campo destino debe ser una cadena de texto.',
            'destino.max'              => 'El campo destino no debe exceder 100 caracteres.',
            'cliente_id.required'      => 'El campo cliente es obligatorio.',
            'cliente_id.exists'        => 'El cliente seleccionado no existe.',
            'proveedor_id.required'    => 'El campo proveedor es obligatorio.',
            'proveedor_id.exists'      => 'El proveedor seleccionado no existe.',
            'contenido.required'       => 'El campo contenido es obligatorio.',
            'contenido.string'         => 'El campo contenido debe ser una cadena de texto.',
            'bultos.required'          => 'El campo bultos es obligatorio.',
            'bultos.numeric'           => 'El campo bultos debe ser numérico.',
            'peso.required'            => 'El campo peso es obligatorio.',
            'peso.numeric'             => 'El campo peso debe ser numérico.',
            'mrn.required'             => 'El campo MRN es obligatorio.',
            'mrn.string'               => 'El campo MRN debe ser una cadena de texto.',
            'mrn.max'                  => 'El campo MRN no debe exceder 100 caracteres.',
            'secuencial.required'      => 'El campo secuencial es obligatorio.',
            'secuencial.string'        => 'El campo secuencial debe ser una cadena de texto.',
            'secuencial.max'           => 'El campo secuencial no debe exceder 100 caracteres.',
            'nro_anviso.required'      => 'El campo número de aviso es obligatorio.',
            'nro_anviso.string'        => 'El campo número de aviso debe ser una cadena de texto.',
            'nro_anviso.max'           => 'El campo número de aviso no debe exceder 50 caracteres.',
            'tipo_flete_term.required' => 'El campo tipo flete/term es obligatorio.',
            'tipo_flete_term.in'       => 'El campo tipo flete/term debe ser 1 (Flete Collet) o 2 (Flete Prepaid).',
            'eta_aprox.required'       => 'El campo ETA aproximada es obligatorio.',
            'eta_aprox.string'         => 'El campo ETA aproximada debe ser una cadena de texto.',
            'eta_aprox.max'            => 'El campo ETA aproximada no debe exceder 100 caracteres.',
            'empresa_id.required'      => 'El campo empresa es obligatorio.',
            'empresa_id.exists'        => 'La empresa seleccionada no existe.',
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
