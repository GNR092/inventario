<?php

namespace App\Http\Controllers;

use App\Models\EquipoComunicacion;
use App\Services\CfdiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;

class EquipoComunicacionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX + FILTROS
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = EquipoComunicacion::query();

        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);

            $query->where(function ($q) use ($buscar) {
                if (is_numeric($buscar)) {
                    $q->orWhere('id', $buscar);
                }

                $q->orWhere('Razon_Social', 'like', "%$buscar%")
                  ->orWhere('RFC', 'like', "%$buscar%")
                  ->orWhere('Concepto', 'like', "%$buscar%")
                  ->orWhere('Proveedor', 'like', "%$buscar%")
                  ->orWhere('No_Factura', 'like', "%$buscar%");
            });
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('Fecha_AD', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('Fecha_AD', '<=', $request->fecha_fin);
        }

        $items = $query->orderByDesc('id')->get();

        return view('comunicacion.index', compact('items'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('comunicacion.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'Factura_XML' => 'nullable|file|mimes:xml|max:10240',
            'factura'     => 'required|file|mimes:pdf,xml|max:5120',
            'cantidad'    => 'nullable|integer|min:0',
            'MOI'         => 'nullable|numeric|min:0'
        ]);

        $data = $request->only([
            'Fecha_AD','No_Factura','Proveedor','Concepto','cantidad',
            'Espacio','Observacion','MOI','MOI_Total','Usuario',
            'Folio_Externo','RFC','Razon_Social','Numero_Serie'
        ]);

        /*
        |--------------------------------------------------------------------------
        | PROCESAR XML SI EXISTE
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('Factura_XML')) {

            $cfdi = new CfdiService();

            $xmlData = $cfdi->procesarXml(
                $request->file('Factura_XML'),
                'comunicacion'
            );

            $data = array_merge($data, $xmlData);
        }

        /*
        |--------------------------------------------------------------------------
        | GUARDAR FACTURA MANUAL
        |--------------------------------------------------------------------------
        */
        $data['pdf_path'] = $request->file('factura')
            ->store('facturas/comunicacion', 'public');

        /*
        |--------------------------------------------------------------------------
        | CREAR REGISTRO
        |--------------------------------------------------------------------------
        */
        $item = EquipoComunicacion::create($data);

        $this->generarQr($item);

        return redirect()
            ->route('comunicacion.index')
            ->with('success', 'Equipo de comunicación registrado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $item = EquipoComunicacion::findOrFail($id);
        return view('comunicacion.edit', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $item = EquipoComunicacion::findOrFail($id);

        $request->validate([
            'Factura_XML' => 'nullable|file|mimes:xml|max:10240',
            'factura'     => 'nullable|file|mimes:pdf,xml|max:5120',
            'cantidad'    => 'nullable|integer|min:0',
            'MOI'         => 'nullable|numeric|min:0'
        ]);

        $data = $request->only([
            'Fecha_AD','No_Factura','Proveedor','Concepto','cantidad',
            'Espacio','Observacion','MOI','MOI_Total','Usuario',
            'Folio_Externo','RFC','Razon_Social','Numero_Serie'
        ]);

        /*
        |--------------------------------------------------------------------------
        | PROCESAR XML NUEVO
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('Factura_XML')) {

            $cfdi = new CfdiService();

            $xmlData = $cfdi->procesarXml(
                $request->file('Factura_XML'),
                'comunicacion'
            );

            $data = array_merge($data, $xmlData);
        }

        /*
        |--------------------------------------------------------------------------
        | REEMPLAZAR FACTURA
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('factura')) {

            if ($item->factura_path &&
                Storage::disk('public')->exists($item->factura_path)) {

                Storage::disk('public')->delete($item->factura_path);
            }

            $data['factura_path'] = $request->file('factura')
                ->store('facturas/comunicacion', 'public');
        }

        $item->update($data);

        $this->generarQr($item);

        return redirect()
            ->route('comunicacion.index')
            ->with('success', 'Equipo de comunicación actualizado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $item = EquipoComunicacion::findOrFail($id);

        foreach (['factura_path','Factura_XML','Factura_PDF','QR_Code'] as $field) {
            if ($item->$field &&
                Storage::disk('public')->exists($item->$field)) {

                Storage::disk('public')->delete($item->$field);
            }
        }

        $item->delete();

        return redirect()
            ->route('comunicacion.index')
            ->with('success', 'Equipo de comunicación eliminado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | FICHA PÚBLICA
    |--------------------------------------------------------------------------
    */
    public function publico($id)
    {
        $item = EquipoComunicacion::findOrFail($id);
        return view('comunicacion.publico', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | VER QR
    |--------------------------------------------------------------------------
    */
    public function verQr($id)
    {
        $item = EquipoComunicacion::findOrFail($id);
        return view('comunicacion.ver_qr', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | GENERAR QR INSTITUCIONAL
    |--------------------------------------------------------------------------
    */
    private function generarQr($item)
    {
        $urlPublica = route('comunicacion.publico', $item->id);
        $qrRelative = 'qr/comunicacion_' . $item->id . '.png';

        $qrCode = new QrCode(
            data: $urlPublica,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 350,
            margin: 5,
            foregroundColor: new Color(40, 40, 40),
            backgroundColor: new Color(255, 255, 255)
        );

        $logo = new Logo(
            path: public_path('logo/Logo-MB.png'),
            resizeToWidth: 100,
            resizeToHeight: 100
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode, $logo);

        Storage::disk('public')->put($qrRelative, $result->getString());

        $item->QR_Code = $qrRelative;
        $item->save();
    }
}
