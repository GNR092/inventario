<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImportExcelController;

use App\Http\Controllers\SegmentacionController;
use App\Http\Controllers\MobiliarioEquipoController;
use App\Http\Controllers\EquipoComputoController;
use App\Http\Controllers\EquipoComunicacionController;
use App\Http\Controllers\MaquinariaEquipoController;
use App\Http\Controllers\VehiculoController;

/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('welcome');


/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (DESTINO DEL QR)
|--------------------------------------------------------------------------
*/

Route::get('/segmentacion/publico/{id}',
    [SegmentacionController::class, 'publico']
)->name('segmentacion.publico');

Route::get('/mobiliario/publico/{id}',
    [MobiliarioEquipoController::class, 'publico']
)->name('mobiliario.publico');

Route::get('/computo/publico/{id}',
    [EquipoComputoController::class, 'publico']
)->name('computo.publico');

Route::get('/comunicacion/publico/{id}',
    [EquipoComunicacionController::class, 'publico']
)->name('comunicacion.publico');

Route::get('/maquinaria/publico/{id}',
    [MaquinariaEquipoController::class, 'publico']
)->name('maquinaria.publico');

Route::get('/vehiculos/publico/{id}',
    [VehiculoController::class, 'publico']
)->name('vehiculos.publico');

Route::get('/catalogo',
    [\App\Http\Controllers\PublicCatalogController::class, 'index']
)->name('publico.catalogo');


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | RUTAS QR (ANTES DEL RESOURCE PARA EVITAR CONFLICTOS)
    |--------------------------------------------------------------------------
    */

    Route::get('/segmentacion/qr/{id}',
        [SegmentacionController::class, 'verQr']
    )->name('segmentacion.qr');

    Route::get('/mobiliario/qr/{id}',
        [MobiliarioEquipoController::class, 'verQr']
    )->name('mobiliario.qr');

    Route::get('/computo/qr/{id}',
        [EquipoComputoController::class, 'verQr']
    )->name('computo.qr');

    Route::get('/comunicacion/qr/{id}',
        [EquipoComunicacionController::class, 'verQr']
    )->name('comunicacion.qr');

    Route::get('/maquinaria/qr/{id}',
        [MaquinariaEquipoController::class, 'verQr']
    )->name('maquinaria.qr');

    Route::get('/vehiculos/qr/{id}',
        [VehiculoController::class, 'verQr']
    )->name('vehiculos.qr');


    /*
    |--------------------------------------------------------------------------
    | MÓDULOS INVENTARIO (CRUD)
    |--------------------------------------------------------------------------
    */

    Route::resource('segmentacion', SegmentacionController::class);
    Route::resource('mobiliario', MobiliarioEquipoController::class);
    Route::resource('computo', EquipoComputoController::class);
    Route::resource('comunicacion', EquipoComunicacionController::class);
    Route::resource('maquinaria', MaquinariaEquipoController::class);
    Route::resource('vehiculos', VehiculoController::class);


    /*
    |--------------------------------------------------------------------------
    | IMPORTAR EXCEL
    |--------------------------------------------------------------------------
    */

    Route::get('/importar', function () {
        return view('importar');
    })->name('importar');

    Route::post('/importar-excel',
        [ImportExcelController::class, 'importar']
    )->name('importar.excel');

});

require __DIR__.'/auth.php';
