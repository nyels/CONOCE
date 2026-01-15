<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cotización #{{ $quote->folio }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { width: 100%; border-bottom: 2px solid #0056b3; margin-bottom: 20px; padding-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; color: #0056b3; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px; }
        
        /* Tabla de Comparativa */
        .comparison { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .comparison th, .comparison td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .comparison th { background-color: #f4f4f4; font-weight: bold; }
        .comparison .insurer-name { font-size: 14px; font-weight: bold; color: #0056b3; }
        .comparison .price { font-size: 16px; font-weight: bold; color: #28a745; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%">
            <tr>
                <td><span class="title">Cotización de Seguro de Auto</span></td>
                <td style="text-align: right">
                    <strong>Folio:</strong> {{ $quote->folio }}<br>
                    <strong>Fecha:</strong> {{ $quote->created_at->format('d/m/Y') }}
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Cliente:</strong> {{ $quote->customer->name }}<br>
                <strong>CP:</strong> {{ $quote->customer->zip_code }} | {{ $quote->customer->city }}
            </td>
            <td width="50%">
                <strong>Vehículo:</strong> {{ $quote->vehicle_data['brand'] }} {{ $quote->vehicle_data['model'] }} {{ $quote->vehicle_data['year'] }}<br>
                <strong>Versión:</strong> {{ $quote->vehicle_data['description'] ?? 'N/A' }}
            </td>
        </tr>
    </table>

    <h3 style="margin-bottom: 5px;">Opciones Disponibles (Pago: {{ $quote->options->first()->payment_frequency }})</h3>
    <table class="comparison">
        <thead>
            <tr>
                <th>Concepto</th>
                @foreach($quote->options as $option)
                    <th>
                        <div class="insurer-name">{{ $option->insurer->name }}</div>
                        <small>{{ $option->coverage_package }}</small>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left;">Prima Neta</td>
                @foreach($quote->options as $option)
                    <td>${{ number_format($option->net_premium, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align: left;">Derecho de Póliza</td>
                @foreach($quote->options as $option)
                    <td>${{ number_format($option->policy_fee, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align: left;">Recargo Financiamiento</td>
                @foreach($quote->options as $option)
                    <td>${{ number_format($option->surcharge, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align: left;">I.V.A.</td>
                @foreach($quote->options as $option)
                    <td>${{ number_format($option->tax, 2) }}</td>
                @endforeach
            </tr>
            <tr style="background-color: #e8f5e9;">
                <td style="text-align: left;"><strong>TOTAL A PAGAR</strong></td>
                @foreach($quote->options as $option)
                    <td class="price">${{ number_format($option->total_premium, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td style="text-align: left;">Primer Pago</td>
                @foreach($quote->options as $option)
                    <td><strong>${{ number_format($option->first_payment, 2) }}</strong></td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Esta cotización tiene una vigencia de 15 días. Precios sujetos a cambios por la aseguradora.
    </div>
</body>
</html>