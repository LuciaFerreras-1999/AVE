<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $fillable = ['prenda_id', 'user_id', 'motivo'];

    public function usuarioReporta()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prenda()
    {
        return $this->belongsTo(Prenda::class);
    }
}
