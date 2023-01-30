<?php

namespace App\Http\Requests;

use App\Models\Pessoa;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdatePessoaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nome' => 'required',
            'documento' => 'required|unique:pessoas,documento,' . $this->id,
            'telefone' => 'required|unique:pessoas,telefone,' . $this->id,
            'status' => ['required',
                Rule::in(Pessoa::status)],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ]));
    }

    public function messages()
    {
        return Pessoa::messages();
    }
}
