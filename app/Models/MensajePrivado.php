<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensajePrivado extends Model
{
    use HasFactory;

    protected $fillable = [
        'emisor_id',
        'receptor_id',
        'mensaje',
        'leido',
        'es_strike',
    ];

    public function emisor()
    {
        return $this->belongsTo(User::class, 'emisor_id');
    }

    public function receptor()
    {
        return $this->belongsTo(User::class, 'receptor_id');
    }
}
