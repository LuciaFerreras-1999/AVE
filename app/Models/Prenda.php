<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'categoria_id',
        'nombre',
        'slug',
        'descripcion',
        'talla',
        'marca',
        'estado',
        'imagen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'categoria_prenda');
    }

    public const TALLAS = ['18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', 'XS', 'S', 'M', 'L', 'XL', 'XXL',];

    public function imagenes()
    {
        return $this->hasMany(Imagen::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function favoritos()
    {
        return $this->belongsToMany(Prenda::class, 'favoritos')->withTimestamps();
    }

    public function favoritoPorUsuario()
    {
        return $this->belongsToMany(User::class, 'favoritos')->withTimestamps();
    }

    public function getFavoritoPorUsuarioAttribute()
    {
        return auth()->check() && auth()->user()->favoritos()->where('prenda_id', $this->id)->exists();
    }

    public function favoritosCount()
    {
        return $this->favoritos()->count();
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class)->latest();
    }

    public function compras()
    {
        return $this->hasMany(Compra::class, 'prenda_id');
    }
}
