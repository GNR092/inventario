<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipoComputo extends Model
{
    protected $table = 'equipo_computo';

    protected $fillable = [
        'publicado',
        'Fecha_AD',
        'No_Factura',
        'Proveedor',
        'Concepto',
        'especificaciones',
        'foto_path',
        'cantidad',
        'MOI',
        'MOI_Total',
        'Razon_Social',
        'RFC',
        'Folio_Externo',
        'Espacio',
        'Observacion',
        'Usuario',
        'Numero_Serie',
        'QR_Code',
        'Factura_XML',
        'Factura_PDF'
    ];

    protected $casts = [
        'Fecha_AD' => 'date',
        'MOI' => 'decimal:2',
        'MOI_Total' => 'decimal:2',
    ];

    /**
     * Calcula el Valor Actual basado en una depreciación del 30% anual.
     */
    public function getValorActualAttribute()
    {
        if (!$this->Fecha_AD || !$this->MOI) return $this->MOI;

        $tasaAnual = 0.30;
        $fechaAdquisicion = \Carbon\Carbon::parse($this->Fecha_AD);
        $hoy = \Carbon\Carbon::now();
        
        // Diferencia en años (incluyendo decimales para precisión mensual)
        $añosTranscurridos = $fechaAdquisicion->diffInMonths($hoy) / 12;
        
        $depreciacionAcumulada = $this->MOI * ($tasaAnual * $añosTranscurridos);
        $valorActual = $this->MOI - $depreciacionAcumulada;

        return max(0, $valorActual);
    }
}
