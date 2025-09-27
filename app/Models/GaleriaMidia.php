<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriaMidia extends Model
{
    use HasFactory;
    protected $fillable = ['galeria_id', 'tipo', 'caminho'];

    public function galeria()
    {
        return $this->belongsTo(Galeria::class);
    }
}
