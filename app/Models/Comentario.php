<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'contenido',
        'prenda_id',
        'user_id',
    ];
    public function prenda()
    {
        return $this->belongsTo(Prenda::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
