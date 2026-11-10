<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Administrador extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'administradores';

    protected $fillable = [
        'nombres',
        'apellidos',
        'correo',
        'usuario',
        'contrasena',
        'imagen',
        'rol',
        'estado',
    ];

    protected $hidden = [
        'contrasena',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function getAuthPasswordName()
    {
        return 'contrasena';
    }

    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}