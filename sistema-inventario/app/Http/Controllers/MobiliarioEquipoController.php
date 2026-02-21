<?php

namespace App\Http\Controllers;

use App\Models\MobiliarioEquipo;
use App\Services\CfdiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;

class MobiliarioEquipoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO + FILTROS
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = MobiliarioEquipo::query();

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

        return view('mobiliario.index', compact('items'));
    }

    public function create()
    {
        return view('mobiliario.create');
    }

    public function publico($id)
    {
        $item = MobiliarioEquipo::findOrFail($id);
        return view('mobiliario.publico', compact('item'));
    }

    public function verQr($id)
    {
        $item = MobiliarioEquipo::findOrFail($id);
        return view('mobiliario.ver_qr', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE (CON XML)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'Factura_XML' => 'nullable|file|mimes:xml|max:10240',

            'Fecha_AD'      => 'nullable|date',
            'No_Factura'    => 'nullable|string|max:100',
            'Proveedor'     => 'nullable|string|max:255',
            'Concepto'      => 'nullable|string',
            'Cantidad'      => 'nullable|integer|min:0',
            'Espacio'       => 'nullable|string|max:100',
            'Observacion'   => 'nullable|string',
            'MOI'           => 'nullable|numeric|min:0',

            // tu factura manual (pdf o xml)
            'factura'       => 'required|file|mimes:pdf,xml|max:5120',

            'Usuario'       => 'nullable|string|max:255',
            'Folio_Externo' => 'nullable|string|max:255',
            'RFC'           => 'nullable|string|max:20',
            'Razon_Social'  => 'nullable|string|max:255',
        ]);

        // Base data (lo que venga del formulario)
        $data = $request->only([
            'Fecha_AD','No_Factura','Proveedor','Concepto','Cantidad',
            'Espacio','Observacion','MOI','Usuario',
            'Folio_Externo','RFC','Razon_Social'
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
                'mobiliario'
            );

            // XML pisa campos si vienen (RFC, Razon_Social, Proveedor, Fecha_AD, MOI, Concepto, Folio_Externo...)
            $data = array_merge($data, $xmlData);
        }

        /*
        |--------------------------------------------------------------------------
        | GUARDAR FACTURA MANUAL
        |--------------------------------------------------------------------------
        */
        $data['factura_path'] = $request->file('factura')
            ->store('facturas/mobiliario', 'public');

        /*
        |--------------------------------------------------------------------------
        | CREAR REGISTRO
        |--------------------------------------------------------------------------
        */
        $item = MobiliarioEquipo::create($data);

        $this->generarQr($item);

        return redirect()
            ->route('mobiliario.index')
            ->with('success', 'Registro creado (XML aplicado si se cargó) y QR generado.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $item = MobiliarioEquipo::findOrFail($id);
        return view('mobiliario.edit', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE (CON XML)
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $item = MobiliarioEquipo::findOrFail($id);

        $request->validate([
            'Factura_XML' => 'nullable|file|mimes:xml|max:10240',

            'Fecha_AD'      => 'nullable|date',
            'No_Factura'    => 'nullable|string|max:100',
            'Proveedor'     => 'nullable|string|max:255',
            'Concepto'      => 'nullable|string',
            'Cantidad'      => 'nullable|integer|min:0',
            'Espacio'       => 'nullable|string|max:100',
            'Observacion'   => 'nullable|string',
            'MOI'           => 'nullable|numeric|min:0',

            'factura'       => 'nullable|file|mimes:pdf,xml|max:5120',

            'Usuario'       => 'nullable|string|max:255',
            'Folio_Externo' => 'nullable|string|max:255',
            'RFC'           => 'nullable|string|max:20',
            'Razon_Social'  => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'Fecha_AD','No_Factura','Proveedor','Concepto','Cantidad',
            'Espacio','Observacion','MOI','Usuario',
            'Folio_Externo','RFC','Razon_Social'
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
                'mobiliario'
            );

            $data = array_merge($data, $xmlData);
        }

        /*
        |--------------------------------------------------------------------------
        | REEMPLAZAR FACTURA MANUAL
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('factura')) {

            if ($item->factura_path &&
                Storage::disk('public')->exists($item->factura_path)) {

                Storage::disk('public')->delete($item->factura_path);
            }

            $data['factura_path'] = $request->file('factura')
                ->store('facturas/mobiliario', 'public');
        }

        $item->update($data);

        $this->generarQr($item);

        return redirect()
            ->route('mobiliario.index')
            ->with('success', 'Registro actualizado (XML aplicado si se cargó) y QR regenerado.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $item = MobiliarioEquipo::findOrFail($id);

        foreach (['factura_path','Factura_XML','Factura_PDF','QR_Code'] as $field) {
            if (!empty($item->$field) && Storage::disk('public')->exists($item->$field)) {
                Storage::disk('public')->delete($item->$field);
            }
        }

        $item->delete();

        return redirect()
            ->route('mobiliario.index')
            ->with('success', 'Registro eliminado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | GENERAR QR
    |--------------------------------------------------------------------------
    */
    private function generarQr($item)
    {
        $urlPublica = route('mobiliario.publico', $item->id);
        $qrRelative = 'qr/mobiliario_' . $item->id . '.png';
        $qrPath = storage_path('app/public/' . $qrRelative);

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

        // Guardar ruta del QR
        $item->QR_Code = $qrRelative;
        $item->save();
    }
}
