<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\DemandSubmission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Mail;
use App\Models\Submission;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SubmissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Visualizar Submissão', only: ['index', 'show']),
            new Middleware('permission:Editar Submissão', only: ['markRead', 'markUnread', 'toggleRead', 'markReadSelected', 'markUnreadSelected']),
            new Middleware('permission:Deletar Submissão', only: ['destroy', 'deleteSelected']),
        ];
    }
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'demand_description' => 'required|string|max:5000',
            'expected_utility' => 'required|string|max:5000',
            'supporting_files' => 'nullable|array|max:5',
            'supporting_files.*' => 'file|mimes:pdf,doc,docx|max:10240',
        ], [
            'name.required' => 'Informe seu nome.',
            'email.required' => 'Informe seu e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'demand_description.required' => 'Descreva a demanda.',
            'expected_utility.required' => 'Informe a utilidade esperada.',
            'supporting_files.*.mimes' => 'Os anexos devem ser PDF, DOC ou DOCX.',
            'supporting_files.*.max' => 'Cada anexo pode ter no máximo 10MB.',
        ]);

        // Anexos ficam na pasta pública (o admin abre por link), então a extensão
        // vem do conteúdo do arquivo e nunca do nome enviado pelo visitante
        $attachments = null;
        if ($request->hasFile('supporting_files')) {
            $destinationPath = public_path('anexos_submissions');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $filePaths = [];
            foreach ($request->file('supporting_files') as $file) {
                $nomeBase = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'anexo';
                $fileName = time() . '_' . Str::random(8) . '_' . Str::limit($nomeBase, 60, '') . '.' . $file->guessExtension();
                $file->move($destinationPath, $fileName);
                $filePaths[] = 'anexos_submissions/' . $fileName;
            }
            $attachments = json_encode($filePaths);
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'demand_description' => $validated['demand_description'],
            'expected_utility' => $validated['expected_utility'],
            'attachments' => $attachments,
        ];

        // Salvar a submissão no banco de dados
        Submission::create($data);

        // O e-mail recebe só os anexos salvos acima
        Mail::to($data['email'])->send(new DemandSubmission($data));

        return redirect()->back()->with('success', 'Submissão enviada com sucesso!');
    }

    public function index()
    {
        $submissions = Submission::paginate(10);
        return view('submissions.index', compact('submissions'));
    }

    public function show($id)
    {
        $submission = Submission::findOrFail($id);
        // Marcar como lida
        if (!$submission->read) {
            $submission->update(['read' => true]);
        }
        return view('submissions.show', compact('submission'));
    }

    public function markRead($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->update(['read' => true]);
        return response()->json(['success' => 'Submissão marcada como lida.']);
    }

    public function markUnread($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->update(['read' => false]);
        return response()->json(['success' => 'Submissão marcada como não lida.']);
    }

    public function toggleRead($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->update(['read' => !$submission->read]);
        return response()->json(['success' => 'Status da submissão alterado.']);
    }

    public function markReadSelected(Request $request)
    {
        Submission::whereIn('id', $request->ids)->update(['read' => true]);
        return response()->json(['success' => 'Submissões marcadas como lidas.']);
    }

    public function markUnreadSelected(Request $request)
    {
        Submission::whereIn('id', $request->ids)->update(['read' => false]);
        return response()->json(['success' => 'Submissões marcadas como não lidas.']);
    }

    public function deleteSelected(Request $request)
    {
        if (!$request->has('ids') || empty($request->ids)) {
            return response()->json(['error' => 'Nenhuma submissão selecionada.'], 400);
        }

        $submissions = Submission::whereIn('id', $request->ids)->get();
        foreach ($submissions as $submission) {
            $this->deleteAttachments($submission);
            $submission->delete();
        }

        return response()->json(['success' => 'Submissões excluídas com sucesso.']);
    }

    public function destroy($id)
    {
        $submission = Submission::findOrFail($id);
        $this->deleteAttachments($submission);
        $submission->delete();
        return response()->json(['success' => 'Submissão excluída com sucesso.']);
    }

    private function deleteAttachments($submission)
    {
        if ($submission->attachments) {
            $attachments = json_decode($submission->attachments, true);
            foreach ($attachments as $attachment) {
                $filePath = public_path($attachment);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        }
    }
}