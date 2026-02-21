<?php

namespace App\Imports;

use App\Models\Segmentacion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SegmentacionImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Segmentacion([
            'fecha' => $row['fecha'] ?? null,
            'factura' => $row['factura'] ?? null,
            'proveedor' => $row['proveedor'] ?? null,
            'concepto' => $row['concepto'] ?? null,
            'cantidad' => $row['cantidad'] ?? null,
            'espacio' => $row['espacio'] ?? null,
            'observacion' => $row['observacion'] ?? null,
            'moi' => $row['moi'] ?? null,

            'estado' => 'Activo',
            'numero_serie' => null,
            'qr_code' => null,

            'mes_compra' => null,
            'anio_compra' => null,

            'factura_pdf' => null,
            'factura_xml' => null,
            'comentarios' => null,
        ]);
    }
}
