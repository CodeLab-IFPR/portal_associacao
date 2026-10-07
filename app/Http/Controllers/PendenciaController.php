<?php

namespace App\Http\Controllers;

use App\Models\InvoiceInstallment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PendenciaController extends Controller
{
    private const PER_PAGE = 15;

    public function index(Request $request)
    {
        $validated = $this->validateFilters($request);
        $user = $request->user();
        $isAdmin = $this->isAdmin($user);

        $baseQuery = $this->buildBaseQuery($user?->id, $isAdmin);
        $filteredQuery = $this->applyFilters(clone $baseQuery, $validated, $isAdmin);

        $summaryQuery = clone $filteredQuery;
        $openStatuses = InvoiceInstallment::OPEN_STATUSES;

        $summary = [
            'total_pendente' => (float) (clone $summaryQuery)->whereIn('status', $openStatuses)->sum('amount'),
            'total_pendencias' => (clone $summaryQuery)->whereIn('status', $openStatuses)->count(),
            'pendencias_vencidas' => (clone $summaryQuery)->withEffectiveStatus('vencida')->count(),
        ];

        $installments = $filteredQuery
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $availableMonths = Cache::remember(
            'pendencias.available_months.' . ($isAdmin ? 'admin' : 'user.' . $user?->id),
            300,
            fn () => (clone $baseQuery)
                ->selectRaw("DISTINCT DATE_FORMAT(due_date, '%Y-%m') as ym")
                ->orderByDesc('ym')
                ->pluck('ym')
        );

        return view('pendencias.index', compact('installments', 'availableMonths', 'summary', 'isAdmin'));
    }

    public function loadMore(Request $request)
    {
        $validated = $this->validateFilters($request);
        $user = $request->user();
        $isAdmin = $this->isAdmin($user);

        $installments = $this->applyFilters($this->buildBaseQuery($user?->id, $isAdmin), $validated, $isAdmin)
            ->paginate(self::PER_PAGE);

        $html = '';
        foreach ($installments as $installment) {
            $html .= view('pendencias._card', compact('installment'))->render();
        }

        return response()->json([
            'html'    => $html,
            'hasMore' => $installments->hasMorePages(),
        ]);
    }

    public function settleInstallment(Request $request, InvoiceInstallment $installment)
    {
        $user = $request->user();
        $isAdmin = $this->isAdmin($user);

        if (!$isAdmin && $installment->invoice->user_id !== $user?->id) {
            abort(403);
        }

        DB::transaction(function () use ($installment) {
            $installment->update([
                'status' => 'paga',
                'payment_date' => now(),
            ]);
        });

        return back()->with('success', 'Baixa registrada com sucesso!');
    }

    private function validateFilters(Request $request): array
    {
        return $request->validate([
            'status' => 'nullable|in:pendente,paga,vencida',
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'search' => 'nullable|string|max:255',
            'page'   => 'nullable|integer|min:1',
        ]);
    }

    private function buildBaseQuery(?int $userId, bool $isAdmin)
    {
        $query = InvoiceInstallment::with(['invoice.user'])
            ->where('status', '!=', 'cancelada');

        if (!$isAdmin && $userId) {
            $query->whereHas('invoice', fn ($q) => $q->where('user_id', $userId));
        }

        return $query;
    }

    private function applyFilters($query, array $filters, bool $isAdmin)
    {
        if (!empty($filters['status'])) {
            $query->withEffectiveStatus($filters['status']);
        }

        if (!empty($filters['start_date']) || !empty($filters['end_date'])) {
            $start = !empty($filters['start_date'])
                ? Carbon::parse($filters['start_date'])->startOfDay()
                : Carbon::create(1900, 1, 1, 0, 0, 0);

            $end = !empty($filters['end_date'])
                ? Carbon::parse($filters['end_date'])->endOfDay()
                : Carbon::create(2999, 12, 31, 23, 59, 59);

            $query->whereBetween('due_date', [$start, $end]);
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            if ($isAdmin) {
                $digits = preg_replace('/\D+/', '', $search);
                $escaped = addcslashes($search, '%_\\');

                $query->whereHas('invoice', function ($invoiceQuery) use ($escaped, $digits) {
                    $invoiceQuery->whereHas('user', function ($userQuery) use ($escaped, $digits) {
                        $userQuery->where(function ($matchQuery) use ($escaped, $digits) {
                            $matchQuery->where('name', 'like', "%{$escaped}%");

                            if ($digits !== '') {
                                $matchQuery->orWhere('cpf', 'like', "%{$digits}%")
                                    ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), ' ', ''), '/', '') LIKE ?", ["%{$digits}%"]);
                            }
                        });
                    });
                });
            } else {
                $escaped = addcslashes($search, '%_\\');
                $query->whereHas('invoice', fn ($q) => $q->where('notes', 'like', "%{$escaped}%"));
            }
        }

        return $query->orderBy('due_date', 'desc');
    }

    private function isAdmin($user): bool
    {
        return $user && ($user->hasRole('admin') || $user->hasRole('Admin'));
    }
}
