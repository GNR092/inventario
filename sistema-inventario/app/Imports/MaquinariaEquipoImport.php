<?php

namespace App\Imports;

use App\Models\MaquinariaEquipo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MaquinariaEquipoImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new MaquinariaEquipo([
            'fecha' => $row['fecha'] ?? null,
            'factura' => $row['factura'] ?? null,
            'proveedor' => $row['proveedor'] ?? null,
            'concepto' => $row['concepto'] ?? null,
            'cantidad' => $row['cantidad'] ?? null,

            'marca' => $row['marca'] ?? null,
            'modelo' => $row['modelo'] ?? null,

            'espacio' => $row['espacio'] ?? null,
            'observacion' => $row['observacion'] ?? null,

            'moi' => $row['moi'] ?? null,
            'precio' => $row['precio'] ?? null,
            'depreciacion' => $row['depreciacion'] ?? null,

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
