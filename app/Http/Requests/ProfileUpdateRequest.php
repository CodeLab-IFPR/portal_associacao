<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'cargo' => ['nullable', 'string', 'max:255'],
            'cpf' => ['nullable', 'string', 'max:14'],
            'cropped_image' => ['nullable', 'string'],
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Novos campos
            'modalidade_principal' => ['nullable', 'in:aeromodelismo,automodelismo'],
            'sobrenome' => ['nullable', 'string', 'max:255'],
            'data_nascimento' => ['nullable', 'date'],
            'rg' => ['nullable', 'string', 'max:20'],
            'telefone_celular' => ['nullable', 'string', 'max:20'],
            'celular_whatsapp' => ['nullable', 'boolean'],
            'telefone_residencial' => ['nullable', 'string', 'max:20'],
            'telefone_comercial' => ['nullable', 'string', 'max:20'],
            'email_alternativo' => ['nullable', 'email', 'max:255'],
            'cep' => ['nullable', 'string', 'max:10'],
            'logradouro' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:10'],
            'bairro' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'string', 'max:100'],
            'cidade' => ['nullable', 'string', 'max:100'],
        ];
    }
}
