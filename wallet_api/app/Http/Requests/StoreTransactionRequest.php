<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTransactionRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'receiver_wallet_id' => 'nullable|exists:wallets,id',
            'send_wallet_id' => 'nullable|exists:wallets,id',
        ];
    }

     public function messages()
    {
        return[
         'amount.required' => 'Le montant est obligatoire.',
         'amount.min' => 'Le montant doit être supérieur à 0.',
       
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
