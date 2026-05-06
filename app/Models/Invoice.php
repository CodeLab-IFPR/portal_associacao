<?php

namespace App\Models;

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
}