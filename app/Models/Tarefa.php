<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'status', 'certificado_gerado', 'projeto_id', 'user_id', 'checkbox_estado'];

    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function atividades()
    {
        return $this->hasMany(Atividade::class);
    }
    
}
