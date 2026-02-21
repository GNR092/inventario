<?php

namespace App\Http\Controllers;

use App\Models\Segmentacion;
use App\Services\CfdiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;

class SegmentacionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX + FILTROS
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Segmentacion::query();

        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);

            $query->where(function ($q) use ($buscar) {

                if (is_numeric($buscar)) {
                    $q->orWhere('id', $buscar);
                }

                $q->orWhere('Numero_Serie', 'like', "%$buscar%")
                  ->orWhere('area', 'like', "%$buscar%")
                  ->orWhere('Concepto', 'like', "%$buscar%")
                  ->orWhere('Proveedor', 'like', "%$buscar%");
            });
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('Fecha_AD', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('Fecha_AD', '<=', $request->fecha_fin);
        }

        $items = $query->orderByDesc('id')->get();

        return view('segmentacion.index', compact('items'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('segmentacion.create');
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
            'factura'     => 'nullable|file|mimes:pdf|max:5120',
            'Cantidad'    => 'nullable|integer|min:0',
            'MOI'         => 'nullable|numeric|min:0'
        ]);

        $data = $request->only([
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
            'Numero_Serie'
        ]);

        /*
        |--------------------------------------------------------------------------
        | PROCESAR XML CFDI
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('Factura_XML')) {

            $cfdi = new CfdiService();

            $xmlData = $cfdi->procesarXml(
                $request->file('Factura_XML'),
                'segmentacion'
            );

            $data = array_merge($data, $xmlData);

            $data['Factura_XML'] = $request->file('Factura_XML')
                ->store('xml/segmentacion', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | GUARDAR PDF MANUAL
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('factura')) {

            $data['pdf_path'] = $request->file('factura')
                ->store('pdf/segmentacion', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | CREAR REGISTRO
        |--------------------------------------------------------------------------
        */
        $item = Segmentacion::create($data);

        $this->generarQr($item);

        return redirect()
            ->route('segmentacion.index')
            ->with('success', 'Registro creado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $item = Segmentacion::findOrFail($id);
        return view('segmentacion.edit', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $item = Segmentacion::findOrFail($id);

        $request->validate([
            'Factura_XML' => 'nullable|file|mimes:xml|max:10240',
            'factura'     => 'nullable|file|mimes:pdf|max:5120',
            'Cantidad'    => 'nullable|integer|min:0',
            'MOI'         => 'nullable|numeric|min:0'
        ]);

        $data = $request->only([
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
            'Numero_Serie'
        ]);

        /*
        |--------------------------------------------------------------------------
        | NUEVO XML
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('Factura_XML')) {

            if ($item->Factura_XML &&
                Storage::disk('public')->exists($item->Factura_XML)) {

                Storage::disk('public')->delete($item->Factura_XML);
            }

            $cfdi = new CfdiService();

            $xmlData = $cfdi->procesarXml(
                $request->file('Factura_XML'),
                'segmentacion'
            );

            $data = array_merge($data, $xmlData);

            $data['Factura_XML'] = $request->file('Factura_XML')
                ->store('xml/segmentacion', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | REEMPLAZAR PDF
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('factura')) {

            if ($item->pdf_path &&
                Storage::disk('public')->exists($item->pdf_path)) {

                Storage::disk('public')->delete($item->pdf_path);
            }

            $data['pdf_path'] = $request->file('factura')
                ->store('pdf/segmentacion', 'public');
        }

        $item->update($data);

        $this->generarQr($item);

        return redirect()
            ->route('segmentacion.index')
            ->with('success', 'Registro actualizado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $item = Segmentacion::findOrFail($id);

        foreach (['pdf_path','Factura_XML','QR_Code'] as $field) {
            if ($item->$field &&
                Storage::disk('public')->exists($item->$field)) {

                Storage::disk('public')->delete($item->$field);
            }
        }

        $item->delete();

        return redirect()
            ->route('segmentacion.index')
            ->with('success', 'Registro eliminado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | FICHA PÚBLICA
    |--------------------------------------------------------------------------
    */
    public function publico($id)
    {
        $item = Segmentacion::findOrFail($id);
        return view('segmentacion.publico', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | VER QR
    |--------------------------------------------------------------------------
    */
    public function verQr($id)
    {
        $item = Segmentacion::findOrFail($id);
        return view('segmentacion.ver_qr', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | GENERAR QR
    |--------------------------------------------------------------------------
    */
    private function generarQr($item)
    {
        $urlPublica = route('segmentacion.publico', $item->id);
        $qrRelative = 'qr/segmentacion_' . $item->id . '.png';

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
