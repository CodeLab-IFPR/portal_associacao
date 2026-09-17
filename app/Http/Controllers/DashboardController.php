<?php

namespace App\Http\Controllers;

use App\Models\Ata;
use App\Models\Documento;
use App\Models\Invoice;
use App\Models\Noticias;
use App\Models\User;

class DashboardController extends Controller  {

    public function index()
    {
        $usuarios = User::select('id', 'name', 'created_at')
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
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($i) => (object) [
                'tipo'      => 'FATURA',
                'descricao' => 'Cobrança criada para ' . optional($i->user)->name
                    . ' - R$ ' . number_format($i->total_amount, 2),
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

        $activities = $usuarios
            ->merge($atas)
            ->merge($documentos)
            ->merge($faturas)
            ->merge($noticias)
            ->sortByDesc('data')
            ->take(5)
            ->values();

        $icons = [
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

        foreach ($activities as $activity) {

            $activity->icon = $icons[$activity->tipo]['icon']
                ?? 'fas fa-circle';

            $activity->color = $icons[$activity->tipo]['color']
                ?? 'text-secondary';
        }

            $months = [
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

            $meses = collect(range(5, 0))->map(function ($i) use ($months) {
                $data = now()->subMonths($i);
                return [
                    'label' => $months[$data->format('M')] . '/' . $data->format('Y'),
                    'mes'   => $data->month,
                    'ano'   => $data->year,
                ];
            });

            $labelsMeses = $meses->pluck('label')->values()->toArray();

            $invoices = Invoice::select('status', 'total_amount', 'first_due_date', 'updated_at')->get();

            $porStatus = $invoices->groupBy('status');

            $pagas = $meses->map(function ($m) use ($porStatus) {
                return $porStatus->get('paga', collect())
                    ->filter(fn ($invoice) => $invoice->updated_at->month === $m['mes'] && $invoice->updated_at->year === $m['ano'])
                    ->sum('total_amount');
            })->values()->toArray();

            $pendentes = $meses->map(function ($m) use ($porStatus) {
                return $porStatus->get('pendente', collect())
                    ->filter(fn ($invoice) => $invoice->first_due_date->month === $m['mes'] && $invoice->first_due_date->year === $m['ano'])
                    ->sum('total_amount');
            })->values()->toArray();

            $vencidas = $meses->map(function ($m) use ($porStatus) {
                return $porStatus->get('vencida', collect())
                    ->filter(fn ($invoice) => $invoice->first_due_date->month === $m['mes'] && $invoice->first_due_date->year === $m['ano'])
                    ->sum('total_amount');
            })->values()->toArray();

            $totalVencida  = $porStatus->get('vencida', collect())->sum('total_amount');
            $totalPendente = $porStatus->get('pendente', collect())->sum('total_amount');
            $totalPaga     = $porStatus->get('paga', collect())->sum('total_amount');

        return view('admin.index', compact('months', 'activities', 'labelsMeses', 'pagas', 'pendentes', 'vencidas', 'totalVencida', 'totalPendente', 'totalPaga'));
    }

}
