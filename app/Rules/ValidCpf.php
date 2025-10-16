<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpf implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Se o valor estiver vazio, a validação passa (campo não obrigatório)
        if (empty($value)) {
            return;
        }

        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $value);

        // Verifica se tem 11 dígitos
        if (strlen($cpf) !== 11) {
            $fail('O campo :attribute deve conter exatamente 11 dígitos.');
            return;
        }

        // Verifica se todos os dígitos são iguais (ex: 111.111.111-11)
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            $fail('O :attribute informado não é válido.');
            return;
        }

        // Calcula o primeiro dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += intval($cpf[$i]) * (10 - $i);
        }
        $remainder = 11 - ($sum % 11);
        $digit1 = $remainder >= 10 ? 0 : $remainder;

        // Verifica o primeiro dígito
        if ($digit1 !== intval($cpf[9])) {
            $fail('O :attribute informado não é válido.');
            return;
        }

        // Calcula o segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += intval($cpf[$i]) * (11 - $i);
        }
        $remainder = 11 - ($sum % 11);
        $digit2 = $remainder >= 10 ? 0 : $remainder;

        // Verifica o segundo dígito
        if ($digit2 !== intval($cpf[10])) {
            $fail('O :attribute informado não é válido.');
            return;
        }
    }
}
