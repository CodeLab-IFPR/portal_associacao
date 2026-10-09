<?php

namespace App\Http\Controllers;

use App\Models\Ata;
use App\Models\Documento;
use App\Models\Invoice;
use App\Models\InvoiceInstallment;
use App\Models\Noticias;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private const MONTHS = [
        'Jan' => 'JAN',
        'Feb' => 'FEV',
        'Mar' => 'MAR',
        'Apr' => 'ABR',
        'May' => 'MAI',
        'Jun' => 'JUN',
        'Jul' => 'JUL',
        'Aug' => 'AGO',
        'Sep' => 'SET',
        'Oct' => 'OUT',
        'Nov' => 'NOV',
        'Dec' => 'DEZ'
    ];

    private const ICONS = [
        'USUARIO' => [
            'icon' => 'fas fa-users',
            'color' => 'text-success'
        ],
        'ATA' => [
            'icon' => 'fas fa-file-signature',
            'color' => 'text-primary'
        ],
        'DOCUMENTO' => [
            'icon' => 'fas fa-folder-open',
            'color' => 'text-warning'
        ],
        'FATURA' => [
            'icon' => 'fas fa-file-invoice-dollar',
            'color' => 'text-danger'
        ],
        'NOTICIA' => [
            'icon' => 'fas fa-newspaper',
            'color' => 'text-info'
        ],
    ];

    public function index(Request $request)
    {
        $user = $request->user();
        $isAdmin = $this->isAdmin($user);

        // Membro comum só enxerga as próprias parcelas (mesma regra de Faturas e Pendências)
        $parcelas = InvoiceInstallment::where('status', '!=', 'cancelada')
            ->when(!$isAdmin, fn ($q) => $q->whereHas('invoice', fn ($i) => $i->where('user_id', $user->id)));

        $qtdVencidas   = (clone $parcelas)->withEffectiveStatus('vencida')->count();
        $totalVencida  = (float) (clone $parcelas)->withEffectiveStatus('vencida')->sum('amount');
        $qtdPendentes  = (clone $parcelas)->withEffectiveStatus('pendente')->count();
        $totalPendente = (float) (clone $parcelas)->withEffectiveStatus('pendente')->sum('amount');
        $totalPagaMes  = (float) (clone $parcelas)->where('status', 'paga')
            ->whereBetween('payment_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->sum('amount');

        $proximosVencimentos = (clone $parcelas)->withEffectiveStatus('pendente')
            ->with('invoice.user')
            ->orderBy('due_date')
            ->take(6)
            ->get();

        [$labelsMeses, $pagas, $pendentes, $vencidas] = $this->graficoUltimosMeses($parcelas);

        $membrosAtivos   = User::where('ativo', true)->count();
        $membrosNovosMes = User::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count();
        $totalAtas       = Ata::count();
        $ultimaAta       = Ata::latest()->first();

        $activities = $this->atividadesRecentes($user, $isAdmin);
        $months = self::MONTHS;

        return view('admin.index', compact(
            'isAdmin', 'months', 'activities',
            'qtdVencidas', 'totalVencida', 'qtdPendentes', 'totalPendente', 'totalPagaMes',
            'proximosVencimentos', 'labelsMeses', 'pagas', 'pendentes', 'vencidas',
            'membrosAtivos', 'membrosNovosMes', 'totalAtas', 'ultimaAta'
        ));
    }

    private function isAdmin($user): bool
    {
        return $user && ($user->hasRole('admin') || $user->hasRole('Admin'));
    }

    /**
     * Valores dos últimos 6 meses: pagas pelo mês do pagamento,
     * pendentes e vencidas pelo mês do vencimento da parcela.
     */
    private function graficoUltimosMeses($parcelas): array
    {
        $meses = collect(range(5, 0))->map(function ($i) {
            $data = now()->subMonths($i);
            return [
                'label' => self::MONTHS[$data->format('M')] . '/' . $data->format('Y'),
                'chave' => $data->format('Y-m'),
            ];
        });

        $inicio = now()->subMonths(5)->startOfMonth()->toDateString();
        $fim    = now()->endOfMonth()->toDateString();

        $pagasPorMes = (clone $parcelas)->where('status', 'paga')
            ->whereBetween('payment_date', [$inicio, $fim])
            ->get(['amount', 'payment_date'])
            ->groupBy(fn ($p) => $p->payment_date->format('Y-m'))
            ->map->sum('amount');

        $abertas = (clone $parcelas)->whereIn('status', InvoiceInstallment::OPEN_STATUSES)
            ->whereBetween('due_date', [$inicio, $fim])
            ->get(['amount', 'due_date', 'status'])
            ->groupBy('effective_status');

        $somaPorMes = fn ($status) => $abertas->get($status, collect())
            ->groupBy(fn ($p) => $p->due_date->format('Y-m'))
            ->map->sum('amount');

        $pendentesPorMes = $somaPorMes('pendente');
        $vencidasPorMes  = $somaPorMes('vencida');

        return [
            $meses->pluck('label')->all(),
            $meses->map(fn ($m) => (float) $pagasPorMes->get($m['chave'], 0))->all(),
            $meses->map(fn ($m) => (float) $pendentesPorMes->get($m['chave'], 0))->all(),
            $meses->map(fn ($m) => (float) $vencidasPorMes->get($m['chave'], 0))->all(),
        ];
    }

    private function atividadesRecentes($user, bool $isAdmin)
    {
        // Cadastros de usuários só aparecem para admin
        $usuarios = !$isAdmin ? collect() : User::select('id', 'name', 'created_at')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($u) => (object) [
                'tipo'      => 'USUARIO',
                'descricao' => "Novo usuário cadastrado: {$u->name}",
                'data'      => $u->created_at,
            ]);

        $atas = Ata::select('id', 'titulo', 'created_at')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($a) => (object) [
                'tipo'      => 'ATA',
                'descricao' => "Nova ATA criada: {$a->titulo}",
                'data'      => $a->created_at,
            ]);

        $documentos = Documento::with('user:id,name')
            ->select('id', 'user_id', 'tipo_documento', 'nome_original', 'created_at')
            ->when(!$isAdmin, fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($d) => (object) [
                'tipo'      => 'DOCUMENTO',
                'descricao' => 'Documento enviado por ' . optional($d->user)->name
                    . ': ' . ($d->tipo_documento ?? $d->nome_original),
                'data'      => $d->created_at,
            ]);

        $faturas = Invoice::with('user:id,name')
            ->select('id', 'user_id', 'total_amount', 'created_at')
            ->when(!$isAdmin, fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($i) => (object) [
                'tipo'      => 'FATURA',
                'descricao' => 'Cobrança criada para ' . optional($i->user)->name
                    . ' - R$ ' . number_format($i->total_amount, 2, ',', '.'),
                'data'      => $i->created_at,
            ]);

        $noticias = Noticias::select('id', 'titulo', 'created_at')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($n) => (object) [
                'tipo'      => 'NOTICIA',
                'descricao' => "Nova notícia publicada: {$n->titulo}",
                'data'      => $n->created_at,
            ]);

        return $usuarios
            ->merge($atas)
            ->merge($documentos)
            ->merge($faturas)
            ->merge($noticias)
            ->sortByDesc('data')
            ->take(5)
            ->values()
            ->each(function ($activity) {
                $activity->icon  = self::ICONS[$activity->tipo]['icon'] ?? 'fas fa-circle';
                $activity->color = self::ICONS[$activity->tipo]['color'] ?? 'text-secondary';
            });
    }
}
