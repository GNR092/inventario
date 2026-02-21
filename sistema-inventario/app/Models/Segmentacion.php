<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segmentacion extends Model
{
    protected $table = 'segmentacion';

    /*
    |--------------------------------------------------------------------------
    | TIMESTAMPS ACTIVOS
    |--------------------------------------------------------------------------
    */
    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'Fecha_AD',
        'area',
        'Cantidad',
        'estado',
        'activo',
        'Concepto',
        'Razon_Social',
        'No_Factura',
        'RFC',
        'Folio_Externo',
        'Proveedor',
        'Espacio',
        'Observacion',
        'MOI',
        'Usuario',
        'Numero_Serie',
        'pdf_path',
        'Factura_XML',
        'Factura_PDF',
        'QR_Code'
        // NO incluimos MOI_Total porque lo calcula el TRIGGER
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'Fecha_AD'  => 'date',
        'MOI'       => 'decimal:2',
        'MOI_Total' => 'decimal:2',
        'Cantidad'  => 'integer'
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

    /*
    |--------------------------------------------------------------------------
    | Accessor QR limpio
    |--------------------------------------------------------------------------
    */

    public function getQrCodeAttribute()
    {
        return $this->attributes['QR_Code'] ?? null;
    }

    public function setQrCodeAttribute($value)
    {
        $this->attributes['QR_Code'] = $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor: MOI Total formateado
    |--------------------------------------------------------------------------
    */

    public function getMoiTotalFormateadoAttribute()
    {
        return number_format($this->MOI_Total ?? 0, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor: MOI Unitario formateado
    |--------------------------------------------------------------------------
    */

    public function getMoiFormateadoAttribute()
    {
        return number_format($this->MOI ?? 0, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor: Ruta pública del QR
    |--------------------------------------------------------------------------
    */

    public function getQrUrlAttribute()
    {
        return $this->QR_Code
            ? asset('storage/' . $this->QR_Code)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor: Ruta pública de Factura
    |--------------------------------------------------------------------------
    */

    public function getFacturaUrlAttribute()
    {
        return $this->factura_path
            ? asset('storage/' . $this->factura_path)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor: Ruta pública XML
    |--------------------------------------------------------------------------
    */

    public function getXmlUrlAttribute()
    {
        return $this->Factura_XML
            ? asset('storage/' . $this->Factura_XML)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor: Ruta pública PDF generado
    |--------------------------------------------------------------------------
    */

    public function getPdfUrlAttribute()
    {
        return $this->Factura_PDF
            ? asset('storage/' . $this->Factura_PDF)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper: Estado visual
    |--------------------------------------------------------------------------
    */

    public function getEstadoBadgeAttribute()
    {
        return match($this->estado) {
            'Activo' => 'success',
            'Baja'   => 'danger',
            'Mantenimiento' => 'warning',
            default  => 'secondary'
        };
    }
}
