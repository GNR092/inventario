<?php

namespace App\Http\Controllers;

use App\Models\EquipoComputo;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    public function index()
    {
        // Solo mostramos los marcados como publicados
        $equipos = EquipoComputo::where('publicado', true)
            ->orderByDesc('id')
            ->get();

        return view('publico.catalogo', compact('equipos'));
    }
}
