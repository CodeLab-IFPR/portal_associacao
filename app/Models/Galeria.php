<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'caminho',
        'tipo',
        'link',
        'ano',
    ];

    protected $casts = [
        'ano' => 'integer',
    ];
}
