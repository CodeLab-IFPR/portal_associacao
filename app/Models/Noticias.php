<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Noticias extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'autor',
        'conteudo',
        'imagem',
        'categoria',
        'alt',
    ];
}
