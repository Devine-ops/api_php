<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'=> false,
            'erros'=> $validator->errors(),
        ], 422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            "name"=> 'required',
            "email"=> 'required|email|unique:users,email' . ($userId ? $userId->id : null),
            "password"=> 'required|min:6'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=>'O campo nome é obrigatótio',
            'email.required'=>'O campo email é obrigatório',
            'email.email'=>'O email deve ser valido',
            'email.unique'=>'O email já está cadastrado',
            'password.required'=>'O campo senha é obrigatório',
            'password.min'=>'Sua senha deve ter no minimo 6 caracteres'
        ];
    }
}
