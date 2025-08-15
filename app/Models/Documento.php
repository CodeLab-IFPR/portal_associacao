<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nome_arquivo',
        'nome_original',
        'caminho_arquivo',
        'tipo_documento',
        'descricao',
        'status',
        'aprovado_por',
        'aprovado_em',
        'observacoes'
    ];

    protected $casts = [
        'aprovado_em' => 'datetime',
    ];

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com o usuário que aprovou
    public function aprovador()
    {
        return $this->belongsTo(User::class, 'aprovado_por');
    }

    // Scopes
    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeAprovados($query)
    {
        return $query->where('status', 'aprovado');
    }

    public function scopeRejeitados($query)
    {
        return $query->where('status', 'rejeitado');
    }

    // Acessors
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pendente' => 'Pendente',
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado'
        ];

        return $labels[$this->status] ?? 'Desconhecido';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pendente' => 'warning',
            'aprovado' => 'success',
            'rejeitado' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }
}
