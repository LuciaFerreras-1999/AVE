<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strike extends Model
{
    protected $fillable = ['user_id', 'motivo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
