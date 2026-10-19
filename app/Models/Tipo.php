<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tipo extends Model
{
    protected $table = 'tipos';

    protected $fillable = [
        'nombre',
        'imagen',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    // Un tipo puede estar relacionado con muchos productos.
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'tipo_id');
    }
}