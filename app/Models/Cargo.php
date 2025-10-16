<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao'
    ];

    protected $table = 'cargos';

    public function users()
    {
        return $this->hasMany(User::class, 'cargo_id');
    }
}
