<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'avatar',
        'phone',
        'address',
        'bloqueado',
    ];

    public function imagenes()
    {
        return $this->hasMany(Imagen::class);
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function favoritos()
    {
        return $this->belongsToMany(Prenda::class, 'favoritos')->withTimestamps();
    }

    public function prendas()
    {
        return $this->hasMany(Prenda::class);
    }

    public function strikes()
    {
        return $this->hasMany(Strike::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'valorador_id');
    }

    public function valoracionesRecibidas()
    {
        return $this->hasMany(Valoracion::class, 'valorado_id');
    }

    public function valoracionesHechas()
    {
        return $this->hasMany(Valoracion::class, 'valorador_id');
    }

    public function promedioValoracion()
    {
        return $this->valoracionesRecibidas()->avg('puntuacion');
    }

    public function totalValoraciones()
    {
        return $this->valoracionesRecibidas()->count();
    }



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
}
