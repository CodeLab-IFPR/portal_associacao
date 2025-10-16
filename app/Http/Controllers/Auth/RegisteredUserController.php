<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Jobs\SendPasswordJob;
use App\Mail\SendPasswordMail;
use App\Providers\ImageUploader;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Queue;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RegisteredUserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Visualizar Membro', only: ['index', 'show']),
            new Middleware('permission:Criar Membro', only: ['create', 'store']),
            new Middleware('permission:Editar Membro', only: ['edit', 'update']),
            new Middleware('permission:Deletar Membro', only: ['destroy']),
        ];
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        return view('users.create', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Modalidade
            'modalidade_principal' => 'nullable|in:aeromodelismo,automodelismo',
            
            // Dados pessoais
            'nome' => 'nullable|min:2|max:255',
            'sobrenome' => 'nullable|min:2|max:255',
            'data_nascimento' => 'nullable|date|before:today',
            'cpf' => 'required|unique:users,cpf',
            'rg' => 'nullable|string|max:20',
            'cargo_id' => 'nullable|exists:cargos,id',
            
            // Contato
            'telefone_celular' => 'nullable|string|max:20',
            'celular_whatsapp' => 'nullable|boolean',
            'telefone_residencial' => 'nullable|string|max:20',
            'telefone_comercial' => 'nullable|string|max:20',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'email_alternativo' => 'nullable|email|max:255',
            'senha' => 'nullable|string|max:255',
            
            // Endereço
            'cep' => 'nullable|string|max:10',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:10',
            'bairro' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:50',
            'cidade' => 'nullable|string|max:100',
            
            // Campos originais - removidos: cargo, biografia, linkedin, github, alt, imagem
        ]);
    
        $entrada = $request->all();
        $entrada['ativo'] = $request->has('ativo') ? $request->ativo : false;
        $password = $request->input('generated_password');
        $entrada['password'] = Hash::make($password);
        
        // Combinar nome e sobrenome no campo name para compatibilidade, se existirem
        if ($request->filled('nome') && $request->filled('sobrenome')) {
            $entrada['name'] = $entrada['nome'] . ' ' . $entrada['sobrenome'];
        } elseif (!$request->filled('name') && ($request->filled('nome') || $request->filled('sobrenome'))) {
            $entrada['name'] = ($entrada['nome'] ?? '') . ' ' . ($entrada['sobrenome'] ?? '');
        }
        
        // Converter checkbox para boolean
        $entrada['celular_whatsapp'] = $request->has('celular_whatsapp') ? true : false;

        $user = User::create($entrada);
        $user->syncRoles($request->roles);
    
        // Enviar email de verificação
        event(new Registered($user));
    
        // Enviar email de verificação
        event(new Registered($user));
    
        // Enviar email de forma assíncrona usando um job
        SendPasswordJob::dispatch($user, $password);
    
        return redirect()->route("users.index")
            ->with("success", "Usuário criado com sucesso.");
    }

    public function index(Request $request)
    {
        $usersQuery = User::latest();
    
        if ($request->search) {
            $usersQuery->where(function (Builder $builder) use ($request) {
                $builder->where('name', 'like', "%{$request->search}%")
                        ->orWhere('cpf', 'like', "%{$request->search}%")
                        ->orWhere('cargo', 'like', "%{$request->search}%");
            });
        }
    
        $users = $usersQuery->paginate(5);
    
        if ($request->ajax()) {
            return response()->json([
                'table' => view('users.table', compact('users'))->render()
            ]);
        }
    
        return view('users.index', compact('users'));
    }

    public function about(): View
    {
        $users = User::where('ativo', true)->get();
        
        return view('about', compact('users'));
    }

    public function show(User $user): View
    {
        return view("users.show", compact("user"));
    }

    public function edit(User $user): View
    {   
        $user = User::findOrFail($user->id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $tem_roles = $user->roles->pluck('id');
        return view("users.edit", compact("user", "roles", "tem_roles"));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            // Modalidade
            'modalidade_principal' => 'nullable|in:aeromodelismo,automodelismo',
            
            // Dados pessoais
            'nome' => 'nullable|min:2|max:255',
            'sobrenome' => 'nullable|min:2|max:255',
            'data_nascimento' => 'nullable|date|before:today',
            'cpf' => 'required|unique:users,cpf,' . $user->id,
            'rg' => 'nullable|string|max:20',
            'cargo_id' => 'nullable|exists:cargos,id',
            
            // Contato
            'telefone_celular' => 'nullable|string|max:20',
            'celular_whatsapp' => 'nullable|boolean',
            'telefone_residencial' => 'nullable|string|max:20',
            'telefone_comercial' => 'nullable|string|max:20',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'email_alternativo' => 'nullable|email|max:255',
            'senha' => 'nullable|string|max:255',
            
            // Endereço
            'cep' => 'nullable|string|max:10',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:10',
            'bairro' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:50',
            'cidade' => 'nullable|string|max:100',
            
            // Campos originais - removidos: cargo, biografia, linkedin, github, alt, imagem
        ], [
            'cpf.unique' => 'CPF já cadastrado.',
            'cpf.required' => 'O campo CPF é obrigatório.',
            'nome.min' => 'O campo nome deve ter no mínimo 2 caracteres.',
            'nome.max' => 'O campo nome deve ter no máximo 255 caracteres.',
            'sobrenome.min' => 'O campo sobrenome deve ter no mínimo 2 caracteres.',
            'sobrenome.max' => 'O campo sobrenome deve ter no máximo 255 caracteres.',
        ]);
    
        $entrada = $request->all();
        $entrada['ativo'] = $request->has('ativo') ? $request->ativo : false;
        
        // Combinar nome e sobrenome no campo name para compatibilidade, se existirem
        if ($request->filled('nome') && $request->filled('sobrenome')) {
            $entrada['name'] = $entrada['nome'] . ' ' . $entrada['sobrenome'];
        } elseif (!$request->filled('name') && ($request->filled('nome') || $request->filled('sobrenome'))) {
            $entrada['name'] = ($entrada['nome'] ?? '') . ' ' . ($entrada['sobrenome'] ?? '');
        }
        
        // Converter checkbox para boolean
        $entrada['celular_whatsapp'] = $request->has('celular_whatsapp') ? true : false;

        $user->update($entrada);
        $user->syncRoles($request->roles);
    
        return redirect()->route("users.index")
            ->with("success", "Usuário atualizado com sucesso.");
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->imagem) {
                $imagePath = public_path("imagens/users/{$user->imagem}");
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            $user->delete();

            $users = User::paginate(5);

            if (request()->ajax()) {
                return response()->json([
                    'table' => view('users.table', compact('users'))->render()
                ]);
            }
          
            return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao excluir o usuário.'], 500);
        }
    }
}