<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{

    protected $table = 'vehiculo';

    protected $fillable = [
        'Fecha_AD',
        'No_Factura',
        'Proveedor',
        'Concepto',
        'Cantidad',
        'Observacion',
        'MOI',
        'MOI_Total',
        'Razon_Social',
        'RFC',
        'Folio_Externo',
        'Espacio',
        'Usuario',
        'Numero_Serie',
        'QR_Code',
        'Factura_XML',
        'Factura_PDF',
        'pdf_path'
    ];

    protected $casts = [
        'Fecha_AD' => 'date',
        'MOI' => 'decimal:2',
        'MOI_Total' => 'decimal:2',
    ];

    /**
     * Calcula el Valor Actual basado en una depreciación del 25% anual.
     */
    public function getValorActualAttribute()
    {
        if (!$this->Fecha_AD || !$this->MOI) return $this->MOI;

        $tasaAnual = 0.25;
        $fechaAdquisicion = \Carbon\Carbon::parse($this->Fecha_AD);
        $hoy = \Carbon\Carbon::now();
        
        $añosTranscurridos = $fechaAdquisicion->diffInMonths($hoy) / 12;
        
        $depreciacionAcumulada = $this->MOI * ($tasaAnual * $añosTranscurridos);
        $valorActual = $this->MOI - $depreciacionAcumulada;

        return max(0, $valorActual);
    }
}
