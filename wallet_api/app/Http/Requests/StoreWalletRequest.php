<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreWalletRequest extends FormRequest
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
            'name' => 'required|string',
            'currency' => 'required|in:MAD,USD,EUR,',
            'balance' => 'nullable|numeric',

        ];
    }

    public function messages()
    {
        return[
        'name.required' => "Le nom du wallet est obligatoire",
        'currency.in' => "La devise sélectionnée n'est pas valide.",
        ];

    }

    public function failedValidation(Validator $validator)
    {
       $response = response()->json([
            "success" => false,
            "message" => "Erreur de validation.",
            "errors" => $validator->errors()
        ], 422);
            throw new HttpResponseException($response);
    }
}
