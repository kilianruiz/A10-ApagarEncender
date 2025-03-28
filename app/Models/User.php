<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'sede_id',
        'jefe_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function jefe()
    {
        return $this->belongsTo(User::class);
    }

    public function incidencias()
    {
        return $this->belongsToMany(Incidencia::class, 'incidencia_usuario');
    }
    public function mensajesEnviados()
    {
        return $this->hasMany(Mensaje::class, 'id_usuario_emisor', 'user_id');
    }

    public function mensajesRecibidos()
    {
        return $this->hasMany(Mensaje::class, 'id_usuario_receptor', 'user_id');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
