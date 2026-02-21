<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CfdiService
{
    public function procesarXml($file, $carpeta)
    {
        $data = [];

        $xmlPath = $file->store("xml/{$carpeta}", 'public');
        $data['Factura_XML'] = $xmlPath;

        try {

            $xml = simplexml_load_file(storage_path('app/public/' . $xmlPath));

            if (!$xml) {
                throw new \Exception("XML inválido");
            }

            $xml->registerXPathNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');
            $xml->registerXPathNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');

            // Emisor
            $emisor = $xml->xpath('//cfdi:Emisor')[0] ?? null;
            if ($emisor) {
                $data['RFC'] = (string)$emisor['Rfc'];
                $data['Razon_Social'] = (string)$emisor['Nombre'];
                $data['Proveedor'] = (string)$emisor['Nombre'];
            }

            // Fecha
            if (!empty($xml['Fecha'])) {
                $data['Fecha_AD'] = substr((string)$xml['Fecha'], 0, 10);
            }

            // Total
            if (!empty($xml['Total'])) {
                $data['MOI'] = (float)$xml['Total'];
            }

            // Concepto
            $concepto = $xml->xpath('//cfdi:Concepto')[0] ?? null;
            if ($concepto) {
                $data['Concepto'] = (string)$concepto['Descripcion'];
            }

            // UUID
            $timbre = $xml->xpath('//tfd:TimbreFiscalDigital')[0] ?? null;
            if ($timbre) {
                $data['Folio_Externo'] = (string)$timbre['UUID'];
            }

            // Generar PDF
            $pdf = Pdf::loadView('pdf.cfdi_template', [
                'data' => $data
            ]);

            $pdfPath = "pdf/{$carpeta}_" . time() . '.pdf';

            Storage::disk('public')->put($pdfPath, $pdf->output());

            $data['Factura_PDF'] = $pdfPath;

            return $data;

        } catch (\Exception $e) {
            throw new \Exception("Error al procesar XML: " . $e->getMessage());
        }
    }
}
