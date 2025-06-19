<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'prenda_id', 'precio', 'fecha_compra'];

    protected $casts = [
        'fecha_compra' => 'datetime',
    ];


    public function prenda()
    {
        return $this->belongsTo(Prenda::class, 'prenda_id');
    }
}
