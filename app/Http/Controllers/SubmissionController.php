<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\DemandSubmission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Mail;
use App\Models\Submission;
use Illuminate\Support\Facades\File;

class SubmissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Visualizar Submissão', only: ['index', 'show']),
            new Middleware('permission:Editar Submissão', only: ['markRead', 'markUnread', 'toggleRead']),
            new Middleware('permission:Deletar Submissão', only: ['destroy']),
        ];
    }
    public function submit(Request $request)
    {
        $data = $request->all();

        // Verificar e processar arquivos anexados
        if ($request->hasFile('supporting_files')) {
            $files = $request->file('supporting_files');
            $filePaths = [];
            foreach ($files as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('anexos_submissions');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $filePaths[] = 'anexos_submissions/' . $fileName;
            }
            $data['attachments'] = json_encode($filePaths);
        }

        // Salvar a submissão no banco de dados
        $submission = Submission::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'demand_description' => $data['demand_description'],
            'expected_utility' => $data['expected_utility'],
            'attachments' => $data['attachments'] ?? null,
        ]);

        // Enviar e-mail
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