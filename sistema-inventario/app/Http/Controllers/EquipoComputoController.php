<?php

namespace App\Http\Controllers;

use App\Models\EquipoComputo;
use App\Services\CfdiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;

class EquipoComputoController extends Controller
{
    public function index(Request $request)
    {
        $query = EquipoComputo::query();

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

        return view('computo.index', compact('items'));
    }

    public function create()
    {
        return view('computo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Factura_XML' => 'nullable|file|mimes:xml|max:10240',
            'factura'     => 'required|file|mimes:pdf,xml|max:5120',
            'cantidad'    => 'nullable|integer|min:0',
            'MOI'         => 'nullable|numeric|min:0'
        ]);

        $data = $request->only([
            'publicado','Fecha_AD','No_Factura','Proveedor','Concepto',
            'especificaciones','cantidad',
            'Espacio','Observacion','MOI','MOI_Total','Usuario',
            'Folio_Externo','RFC','Razon_Social','Numero_Serie'
        ]);

        if ($request->hasFile('foto')) {
            $data['foto_path'] = $request->file('foto')
                ->store('fotos/computo', 'public');
        }

        if ($request->hasFile('Factura_XML')) {

            $cfdi = new CfdiService();

            $xmlData = $cfdi->procesarXml(
                $request->file('Factura_XML'),
                'computo'
            );

            $data = array_merge($data, $xmlData);
        }

        // USAMOS SOLO Factura_PDF
        $data['Factura_PDF'] = $request->file('factura')
            ->store('facturas/computo', 'public');

        $item = EquipoComputo::create($data);

        $this->generarQr($item);

        return redirect()
            ->route('computo.index')
            ->with('success', 'Equipo de computo registrado correctamente.');
    }

    public function edit($id)
    {
        $item = EquipoComputo::findOrFail($id);
        return view('computo.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = EquipoComputo::findOrFail($id);

        $request->validate([
            'Factura_XML' => 'nullable|file|mimes:xml|max:10240',
            'factura'     => 'nullable|file|mimes:pdf,xml|max:5120',
            'cantidad'    => 'nullable|integer|min:0',
            'MOI'         => 'nullable|numeric|min:0'
        ]);

        $data = $request->only([
            'publicado','Fecha_AD','No_Factura','Proveedor','Concepto',
            'especificaciones','cantidad',
            'Espacio','Observacion','MOI','MOI_Total','Usuario',
            'Folio_Externo','RFC','Razon_Social','Numero_Serie'
        ]);

        if ($request->hasFile('foto')) {
            $data['foto_path'] = $request->file('foto')
                ->store('fotos/computo', 'public');
        }

        if ($request->hasFile('Factura_XML')) {

            $cfdi = new CfdiService();

            $xmlData = $cfdi->procesarXml(
                $request->file('Factura_XML'),
                'computo'
            );

            $data = array_merge($data, $xmlData);
        }

        if ($request->hasFile('factura')) {

            if ($item->Factura_PDF &&
                Storage::disk('public')->exists($item->Factura_PDF)) {

                Storage::disk('public')->delete($item->Factura_PDF);
            }

            $data['Factura_PDF'] = $request->file('factura')
                ->store('facturas/computo', 'public');
        }

        $item->update($data);

        $this->generarQr($item);

        return redirect()
            ->route('computo.index')
            ->with('success', 'Equipo de computo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $item = EquipoComputo::findOrFail($id);

        foreach (['Factura_PDF','Factura_XML','QR_Code'] as $field) {
            if ($item->$field &&
                Storage::disk('public')->exists($item->$field)) {

                Storage::disk('public')->delete($item->$field);
            }
        }

        $item->delete();

        return redirect()
            ->route('computo.index')
            ->with('success', 'Equipo de comunicación eliminado correctamente.');
    }

    public function publico($id)
    {
        $item = EquipoComputo::findOrFail($id);
        return view('computo.publico', compact('item'));
    }

    public function verQr($id)
    {
        $item = EquipoComputo::findOrFail($id);
        return view('computo.ver_qr', compact('item'));
    }

    private function generarQr($item)
    {
        $urlPublica = route('computo.publico', $item->id);
        $qrRelative = 'qr/computo' . $item->id . '.png';

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
