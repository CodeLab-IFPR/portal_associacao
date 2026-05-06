<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceInstallment extends Model
{
    use HasFactory;

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

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}