<?php

namespace App\Http\Controllers;

use App\Models\MaquinariaEquipo;
use App\Services\CfdiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;

class MaquinariaEquipoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = MaquinariaEquipo::query();

        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);

            $query->where(function ($q) use ($buscar) {
                if (is_numeric($buscar)) {
                    $q->orWhere('id', $buscar);
                }

                $q->orWhere('Concepto', 'like', "%$buscar%")
                  ->orWhere('Proveedor', 'like', "%$buscar%")
                  ->orWhere('Razon_Social', 'like', "%$buscar%");
            });
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('Fecha_AD', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('Fecha_AD', '<=', $request->fecha_fin);
        }

        $items = $query->orderByDesc('id')->get();

        return view('maquinaria.index', compact('items'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('maquinaria.create');
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
            'Fecha_AD','No_Factura','Concepto','Cantidad','Observacion',
            'MOI','Razon_Social','RFC','Folio_Externo',
            'Proveedor','Espacio','Usuario','Numero_Serie'
        ]);

        if ($request->hasFile('Factura_XML')) {

            $cfdi = new CfdiService();

            $xmlData = $cfdi->procesarXml(
                $request->file('Factura_XML'),
                'maquinaria'
            );

            $data = array_merge($data, $xmlData);

            $data['Factura_XML'] = $request->file('Factura_XML')
                ->store('xml/maquinaria', 'public');
        }

        if ($request->hasFile('factura')) {

            $data['pdf_path'] = $request->file('factura')
                ->store('pdf/maquinaria', 'public');
        }

        $item = MaquinariaEquipo::create($data);

        $this->generarQr($item);

        return redirect()
            ->route('maquinaria.index')
            ->with('success', 'Registro creado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $item = MaquinariaEquipo::findOrFail($id);
        return view('maquinaria.edit', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $item = MaquinariaEquipo::findOrFail($id);

        $data = $request->only([
            'Fecha_AD','No_Factura','Concepto','Cantidad','Observacion',
            'MOI','Razon_Social','RFC','Folio_Externo',
            'Proveedor','Espacio','Usuario','Numero_Serie'
        ]);

        if ($request->hasFile('Factura_XML')) {

            if ($item->Factura_XML &&
                Storage::disk('public')->exists($item->Factura_XML)) {
                Storage::disk('public')->delete($item->Factura_XML);
            }

            $cfdi = new CfdiService();

            $xmlData = $cfdi->procesarXml(
                $request->file('Factura_XML'),
                'maquinaria'
            );

            $data = array_merge($data, $xmlData);

            $data['Factura_XML'] = $request->file('Factura_XML')
                ->store('xml/maquinaria', 'public');
        }

        if ($request->hasFile('factura')) {

            if ($item->pdf_path &&
                Storage::disk('public')->exists($item->pdf_path)) {
                Storage::disk('public')->delete($item->pdf_path);
            }

            $data['pdf_path'] = $request->file('factura')
                ->store('pdf/maquinaria', 'public');
        }

        $item->update($data);

        $this->generarQr($item);

        return redirect()
            ->route('maquinaria.index')
            ->with('success', 'Registro actualizado.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $item = MaquinariaEquipo::findOrFail($id);

        foreach (['pdf_path','Factura_XML','QR_Code'] as $field) {
            if ($item->$field &&
                Storage::disk('public')->exists($item->$field)) {
                Storage::disk('public')->delete($item->$field);
            }
        }

        $item->delete();

        return redirect()
            ->route('maquinaria.index')
            ->with('success', 'Registro eliminado.');
    }

    public function publico($id)
    {
        $item = MaquinariaEquipo::findOrFail($id);
        return view('maquinaria.publico', compact('item'));
    }

    public function verQr($id)
    {
        $item = MaquinariaEquipo::findOrFail($id);
        return view('maquinaria.ver_qr', compact('item'));
    }

    private function generarQr($item)
    {
        $urlPublica = route('maquinaria.publico', $item->id);
        $qrRelative = 'qr/maquinaria_' . $item->id . '.png';

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
