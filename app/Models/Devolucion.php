<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Devolucion extends Model
{
    use HasFactory;

    protected $table = 'devoluciones';

    protected $fillable = [
        'renta_id', 'fecha', 'observacion', 'cargo_extra', 'imagen', 'estado'
    ];

    public function renta()
    {
        return $this->belongsTo(Renta::class);
    }
}
