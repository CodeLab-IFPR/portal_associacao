<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceInstallment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['user', 'installments'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $members = User::role('Membro')->orderBy('name')->get();

        return view('invoices.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_ids'           => 'required|array|min:1',
            'user_ids.*'         => 'exists:users,id',
            'installment_amount' => 'required|numeric|min:0.01',
            'installments_count' => 'required|integer|min:1|max:60',
            'first_due_date'     => 'required|date',
            'periodicity'        => 'required|in:semanal,quinzenal,mensal,bimestral,trimestral,semestral,anual',
            'notes'              => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['user_ids'] as $userId) {
                $totalAmount = $validated['installment_amount'] * $validated['installments_count'];

                $invoice = Invoice::create([
                    'user_id'            => $userId,
                    'total_amount'       => $totalAmount,
                    'installments_count' => $validated['installments_count'],
                    'periodicity'        => $validated['periodicity'],
                    'first_due_date'     => $validated['first_due_date'],
                    'status'             => 'pendente',
                    'notes'              => $validated['notes'],
                ]);

                $this->generateInstallments($invoice, $validated);
            }

            DB::commit();

            return redirect()->route('invoices.index')
                ->with('success', 'Faturas criadas com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Erro ao criar faturas: ' . $e->getMessage());
        }
    }

    private function generateInstallments(Invoice $invoice, array $data)
    {
        $dueDate = Carbon::parse($data['first_due_date']);

        for ($i = 1; $i <= $data['installments_count']; $i++) {
            InvoiceInstallment::create([
                'invoice_id'         => $invoice->id,
                'installment_number' => $i,
                'amount'             => $data['installment_amount'],
                'due_date'           => $dueDate->copy(),
                'status'             => 'pendente',
            ]);

            $dueDate = $this->calculateNextDueDate($dueDate, $data['periodicity']);
        }
    }

    private function calculateNextDueDate(Carbon $currentDate, string $periodicity): Carbon
    {
        return match ($periodicity) {
            'semanal'    => $currentDate->addWeek(),
            'quinzenal'  => $currentDate->addWeeks(2),
            'mensal'     => $currentDate->addMonth(),
            'bimestral'  => $currentDate->addMonths(2),
            'trimestral' => $currentDate->addMonths(3),
            'semestral'  => $currentDate->addMonths(6),
            'anual'      => $currentDate->addYear(),
        };
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['user', 'installments']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $members = User::role('Membro')->orderBy('name')->get();

        return view('invoices.edit', compact('invoice', 'members'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Fatura atualizada com sucesso!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Fatura excluída com sucesso!');
    }

    // ──────────────────────────────────────────────────────────
    // Parcelas individuais
    // ──────────────────────────────────────────────────────────

    public function updateInstallment(Request $request, Invoice $invoice, InvoiceInstallment $installment)
    {
        $validated = $request->validate([
            'amount'             => 'required|numeric|min:0.01',
            'installments_count' => 'required|integer|min:1|max:60',
            'due_date'           => 'required|date',
            'payment_date'       => 'nullable|date',
            'status'             => 'required|in:pendente,paga,vencida,cancelada',
        ]);

        DB::beginTransaction();

        try {
            $installment->update([
                'amount'       => $validated['amount'],
                'due_date'     => $validated['due_date'],
                'payment_date' => $validated['payment_date'] ?? null,
                'status'       => $validated['status'],
            ]);

            // Atualiza installments_count e recalcula total na fatura pai
            $invoice->update([
                'installments_count' => $validated['installments_count'],
                'total_amount'       => $validated['amount'] * $validated['installments_count'],
            ]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice->id)
                ->with('success', 'Parcela atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Erro ao atualizar parcela: ' . $e->getMessage());
        }
    }

    public function destroyInstallment(Invoice $invoice, InvoiceInstallment $installment)
    {
        DB::beginTransaction();

        try {
            $installment->delete();

            // Recalcula total e installments_count na fatura pai
            $remaining = $invoice->installments()->count();
            $newTotal  = $invoice->installments()->sum('amount');

            $invoice->update([
                'installments_count' => $remaining,
                'total_amount'       => $newTotal,
            ]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice->id)
                ->with('success', 'Parcela excluída com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Erro ao excluir parcela: ' . $e->getMessage());
        }
    }

    public function uploadBoleto(Request $request, Invoice $invoice, InvoiceInstallment $installment)
    {
        // Garantir que a parcela pertence à fatura (segurança básica)
        if ($installment->invoice_id !== $invoice->id) {
            abort(404);
        }

        $validated = $request->validate([
            'boleto' => 'required|file|mimes:pdf|max:5120', // 5MB
        ]);

        $path = $validated['boleto']->store('boletos', 'public');

        // Apagar boleto antigo
        if ($installment->boleto_path) {
            Storage::disk('public')->delete($installment->boleto_path);
        }

        $installment->update([
            'boleto_path' => $path,
        ]);

        return redirect()
            ->route('invoices.show', $invoice->id)
            ->with('success', 'Boleto anexado com sucesso!');
    }
}