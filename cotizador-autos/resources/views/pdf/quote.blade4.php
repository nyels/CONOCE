<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Cotización #{{ $quote->folio }}</title>
    <style>
        /* --- ESTILOS GENERALES (BASE 14PX) --- */
        @page {
            margin: 0.5cm 1cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            /* AJUSTADO A 14PX */
            color: #333;
            line-height: 1.3;
        }

        /* Colores de Marca (Vino Conoce) */
        .brand-color {
            color: #632533;
        }

        .brand-bg {
            background-color: #632533;
            color: white;
        }

        /* Tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        /* Encabezado */
        .header-table {
            margin-bottom: 20px;
            border-bottom: 2px solid #632533;
            padding-bottom: 10px;
        }

        .quote-title {
            font-size: 24px;
            /* Proporcional a 14px */
            font-weight: bold;
            color: #632533;
            text-transform: uppercase;
            margin: 0;
        }

        /* Logo Agencia */
        .agency-logo {
            max-height: 80px;
            display: block;
            margin-left: auto;
        }

        /* Cajas de Info */
        .info-box {
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .info-header {
            background-color: #eee;
            color: #632533;
            font-weight: bold;
            padding: 6px 10px;
            font-size: 12px;
            /* Encabezados de caja un poco más chicos */
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
        }

        .info-content {
            padding: 8px;
        }

        .info-row {
            margin-bottom: 4px;
        }

        .label {
            font-weight: bold;
            color: #555;
            margin-right: 5px;
        }

        /* TABLA COMPARATIVA */
        .comparison-table th {
            background-color: #f9f9f9;
            color: #632533;
            padding: 10px;
            border: 1px solid #e0e0e0;
            text-align: center;
            vertical-align: middle;
        }

        /* ESTILO PARA EL LOGO CIRCULAR */
        .insurer-logo-circle {
            width: 60px;
            /* Ajustado para balancear con texto 14px */
            height: 60px;
            object-fit: contain;
            border-radius: 50%;
            display: block;
            margin: 0 auto;
            border: 1px solid #eee;
            background-color: white;
        }

        .comparison-table td {
            padding: 8px;
            border: 1px solid #e0e0e0;
            text-align: center;
            font-size: 14px;
            /* AJUSTADO A 14PX */
        }

        .concept-col {
            text-align: left !important;
            background-color: #fff;
            font-weight: bold;
            color: #444;
            width: 30%;
        }

        /* Totales */
        .total-row td {
            background-color: #632533;
            font-weight: bold;
            color: white;
            border: 1px solid #632533;
            font-size: 16px;
            /* Destacado pero no gigante */
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
            font-size: 10px;
            color: #632533;
            border-top: 1px solid #632533;
            padding-top: 10px;
            background-color: white;
        }

        /* Enlace de Whatsapp */
        .whatsapp-link {
            text-decoration: none;
            color: #632533;
            font-weight: bold;
            display: inline-block;
            vertical-align: middle;
            font-size: 12px;
            margin-top: 5px;
        }

        .whatsapp-icon {
            width: 16px;
            height: 16px;
            vertical-align: middle;
            margin-right: 4px;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td width="60%" style="vertical-align: bottom;">
                <h1 class="quote-title">Cotización de Seguro</h1>
                <div style="font-size: 12px; margin-top: 5px;">
                    <span class="brand-color">Folio:</span> <strong>{{ $quote->folio }}</strong>
                    <span style="margin: 0 10px;">|</span>
                    <span class="brand-color">Fecha:</span> {{ $quote->created_at->format('d/m/Y') }}
                </div>
            </td>
            <td width="40%" style="text-align: right; vertical-align: middle;">
                <img src="{{ public_path('logo.png') }}" class="agency-logo" alt="Conoce Agente de Seguros">
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="49%" style="vertical-align: top; padding-right: 10px;">
                <div class="info-box">
                    <div class="info-header">Datos del Asegurado</div>
                    <div class="info-content">
                        <div class="info-row"><span class="label">Nombre:</span> {{ $quote->customer->name }}</div>
                        <div class="info-row"><span class="label">RFC:</span>
                            {{ $quote->customer->rfc ?? 'No especificado' }}</div>
                        <div class="info-row"><span class="label">Ubicación:</span> {{ $quote->customer->suburb }},
                            {{ $quote->customer->city }} CP {{ $quote->customer->zip_code }}</div>
                    </div>
                </div>
            </td>
            <td width="49%" style="vertical-align: top; padding-left: 10px;">
                <div class="info-box">
                    <div class="info-header">Datos del Vehículo</div>
                    <div class="info-content">
                        <div class="info-row"><span class="label">Vehículo:</span> {{ $quote->vehicle_data['brand'] }}
                            {{ $quote->vehicle_data['model'] }}</div>
                        <div class="info-row"><span class="label">Año/Versión:</span>
                            {{ $quote->vehicle_data['year'] }} - {{ $quote->vehicle_data['description'] ?? '' }}</div>
                        <div class="info-row"><span class="label">Uso:</span> Particular / Automóvil</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div style="margin-top: 15px; margin-bottom: 10px; font-weight: bold; color: #632533; font-size: 14px;">
        OPCIONES DISPONIBLES (PAGO: {{ $quote->options->first()->payment_frequency }})
    </div>

    <table class="comparison-table">
        <thead>
            <tr>
                <th width="30%">Concepto</th>
                @foreach ($quote->options as $option)
                    <th>
                        @if ($option->insurer->logo_path && file_exists(public_path($option->insurer->logo_path)))
                            <img src="{{ public_path($option->insurer->logo_path) }}" class="insurer-logo-circle">
                        @else
                            <div style="font-weight: bold; font-size: 14px;">{{ $option->insurer->name }}</div>
                        @endif

                        <div style="font-size: 11px; color: #555; margin-top: 5px;">{{ $option->coverage_package }}
                        </div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="concept-col">Daños Materiales</td>
                @foreach ($quote->options as $option)
                    <td>{{ $option->coverages['dmg'] ?? 'Valor Comercial' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="concept-col">Robo Total</td>
                @foreach ($quote->options as $option)
                    <td>{{ $option->coverages['theft'] ?? 'Valor Comercial' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="concept-col">Resp. Civil</td>
                @foreach ($quote->options as $option)
                    <td>{{ $option->coverages['rc'] ?? '$3,000,000' }}</td>
                @endforeach
            </tr>

            <tr>
                <td colspan="{{ $quote->options->count() + 1 }}" style="border:none; height:10px;"></td>
            </tr>

            <tr>
                <td class="concept-col">Prima Neta</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->net_premium, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="concept-col">Derecho Póliza</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->policy_fee, 2) }}</td>
                @endforeach
            </tr>
            @if ($quote->options->first()->surcharge > 0)
                <tr>
                    <td class="concept-col">Recargo Pago Frac.</td>
                    @foreach ($quote->options as $option)
                        <td>${{ number_format($option->surcharge, 2) }}</td>
                    @endforeach
                </tr>
            @endif
            <tr>
                <td class="concept-col">I.V.A.</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->tax, 2) }}</td>
                @endforeach
            </tr>

            <tr class="total-row">
                <td style="text-align: left; background-color: #632533; color: white;">TOTAL A PAGAR</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->total_premium, 2) }}</td>
                @endforeach
            </tr>

            <tr class="payment-row">
                <td class="concept-col">Primer Pago</td>
                @foreach ($quote->options as $option)
                    <td><strong>${{ number_format($option->first_payment, 2) }}</strong></td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 10px; color: #555;">
        <strong>Notas:</strong>
        <ul style="margin-top: 5px; padding-left: 20px;">
            <li>Precios sujetos a cambio sin previo aviso. Vigencia de 15 días.</li>
            <li>La Prima Total incluye impuestos y derechos.</li>
            <li>Esto es una cotización informativa, no es una póliza de seguro.</li>
        </ul>
    </div>

    <div class="footer">
        <strong>CONOCE AGENTE DE SEGUROS Y DE FIANZAS, S.A. DE C.V.</strong><br>
        Calle 8 No. 424-E x 21 Col. Díaz Ordaz, Mérida, Yucatán. C.P. 97130<br>
        Tel: (999) 944-68-35 | (999) 944-68-39 <br>

        <a href="https://wa.me/5219991630369" class="whatsapp-link" target="_blank">
            <img style="width: 20px; height: 20px;" src="{{ public_path('whatsapp.png') }}" class="whatsapp-icon">
            (999) 163-0369
        </a>
    </div>

</body>

</html>
