<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'installments_count',
        'periodicity',
        'first_due_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'first_due_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function installments()
    {
        return $this->hasMany(InvoiceInstallment::class);
    }

    /**
     * Status da fatura a partir das parcelas (a baixa é feita por parcela,
     * então a coluna status só vale para "cancelada").
     */
    public function getEffectiveStatusAttribute(): string
    {
        if ($this->status === 'cancelada') {
            return 'cancelada';
        }

        $parcelas = $this->installments->where('status', '!=', 'cancelada');

        if ($parcelas->isEmpty()) {
            return $this->status;
        }

        $abertas = $parcelas->whereIn('status', InvoiceInstallment::OPEN_STATUSES);

        if ($abertas->isEmpty()) {
            return 'paga';
        }

        return $abertas->contains(fn ($p) => $p->effective_status === 'vencida') ? 'vencida' : 'pendente';
    }

    public function scopeWithEffectiveStatus(Builder $query, string $status): Builder
    {
        if ($status === 'cancelada') {
            return $query->where('status', 'cancelada');
        }

        $query->where('status', '!=', 'cancelada');

        return match ($status) {
            'paga' => $query
                ->whereHas('installments', fn ($q) => $q->where('status', 'paga'))
                ->whereDoesntHave('installments', fn ($q) => $q->whereIn('status', InvoiceInstallment::OPEN_STATUSES)),
            'vencida' => $query
                ->whereHas('installments', fn ($q) => $q->withEffectiveStatus('vencida')),
            'pendente' => $query
                ->whereHas('installments', fn ($q) => $q->withEffectiveStatus('pendente'))
                ->whereDoesntHave('installments', fn ($q) => $q->withEffectiveStatus('vencida')),
            default => $query,
        };
    }
}
