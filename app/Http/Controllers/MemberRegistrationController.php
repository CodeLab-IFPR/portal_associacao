<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Jobs\SendPasswordJob;
use App\Providers\ImageUploader;

class MemberRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.member-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            // Modalidade
            'modalidade_principal' => 'required|in:aeromodelismo,automodelismo',
            
            // Dados pessoais
            'nome' => 'required|min:2|max:255',
            'sobrenome' => 'required|min:2|max:255',
            'data_nascimento' => 'nullable|date|before:today',
            'cpf' => 'required|unique:users,cpf',
            'rg' => 'required|string|max:20',
            
            // Contato
            'telefone_celular' => 'required|string|max:20',
            'celular_whatsapp' => 'nullable|boolean',
            'telefone_residencial' => 'nullable|string|max:20',
            'telefone_comercial' => 'nullable|string|max:20',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'email_alternativo' => 'nullable|email|max:255',
            'senha' => 'nullable|string|max:255',
            
            // Endereço
            'cep' => 'required|string|max:10',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'bairro' => 'required|string|max:100',
            'estado' => 'required|string|max:50',
            'cidade' => 'required|string|max:100',
        ]);

        $entrada = $request->all();
        $entrada['ativo'] = false; // Always set as inactive by default
        $password = \Illuminate\Support\Str::random(10); // Generate a random password
        $entrada['password'] = Hash::make($password);
        
        // Combinar nome e sobrenome no campo name para compatibilidade
        $entrada['name'] = $entrada['nome'] . ' ' . $entrada['sobrenome'];
        
        // Converter checkbox para boolean
        $entrada['celular_whatsapp'] = $request->has('celular_whatsapp') ? true : false;

        $user = User::create($entrada);
        
        // Assign the member role
        $memberRole = Role::where('name', 'Membro')->first();
        if ($memberRole) {
            $user->assignRole($memberRole);
        }

        // Send registration confirmation email
        Mail::to($user->email)->send(new \App\Mail\MemberRegistrationMail($user));

        return redirect()->route('member.register.form')
            ->with('success', 'Cadastro realizado com sucesso! Por favor, verifique seu e-mail para mais informações sobre o processo de análise.');
    }
}
