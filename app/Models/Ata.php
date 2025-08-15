<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ata extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'arquivo',
        'arquivo_original'
    ];

    /**
     * Get the URL for the ATA file
     */
    public function getArquivoUrlAttribute()
    {
        if ($this->arquivo) {
            return asset('storage/atas/' . $this->arquivo);
        }
        return null;
    }

    /**
     * Get formatted created date
     */
    public function getDataCriadaAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }
}
