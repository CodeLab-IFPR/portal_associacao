<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceInstallment extends Model
{
    use HasFactory;

    public const OPEN_STATUSES = ['pendente', 'vencida'];

    protected $fillable = [
        'invoice_id',
        'installment_number',
        'amount',
        'due_date',
        'status',
        'payment_date',
        'boleto_path'
    ];

    protected $casts = [
        'due_date' => 'date',
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function getEffectiveStatusAttribute(): string
    {
        if (!in_array($this->status, self::OPEN_STATUSES, true)) {
            return $this->status;
        }

        // O vencimento de hoje permanece em aberto até o fim do dia local.
        return $this->due_date->lt(today()) ? 'vencida' : 'pendente';
    }

    public function scopeWithEffectiveStatus(Builder $query, string $status): Builder
    {
        if (in_array($status, self::OPEN_STATUSES, true)) {
            return $query->whereIn('status', self::OPEN_STATUSES)
                ->where('due_date', $status === 'vencida' ? '<' : '>=', today()->toDateString());
        }

        return $query->where('status', $status);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
