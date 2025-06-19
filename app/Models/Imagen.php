<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    use HasFactory;
    protected $table = 'imagenes';

    protected $fillable = [
        'user_id',
        'prenda_id',
        'ruta',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prenda()
    {
        return $this->belongsTo(Prenda::class);
    }
}
