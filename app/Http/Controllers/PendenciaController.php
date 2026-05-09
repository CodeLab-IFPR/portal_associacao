<?php

namespace App\Http\Controllers;

use App\Models\InvoiceInstallment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PendenciaController extends Controller
{
    private const PER_PAGE = 15;

    public function index(Request $request)
    {
        $validated = $this->validateFilters($request);
        $userId = Auth::id();

        $installments = $this->buildQuery($userId, $validated)
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $availableMonths = Cache::remember(
            "pendencias.available_months.user.{$userId}",
            300,
            fn () => InvoiceInstallment::whereHas('invoice', fn ($q) => $q->where('user_id', $userId))
                ->selectRaw("DISTINCT DATE_FORMAT(due_date, '%Y-%m') as ym")
                ->orderByDesc('ym')
                ->pluck('ym')
        );

        $upcomingCount = InvoiceInstallment::whereHas('invoice', fn ($q) => $q->where('user_id', $userId))
            ->whereBetween('due_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->where('status', 'pendente')
            ->count();

        return view('pendencias.index', compact('installments', 'availableMonths', 'upcomingCount'));
    }

    public function loadMore(Request $request)
    {
        $validated = $this->validateFilters($request);
        $userId = Auth::id();

        $installments = $this->buildQuery($userId, $validated)
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

    private function validateFilters(Request $request): array
    {
        return $request->validate([
            'status' => 'nullable|in:pendente,paga,vencida',
            'month'  => 'nullable|regex:/^\d{4}-(0[1-9]|1[0-2])$/',
            'search' => 'nullable|string|max:255',
            'page'   => 'nullable|integer|min:1',
        ]);
    }

    private function buildQuery(int $userId, array $filters)
    {
        $query = InvoiceInstallment::with('invoice')
            ->whereHas('invoice', fn ($q) => $q->where('user_id', $userId))
            ->where('status', '!=', 'cancelada');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['month'])) {
            $start = Carbon::createFromFormat('Y-m-d', $filters['month'] . '-01')->startOfDay();
            $end   = $start->copy()->endOfMonth();
            $query->whereBetween('due_date', [$start, $end]);
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            if (is_numeric($search)) {
                $query->where('amount', $search);
            } else {
                $escaped = addcslashes($search, '%_\\');
                $query->whereHas('invoice', fn ($q) => $q->where('notes', 'like', "%{$escaped}%"));
            }
        }

        return $query->orderBy('due_date', 'desc');
    }
}
