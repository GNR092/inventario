<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobiliarioEquipo extends Model
{
    protected $table = 'mobiliario_equipo';

    public $timestamps = false;

    protected $fillable = [
        'Fecha_AD',
        'No_Factura',
        'Proveedor',
        'Concepto',
        'Cantidad',
        'Espacio',
        'Observacion',
        'MOI',
        'factura_path',
        'QR_Code',
        'Usuario',
        'Folio_Externo',
        'RFC',
        'Razon_Social',
        'Factura_XML',   // 🔥 NECESARIO
        'Factura_PDF'    // 🔥 NECESARIO
    ];

    protected $casts = [
        'MOI' => 'decimal:2',
        'Cantidad' => 'integer'
    ];

    /**
     * Calcula el Valor Actual basado en una depreciación del 10% anual.
     */
    public function getValorActualAttribute()
    {
        if (!$this->Fecha_AD || !$this->MOI) return $this->MOI;

        $tasaAnual = 0.10;
        $fechaAdquisicion = \Carbon\Carbon::parse($this->Fecha_AD);
        $hoy = \Carbon\Carbon::now();
        
        $añosTranscurridos = $fechaAdquisicion->diffInMonths($hoy) / 12;
        
        $depreciacionAcumulada = $this->MOI * ($tasaAnual * $añosTranscurridos);
        $valorActual = $this->MOI - $depreciacionAcumulada;

        return max(0, $valorActual);
    }

    public function getQrCodeAttribute()
    {
        return $this->attributes['QR_Code'] ?? null;
    }

    public function setQrCodeAttribute($value)
    {
        $this->attributes['QR_Code'] = $value;
    }
}
