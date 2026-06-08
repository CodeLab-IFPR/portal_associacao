<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller  {

    public function index()
    {
        $activities = DB::select("
            (
                SELECT
                    'USUARIO' AS tipo,
                    CONCAT('Novo usuário cadastrado: ', u.name) AS descricao,
                    u.created_at AS data
                FROM users u
            )

            UNION ALL

            (
                SELECT
                    'ATA' AS tipo,
                    CONCAT('Nova ATA criada: ', a.titulo) AS descricao,
                    a.created_at AS data
                FROM atas a
            )

            UNION ALL

            (
                SELECT
                    'DOCUMENTO' AS tipo,
                    CONCAT(
                        'Documento enviado por ',
                        u.name,
                        ': ',
                        COALESCE(d.tipo_documento, d.nome_original)
                    ) AS descricao,
                    d.created_at AS data
                FROM documentos d
                INNER JOIN users u ON u.id = d.user_id
            )

            UNION ALL

            (
                SELECT
                    'FATURA' AS tipo,
                    CONCAT(
                        'Cobrança criada para ',
                        u.name,
                        ' - R$ ',
                        FORMAT(i.total_amount, 2)
                    ) AS descricao,
                    i.created_at AS data
                FROM invoices i
                INNER JOIN users u ON u.id = i.user_id
            )

            UNION ALL

            (
                SELECT
                    'NOTICIA' AS tipo,
                    CONCAT(
                        'Nova notícia publicada: ',
                        n.titulo
                    ) AS descricao,
                    n.created_at AS data
                FROM noticias n
            )

            ORDER BY data DESC
            LIMIT 5
        ");

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

            $activity->data = Carbon::parse($activity->data);

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

            $pagas = $meses->map(function ($m) {
                return \App\Models\Invoice::where('status', 'paga')
                    ->whereMonth('updated_at', $m['mes'])
                    ->whereYear('updated_at', $m['ano'])
                    ->sum('total_amount');
            })->values()->toArray();

            $pendentes = $meses->map(function ($m) {
                return \App\Models\Invoice::where('status', 'pendente')
                    ->whereMonth('first_due_date', $m['mes'])
                    ->whereYear('first_due_date', $m['ano'])
                    ->sum('total_amount');
            })->values()->toArray();

            $vencidas = $meses->map(function ($m) {
                return \App\Models\Invoice::where('status', 'vencida')
                    ->whereMonth('first_due_date', $m['mes'])
                    ->whereYear('first_due_date', $m['ano'])
                    ->sum('total_amount');
            })->values()->toArray();

            $totalVencida  = \App\Models\Invoice::where('status', 'vencida')->sum('total_amount');
            $totalPendente = \App\Models\Invoice::where('status', 'pendente')->sum('total_amount');
            $totalPaga     = \App\Models\Invoice::where('status', 'paga')->sum('total_amount');

        return view('admin.index', compact('months', 'activities', 'labelsMeses', 'pagas', 'pendentes', 'vencidas', 'totalVencida', 'totalPendente', 'totalPaga'));
    }

}
