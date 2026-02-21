<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activo extends Model
{
    use HasFactory;

    protected $fillable = [
        'razon_social',
        'departamento',
        'area',
        'complejo',
        'categoria',
        'tipo_aparato',
        'precio',
        'depreciacion',
        'fecha_compra',
        'mes_compra',
        'anio_compra',
        'numero_serie',
        'qr_code',
        'estado',
        'comentarios',
        'factura_pdf',
        'factura_xml'
    ];
}
