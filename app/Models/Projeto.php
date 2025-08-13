<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'descricao', 'status', 'imagem'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

}

