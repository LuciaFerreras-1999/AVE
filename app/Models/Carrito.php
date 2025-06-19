<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prenda_id',
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
