<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; }
        h2 { color:#333; }
    </style>
</head>
<body>

<h2>Factura CFDI</h2>

<p><strong>Proveedor:</strong> {{ $data['Proveedor'] ?? '' }}</p>
<p><strong>RFC:</strong> {{ $data['RFC'] ?? '' }}</p>
<p><strong>Fecha:</strong> {{ $data['Fecha_AD'] ?? '' }}</p>
<p><strong>Concepto:</strong> {{ $data['Concepto'] ?? '' }}</p>
<p><strong>Total:</strong> ${{ $data['MOI'] ?? '' }}</p>
<p><strong>UUID:</strong> {{ $data['Folio_Externo'] ?? '' }}</p>

</body>
</html>
