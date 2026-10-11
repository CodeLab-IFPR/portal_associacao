<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tarefa;
use App\Models\Servico;
use App\Support\CertificadoPdf;
use App\Http\Controllers\Concerns\PaginaListagem;
use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Writer as QrCodeWriter;
use Illuminate\View\View;
use App\Models\Certificado;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LancamentoServico;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CertificadoController extends Controller implements HasMiddleware
{
    use PaginaListagem;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Visualizar Certificado', only: ['index', 'show']),
            new Middleware('permission:Criar Certificado', only: ['create', 'store']),
            new Middleware('permission:Editar Certificado', only: ['edit', 'update']),
            new Middleware('permission:Deletar Certificado', only: ['destroy']),
        ];
        
    }

    public function emitir()
    {
        return view('certificados.emitir');
    }

    public function buscarCertificados(Request $request): JsonResponse
    {
        try {

            $request->validate([
                'cpf' => 'required|string|max:14'
            ]);

            // A tela envia o CPF com máscara e o banco tem CPFs com e sem pontuação
            $cpf = preg_replace('/\D+/', '', (string) $request->input('cpf'));

            $user = $cpf === '' ? null : User::whereRaw(
                "REPLACE(REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), ' ', ''), '/', '') = ?",
                [$cpf]
            )->first();
            
            if ($user) {
                $certificados = Certificado::where('user_id', $user->id)->get();

                return response()->json([
                    'certificados' => $certificados
                ]);
            } else {

                return response()->json([
                    'certificados' => []
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar certificados.'], 500);
        }
    }

    public function showValidationForm()
    {
        return view('certificados.validar');
    }

    public function validarCertificado(Request $request): JsonResponse
    {

        try {
            $request->validate([
                'token' => 'required|string|max:10'
            ]);

            $token = $request->input('token');

            $certificado = Certificado::where('token', $token)->with('user:id,name')->first();
            
            if ($certificado) {
                // Página pública: só o que comprova o certificado, sem dados pessoais do associado
                return response()->json(['certificado' => [
                    'token' => $certificado->token,
                    'descricao' => $certificado->descricao,
                    'horas' => $certificado->horas,
                    'data' => $certificado->data,
                    'user' => ['name' => $certificado->user?->name],
                ]]);
            } else {
                return response()->json(['error' => 'Certificado não encontrado.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocorreu um erro ao validar o certificado.'], 500);
        }
    }

    public function index(Request $request)
    {
        $certificados = $this->listagemDeCertificados($request);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('certificados.table', compact('certificados'))->render()
            ]);
        }

        return view('certificados.index', compact('certificados'));
    }

    /** Listagem usada pela tela e pela exclusão via AJAX. */
    private function listagemDeCertificados(Request $request)
    {
        $certificadosQuery = Certificado::latest()->latest('id');

        if ($request->search) {
            $certificadosQuery->where(function (Builder $builder) use ($request) {
                $builder->where('descricao', 'like', "%{$request->search}%")
                    ->orWhere('horas', 'like', "%{$request->search}%")
                    ->orWhere('data', 'like', "%{$request->search}%")
                    // O nome completo fica em "name"; "nome" só existe no cadastro público
                    ->orWhereHas('user', function (Builder $query) use ($request) {
                        $query->where('name', 'like', "%{$request->search}%")
                            ->orWhere('nome', 'like', "%{$request->search}%");
                    })
                    ->orWhere('token', 'like', "%{$request->search}%");
            });
        }

        return $this->paginarListagem($certificadosQuery, 'certificados.index', 5, ['search']);
    }

    public function create(Request $request)
    {
        $certificadosData = json_decode($request->input('data'), true);
        $users = User::all();
        $servicos = Servico::all(); // Adicione esta linha para obter os serviços
        return view('certificados.create', compact('users', 'certificadosData', 'servicos'));
    }

    public function edit(Certificado $certificado)
    {
        $users = User::all();
        $selectedUser = $certificado->user_id;
        return view('certificados.edit', [
            'certificado' => $certificado,
            'users' => $users,
            'selectedUserId' => $selectedUser
        ]);
    }

    public function update(Request $request, Certificado $certificado): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'descricao' => 'required|max:520',
            'horas' => 'required|integer',
            'data' => 'required|date',
        ],[
            'user_id.required' => 'O campo user é obrigatório',
            'descricao.required' => 'O campo descrição é obrigatório',
            'descricao.max' => 'O campo descrição deve ter no máximo 520 caracteres',
            'horas.required' => 'O campo horas é obrigatório',
            'horas.integer' => 'O campo horas deve ser um número inteiro',
            'data.required' => 'O campo data é obrigatório',
            'data.date' => 'O campo data deve ser uma data válida',
        ]);

        $certificado->update([
            'user_id' => $request->user_id,
            'descricao' => $request->descricao,
            'horas' => $request->horas,
            'data' => $request->data,
        ]);

        return redirect()->route("certificados.index")
            ->with("success", "Certificado atualizado com sucesso.");
    }

    public function store(Request $request): RedirectResponse
    {
        $certificados = $request->input('certificados', []);
        $descricao = $request->input('descricao');

        foreach ($certificados as $certificadoData) {
            $existingCertificado = Certificado::where('user_id', $certificadoData['user_id'])
                ->where('descricao', $certificadoData['descricao'])
                ->first();

            if (!$existingCertificado) {
                $certificado = new Certificado();
                $certificado->user_id = $certificadoData['user_id'];
                $certificado->horas = $certificadoData['horas'];
                $certificado->data = $certificadoData['data'];
                $certificado->descricao = $certificadoData['descricao']; // Usar a descrição correta
                $certificado->token = Str::random(10); // Gerar um token aleatório
                $certificado->save();

                // Marcar lançamentos como certificado_gerado
                if (isset($certificadoData['servico_id'])) {
                    LancamentoServico::where('user_id', $certificadoData['user_id'])
                        ->where('servico_id', $certificadoData['servico_id'])
                        ->update(['certificado_gerado' => true]);
                }
            }
        }

        // Verificar se há dados para criação manual de certificados
        if ($request->has('manual_certificado')) {
            $manualCertificadoData = $request->input('manual_certificado');
            $manualCertificado = new Certificado();
            $manualCertificado->user_id = $manualCertificadoData['user_id'];
            $manualCertificado->horas = $manualCertificadoData['horas'];
            $manualCertificado->data = $manualCertificadoData['data'];
            $manualCertificado->descricao = $manualCertificadoData['descricao'];
            $manualCertificado->token = Str::random(10); // Gerar um token aleatório
            $manualCertificado->save();
        }

        return redirect()->route('certificados.index')
            ->with('success', 'Certificados criados com sucesso.');
    }

    public function show(Certificado $certificado)
    {
        return view('certificados.show', compact('certificado'));
    }

    public function generateCertificate(Certificado $certificado, $download = false)
{
    $user = $certificado->user;
    if (!$user) {
        throw new \Exception("User associado ao certificado não encontrado.");
    }

    $name = $user->name;
    $cpf = $user->cpf;
    $credential = $certificado->token;
    $description = $certificado->descricao;
    $hours = $certificado->horas;
    $date = (new \DateTime($certificado->data))->format('d/m/Y');
    $url = route('certificados.validar', ['token' => $credential]);

    // QR Code em PNG via GD, num arquivo temporário só para o FPDF embutir
    $qrCodePath = tempnam(sys_get_temp_dir(), 'qr') . '.png';
    file_put_contents($qrCodePath, (new QrCodeWriter(new GDLibRenderer(500)))->writeString($url));

    $pdf = new CertificadoPdf();
    $pathToTemplate = public_path('certificate/Certificate2.pdf');
    if (!file_exists($pathToTemplate)) {
        throw new \Exception("Template file not found: " . $pathToTemplate);
    }
    $pdf->setSourceFile($pathToTemplate);
    $template = $pdf->importPage(1);

    $size = $pdf->getTemplateSize($template);

    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
    $pdf->useTemplate($template, 0, 0, $size['width'], $size['height']);

    $pdf->AddFont('DejaVuSerifCondensed', '', 'AMAZI___.TTF', true);
    $pdf->AddFont('DejaVuSans', '', 'DejaVuSans.ttf', true);

    $pdf->SetFont('DejaVuSerifCondensed', '', 12);
    $pdf->SetFontSize(30);
    $pdf->SetXY(35, 110);
    $pdf->Write(0, $name);

    $pdf->SetFont('DejaVuSans');
    $pdf->SetFontSize(12);
    $pdf->SetXY(35, 120);
    $text = "O CDT certifica que $name, inscrito(a) no CPF $cpf $description, perfazendo um total de $hours horas de atividades.";
    $pdf->MultiCell(170, 8, $text);

    $pdf->SetFont('Helvetica');
    $pdf->SetFontSize(12);
    $pdf->SetXY(145, 176.8);
    $pdf->Write(0, 'Horas: ' . $hours);

    $pdf->SetFont('Helvetica');
    $pdf->SetFontSize(12);
    $pdf->SetXY(241, 176.8);
    $pdf->Write(0, $credential);

    $pdf->Image($qrCodePath, 217, 133, 40, 40);

    $conteudo = $pdf->Output('S');
    @unlink($qrCodePath);

    // O PDF vai direto na resposta, sem ficar gravado na pasta pública
    $fileName = 'Certificado_' . $credential . '.pdf';

    return response($conteudo, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => ($download ? 'attachment' : 'inline') . '; filename="' . $fileName . '"',
    ]);
}


    public function viewCertificate(Certificado $certificado)
    {
        return $this->generateCertificate($certificado, false);
    }

    public function downloadCertificate(Certificado $certificado)
    {
        return $this->generateCertificate($certificado, true);
    }

    public function destroy($id)
    {
        try {
            $certificado = Certificado::findOrFail($id);
            $certificado->delete();

            $certificados = $this->listagemDeCertificados(request());

            if (request()->ajax()) {
                return response()->json([
                    'table' => view('certificados.table', compact('certificados'))->render()
                ]);
            }

            return redirect()->route('certificados.index')->with('success', 'Certificado excluído com sucesso.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao excluir o certificado.'], 500);
        }
    }

    protected function deleteFileIfExists(string $filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
