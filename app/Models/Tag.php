<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function projetos()
    {
        return $this->belongsToMany(Projeto::class);
    }
}
