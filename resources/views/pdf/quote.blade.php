<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Cotización #{{ $quote->folio }}</title>

    {{-- LÓGICA DE TAMAÑOS DINÁMICOS --}}
    @php
        $count = $quote->options->count();

        // BASE: Texto general MUY LEGIBLE (16px)
        $bodySize = 16;

        // AJUSTE SOLO PARA LA TABLA SEGÚN COLUMNAS
        if ($count <= 2) {
            $tableSize = 16; // 1-2 columnas: Letra grande 16px
            $padding = 8;
            $logoSize = 75;
        } elseif ($count == 3) {
            $tableSize = 15; // 3 columnas: Casi igual de grande
            $padding = 5;
            $logoSize = 65;
        } else {
            // 4 Columnas: Bajamos un poco solo la tabla para que quepan los 4 precios
            $tableSize = 13;
            $padding = 3;
            $logoSize = 55;
        }
    @endphp

    <style>
        @page {
            margin: 0.5cm 0.5cm;
        }

        /* Márgenes muy reducidos para aprovechar la hoja */

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: {{ $bodySize }}px;
            /* BASE 16PX */
            color: #333;
            line-height: 1.2;
        }

        .brand-color {
            color: #632533;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Header */
        .header-table {
            border-bottom: 3px solid #632533;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .quote-title {
            font-size: 28px;
            font-weight: bold;
            color: #632533;
            text-transform: uppercase;
            margin: 0;
        }

        .agency-logo {
            max-height: 85px;
            display: block;
            margin-left: auto;
        }

        /* Cajas Info */
        .info-box {
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .info-header {
            background-color: #eee;
            color: #632533;
            font-weight: bold;
            padding: 6px 10px;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            text-transform: uppercase;
        }

        .info-content {
            padding: 8px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        /* TABLA COMPARATIVA */
        .comparison-table th {
            background-color: #f9f9f9;
            color: #632533;
            padding: {{ $padding }}px;
            border: 1px solid #e0e0e0;
            vertical-align: middle;
            font-size: {{ $tableSize }}px;
        }

        .comparison-table td {
            padding: {{ $padding }}px;
            border: 1px solid #e0e0e0;
            text-align: center;
            font-size: {{ $tableSize }}px;
        }

        /* Ajuste de anchos para ganar espacio */
        .col-concept {
            width: 22%;
            text-align: left !important;
            background-color: #fff;
            font-weight: bold;
            color: #444;
        }

        .insurer-logo-circle {
            width: {{ $logoSize }}px;
            height: {{ $logoSize }}px;
            object-fit: contain;
            border-radius: 50%;
            display: block;
            margin: 0 auto;
            border: 1px solid #eee;
            background-color: white;
        }

        /* Totales (Más grandes para que resalten) */
        .total-row td {
            background-color: #632533;
            color: white;
            border: 1px solid #632533;
            font-size: {{ $tableSize + 2 }}px;
            font-weight: bold;
        }

        .payment-row td {
            background-color: #fce4ec;
            color: #632533;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 11px;
            color: #632533;
            border-top: 2px solid #632533;
            padding-top: 5px;
            padding-bottom: 5px;
            background-color: white;
        }

        .whatsapp-link {
            text-decoration: none;
            color: #632533;
            font-weight: bold;
            display: inline-block;
            vertical-align: middle;
            font-size: 14px;
            margin-top: 2px;
        }

        .whatsapp-icon {
            width: 18px;
            height: 18px;
            vertical-align: middle;
            margin-right: 4px;
        }
    </style>
</head>

<body>

    @if (isset($isDraft) && $isDraft)
        <div class="watermark">BORRADOR</div>
        <style>
            .watermark {
                position: fixed;
                top: 35%;
                left: -10%;
                width: 120%;
                text-align: center;
                font-size: 120px;
                font-weight: bold;
                color: rgba(200, 0, 0, 0.15);
                transform: rotate(-35deg);
                z-index: 9999;
                pointer-events: none;
                letter-spacing: 20px;
                text-transform: uppercase;
            }
        </style>
    @endif


    <table class="header-table">
        <tr>
            <td width="60%" style="vertical-align: bottom;">
                <h1 class="quote-title">Cotización</h1>
                <div style="font-size: 16px; margin-top: 5px;">
                    <span class="brand-color">Folio:</span> <strong>{{ $quote->folio }}</strong> |
                    <span class="brand-color">Fecha:</span> {{ $quote->created_at->format('d/m/Y') }}
                </div>
            </td>
            <td width="40%">
                <img src="{{ public_path('logo.png') }}" class="agency-logo">
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="50%" style="padding-right: 5px; vertical-align: top;">
                <div class="info-box">
                    <div class="info-header">Asegurado</div>
                    <div class="info-content">
                        <div><span class="label">Nombre:</span> {{ $customer->name ?? 'Cliente Prospecto' }}</div>
                        <div><span class="label">Ubicación:</span> {{ $customer->zip_code ?? '' }}
                            {{ $customer->city ?? '' }}</div>
                    </div>
                </div>
            </td>
            <td width="50%" style="padding-left: 5px; vertical-align: top;">
                <div class="info-box">
                    <div class="info-header">Vehículo</div>
                    <div class="info-content">
                        <div><span class="label">Auto:</span> {{ $quote->vehicle_data['brand'] }}
                            {{ $quote->vehicle_data['model'] }}</div>
                        <div><span class="label">Año:</span> {{ $quote->vehicle_data['year'] }} -
                            {{ $quote->vehicle_data['description'] ?? 'Std' }}</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div style="margin: 15px 0 10px 0; font-weight: bold; color: #632533; font-size: 16px;">
        COMPARATIVA ({{ $count }} OPCIONES - PAGO {{ $quote->options->first()->payment_frequency }})
    </div>

    <table class="comparison-table">
        <thead>
            <tr>
                <th class="col-concept">Concepto</th>
                @foreach ($quote->options as $option)
                    <th>
                        @if ($option->insurer->logo_path && file_exists(public_path($option->insurer->logo_path)))
                            <img src="{{ public_path($option->insurer->logo_path) }}" class="insurer-logo-circle">
                        @else
                            <div style="font-weight: bold;">{{ $option->insurer->name }}</div>
                        @endif
                        <div style="font-size: {{ $tableSize - 2 }}px; color: #666; margin-top: 4px;">
                            {{ $option->coverage_package }}</div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col-concept">Daños Materiales</td>
                @foreach ($quote->options as $option)
                    <td>{{ $option->coverages['dmg'] ?? 'Valor Com.' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">Robo Total</td>
                @foreach ($quote->options as $option)
                    <td>{{ $option->coverages['theft'] ?? 'Valor Com.' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">Resp. Civil</td>
                @foreach ($quote->options as $option)
                    <td>{{ $option->coverages['rc'] ?? '$3M' }}</td>
                @endforeach
            </tr>

            <tr>
                <td colspan="{{ $count + 1 }}" style="border:none; height:10px;"></td>
            </tr>

            <tr>
                <td class="col-concept">Prima Neta</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->net_premium, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">Derecho Póliza</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->policy_fee, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">I.V.A.</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->tax, 2) }}</td>
                @endforeach
            </tr>

            <tr class="total-row">
                <td style="text-align: left;">TOTAL</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->total_premium, 2) }}</td>
                @endforeach
            </tr>
            <tr class="payment-row">
                <td class="col-concept">Primer Pago</td>
                @foreach ($quote->options as $option)
                    <td><strong>${{ number_format($option->first_payment, 2) }}</strong></td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 15px; font-size: 14px; color: #555;">
        <strong>Notas: Precios sujetos a cambio. Vigencia 15 días. La prima total incluye impuestos.</strong>
    </div>

    <div class="footer">
        <strong>CONOCE AGENTE DE SEGUROS Y DE FIANZAS, S.A. DE C.V.</strong><br>
        Calle 8 No. 424-E x 21 Col. Díaz Ordaz, Mérida, Yucatán. C.P. 97130<br>
        Tel: (999) 944-68-35 | (999) 944-68-39 <br>

        <a href="https://wa.me/5219991630369" class="whatsapp-link" target="_blank">
            <img style="width: 24px;" src="{{ public_path('whatsapp.png') }}" class="whatsapp-icon">
            (999) 163-0369
        </a>
    </div>
</body>

</html>
