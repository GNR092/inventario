<?php

namespace App\Imports;

use App\Models\Vehiculo;
use Maatwebsite\Excel\Concerns\ToModel;

class VehiculoImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Vehiculo([
            //
        ]);
    }
}
