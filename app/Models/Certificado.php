<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    use HasFactory;

    protected $fillable = ['users_id', 'token', 'descricao', 'horas', 'data'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}