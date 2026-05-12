<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'cpf' => 'required|string|unique:users,cpf,' . $userId,
            'telefone' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
            'faixa' => 'required|string',
            'grau' => 'required|integer|min:0|max:4',
            'start_date' => 'required|date',
            'plan_id' => 'required|exists:plans,id',
            'password' => ['nullable', 'string', Password::min(8)->letters()->numbers()],
            'status' => 'required|string|in:active,inactive',
            'user_role' => 'nullable|string|in:aluno,professor,instrutor,admin',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.unique' => 'Este e-mail já está em uso.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'plan_id.required' => 'Selecione um plano.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('cpf')) {
            $this->merge([
                'cpf' => preg_replace('/[^0-9]/', '', $this->cpf),
            ]);
        }
    }
}
