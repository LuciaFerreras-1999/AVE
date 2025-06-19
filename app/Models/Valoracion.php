<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Valoracion extends Model
{
    use HasFactory;
    protected $table = 'valoraciones';

    protected $fillable = [
        'valorador_id',
        'valorado_id',
        'puntuacion',
        'comentario',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'valorador_id');
    }
}
