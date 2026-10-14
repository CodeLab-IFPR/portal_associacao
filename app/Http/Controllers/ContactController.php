<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMessage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
use Illuminate\Support\Facades\File;

class ContactController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Visualizar Mensagem', only: ['index', 'show']),
            new Middleware('permission:Marcar como Lida', only: ['markRead', 'markReadSelected']),
            new Middleware('permission:Marcar como Não Lida', only: ['markUnread', 'markUnreadSelected']),
            new Middleware('permission:Alterar Status da Mensagem', only: ['toggleRead']),
            new Middleware('permission:Deletar Mensagem', only: ['destroy', 'deleteSelected']),
        ];
    }

    public function index()
    {
        $mensagens = Contact::paginate(10);
        return view('mensagens.index', compact('mensagens'));
    }

    public function show($id)
    {
        $mensagem = Contact::findOrFail($id);
        $mensagem->update(['read' => true]);
        return view('mensagens.show', compact('mensagem'));
    }

    public function markRead($id)
    {
        $mensagem = Contact::findOrFail($id);
        $mensagem->update(['read' => true]);
        return response()->json(['success' => 'Mensagem marcada como lida.']);
    }

    public function destroy($id)
    {
        $mensagem = Contact::findOrFail($id);
        $this->deleteAttachments($mensagem);
        $mensagem->delete();

        return redirect()->route('mensagens.index')->with('success', 'Mensagem excluída com sucesso.');
    }

    public function deleteSelected(Request $request)
    {
        if (!$request->has('ids') || empty($request->ids)) {
            return response()->json(['error' => 'Nenhuma mensagem selecionada.'], 400);
        }

        $mensagens = Contact::whereIn('id', $request->ids)->get();
        foreach ($mensagens as $mensagem) {
            $this->deleteAttachments($mensagem);
            $mensagem->delete();
        }

        return response()->json(['success' => 'Mensagens excluídas com sucesso.']);
    }

    private function deleteAttachments($mensagem)
    {
        if ($mensagem->attachments) {
            $attachments = json_decode($mensagem->attachments, true);
            foreach ($attachments as $attachment) {
                $filePath = public_path($attachment);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        }
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'message' => 'required|string|max:5000',
        ], [
            'name.required' => 'Informe seu nome.',
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'message.required' => 'Escreva sua mensagem.',
        ]);

        // Salvar a mensagem de contato no banco de dados
        Contact::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
        ]);

        // O e-mail recebe só os campos validados: nada vindo do request decide anexos
        Mail::to($validated['email'])->send(new ContactMessage([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? '',
            'message' => $validated['message'],
        ]));

        return redirect()->back()->with('success', 'Mensagem enviada com sucesso!');
    }

    public function markUnread($id)
    {
        $mensagem = Contact::findOrFail($id);
        $mensagem->update(['read' => false]);
        return response()->json(['success' => 'Mensagem marcada como não lida.']);
    }

    public function toggleRead($id)
    {
        $mensagem = Contact::findOrFail($id);
        $mensagem->update(['read' => !$mensagem->read]);
        return response()->json(['success' => 'Status da mensagem alterado.']);
    }

    public function markReadSelected(Request $request)
    {
        Contact::whereIn('id', $request->ids)->update(['read' => true]);
        return response()->json(['success' => 'Mensagens marcadas como lidas.']);
    }

    public function markUnreadSelected(Request $request)
    {
        Contact::whereIn('id', $request->ids)->update(['read' => false]);
        return response()->json(['success' => 'Mensagens marcadas como não lidas.']);
    }
}
