<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InventarioImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new SegmentacionImport(),
            1 => new MobiliarioEquipoImport(),
            2 => new EquipoComputoImport(),
            3 => new EquipoComunicacionImport(),
            4 => new MaquinariaEquipoImport(),
            5 => new VehiculosImport(),
        ];
    }
}
