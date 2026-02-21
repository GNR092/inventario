<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\SegmentacionImport;
use App\Imports\MobiliarioEquipoImport;
use App\Imports\EquipoComputoImport;
use App\Imports\EquipoComunicacionImport;
use App\Imports\MaquinariaEquipoImport;
use App\Imports\VehiculoImport;

class ImportarInventarioExcel extends Command
{
    protected $signature = 'inventario:importar';
    protected $description = 'Importa el inventario desde el archivo Excel';

    public function handle()
    {
        $ruta = storage_path('app/inventario.xlsx');

        if (!file_exists($ruta)) {
            $this->error("No se encontró el archivo en: $ruta");
            return;
        }

        Excel::import(new SegmentacionImport, $ruta, null, \Maatwebsite\Excel\Excel::XLSX);
        Excel::import(new MobiliarioEquipoImport, $ruta, null, \Maatwebsite\Excel\Excel::XLSX);
        Excel::import(new EquipoComputoImport, $ruta, null, \Maatwebsite\Excel\Excel::XLSX);
        Excel::import(new EquipoComunicacionImport, $ruta, null, \Maatwebsite\Excel\Excel::XLSX);
        Excel::import(new MaquinariaEquipoImport, $ruta, null, \Maatwebsite\Excel\Excel::XLSX);
        Excel::import(new VehiculoImport, $ruta, null, \Maatwebsite\Excel\Excel::XLSX);

        $this->info("Inventario importado correctamente.");
    }
}
