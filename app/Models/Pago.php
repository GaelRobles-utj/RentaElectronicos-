<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'renta_id', 'fecha', 'metodo', 'monto', 'referencia', 'comprobante', 'estado'
    ];

    public function renta()
    {
        return $this->belongsTo(Renta::class);
    }
}
