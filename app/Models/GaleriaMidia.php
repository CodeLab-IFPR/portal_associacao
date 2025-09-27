<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaleriaMidia extends Model
{
    use HasFactory;
    protected $fillable = ['galeria_id', 'tipo', 'caminho'];

    public function galeria(): BelongsTo
    {
        return $this->belongsTo(Galeria::class);
    }
}
