<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Galeria extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'caminho',
        'tipo',
        'link',
        'ano',
        'data_inicio_evento',
        'data_fim_evento',
    ];

    protected $casts = [
        'ano' => 'integer',
        'data_inicio_evento' => 'date',
        'data_fim_evento' => 'date',
    ];

    public function midias(): HasMany {
        return $this->hasMany(GaleriaMidia::class);
    }
}
