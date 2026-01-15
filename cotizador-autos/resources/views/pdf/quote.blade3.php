<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Cotización #{{ $quote->folio }}</title>
    <style>
        /* --- ESTILOS GENERALES Y MODERNOS --- */
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        /* Tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        /* Encabezado */
        .header-table td {
            vertical-align: middle;
        }

        .company-logo {
            max-height: 60px;
        }

        .quote-title {
            font-size: 18px;
            font-weight: bold;
            color: #003366;
            /* Azul Corporativo */
            text-transform: uppercase;
            margin: 0;
        }

        .quote-meta {
            font-size: 10px;
            color: #666;
        }

        /* Secciones */
        .section-title {
            background-color: #003366;
            color: white;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 15px;
            border-radius: 4px 4px 0 0;
        }

        /* Tablas de Info (Cliente/Auto) */
        .info-table td {
            padding: 5px;
            border: 1px solid #e0e0e0;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            color: #555;
            font-size: 10px;
        }

        .value {
            font-size: 11px;
            color: #000;
        }

        /* TABLA COMPARATIVA (Tu Lógica Central) */
        .comparison-table {
            margin-top: 0;
            border: 1px solid #003366;
        }

        .comparison-table th {
            background-color: #f4f6f9;
            color: #003366;
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
            font-size: 12px;
        }

        .comparison-table td {
            padding: 6px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
        }

        .concept-col {
            text-align: left !important;
            background-color: #fafafa;
            font-weight: bold;
            width: 25%;
        }

        /* Filas especiales */
        .total-row td {
            background-color: #e8f0fe;
            font-weight: bold;
            color: #003366;
            border-top: 2px solid #003366;
        }

        .payment-row td {
            background-color: #fff8e1;
            /* Fondo amarillento suave para resaltar pagos */
        }

        /* Notas y Footer */
        .notes-box {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #fff;
            font-size: 9px;
            color: #555;
            page-break-inside: avoid;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td width="60%">
                <h1 class="quote-title">Cotización de Seguro de Auto</h1>
                <p style="margin: 2px 0; font-size: 12px;">Negocio:
                    <strong>{{ $quote->type == 'NEW' ? 'NUEVO' : 'RENOVACIÓN' }}</strong></p>
                <div class="quote-meta">
                    <strong>Folio:</strong> {{ $quote->folio }}<br>
                    <strong>Fecha:</strong> {{ $quote->created_at->format('d/m/Y') }}
                </div>
            </td>
            <td width="40%" style="text-align: right;">
                <h2 style="color:#003366; margin:0;">CONOCE</h2>
                <small>Agente de Seguros y Fianzas</small>
            </td>
        </tr>
    </table>

    <table style="margin-top: 10px;">
        <tr>
            <td width="49%" style="vertical-align: top;">
                <div class="section-title">Información del Asegurado</div>
                <table class="info-table">
                    <tr>
                        <td width="30%" class="label">Nombre:</td>
                        <td class="value">{{ $quote->customer->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Ubicación:</td>
                        <td class="value">
                            CP: {{ $quote->customer->zip_code }}<br>
                            {{ $quote->customer->suburb }}, {{ $quote->customer->city }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">RFC:</td>
                        <td class="value">{{ $quote->customer->rfc ?? 'N/A' }}</td>
                    </tr>
                </table>
            </td>

            <td width="2%"></td>
            <td width="49%" style="vertical-align: top;">
                <div class="section-title">Descripción del Vehículo</div>
                <table class="info-table">
                    <tr>
                        <td width="30%" class="label">Vehículo:</td>
                        <td class="value">
                            {{ $quote->vehicle_data['brand'] }} {{ $quote->vehicle_data['model'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Año/Versión:</td>
                        <td class="value">
                            {{ $quote->vehicle_data['year'] }} - {{ $quote->vehicle_data['description'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Uso/Tipo:</td>
                        <td class="value">Particular / Automóvil</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">Comparativa de Opciones (Pago: {{ $quote->options->first()->payment_frequency }})</div>

    <table class="comparison-table">
        <thead>
            <tr>
                <th width="20%">Concepto / Coberturas</th>
                @foreach ($quote->options as $option)
                    <th>
                        <div style="font-size: 13px; font-weight:bold;">{{ $option->insurer->name }}</div>
                        <div style="font-size: 10px; font-weight:normal; margin-top:2px;">
                            {{ $option->coverage_package }}</div>
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
                <td class="concept-col">Resp. Civil (R.C.)</td>
                @foreach ($quote->options as $option)
                    <td>{{ $option->coverages['rc'] ?? '$3,000,000' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="concept-col">Gastos Médicos</td>
                @foreach ($quote->options as $option)
                    <td>{{ $option->coverages['medical'] ?? '$200,000' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="concept-col">Asistencia Legal / Vial</td>
                @foreach ($quote->options as $option)
                    <td>Amparada</td>
                @endforeach
            </tr>

            <tr>
                <td colspan="{{ $quote->options->count() + 1 }}"
                    style="background-color: #fff; border:none; height:10px;"></td>
            </tr>

            <tr>
                <td class="concept-col">Prima Neta</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->net_premium, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="concept-col">Derecho de Póliza</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->policy_fee, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="concept-col">Recargo Financiamiento</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->surcharge, 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="concept-col">I.V.A. (16%)</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->tax, 2) }}</td>
                @endforeach
            </tr>

            <tr class="total-row">
                <td style="text-align: left;">PRIMA TOTAL ANUAL</td>
                @foreach ($quote->options as $option)
                    <td style="font-size: 14px;">${{ number_format($option->total_premium, 2) }}</td>
                @endforeach
            </tr>

            @php
                $freq = $quote->options->first()->payment_frequency;
                $installments = match ($freq) {
                    'SEMIANNUAL' => 1,
                    'QUARTERLY' => 3,
                    'MONTHLY' => 11,
                    default => 0,
                };
            @endphp

            @if ($freq != 'ANNUAL')
                <tr class="payment-row">
                    <td class="concept-col">Primer Pago</td>
                    @foreach ($quote->options as $option)
                        <td><strong>${{ number_format($option->first_payment, 2) }}</strong></td>
                    @endforeach
                </tr>
                <tr class="payment-row">
                    <td class="concept-col">{{ $installments }} Pagos Subsecuentes de:</td>
                    @foreach ($quote->options as $option)
                        <td>${{ number_format($option->subsequent_payments, 2) }}</td>
                    @endforeach
                </tr>
            @else
                <tr class="payment-row">
                    <td class="concept-col">Pago Único</td>
                    @foreach ($quote->options as $option)
                        <td>${{ number_format($option->total_premium, 2) }}</td>
                    @endforeach
                </tr>
            @endif

        </tbody>
    </table>

    <div class="notes-box">
        <strong>Notas Importantes:</strong>
        <ul>
            <li>La Prima Total Anual incluye derecho de póliza, IVA y recargo por pago fraccionado (si aplica).</li>
            <li>La presente cotización tiene una vigencia de 15 días naturales.</li>
            <li>En ningún caso se considera que esta cotización es una póliza de seguro (cobertura provisional).</li>
            <li>Periodo de gracia para pagos: 30 días (Excepto Qualitas y GNP que pueden variar a 14 días).</li>
        </ul>
    </div>

    <div class="footer">
        <strong>CONOCE AGENTE DE SEGUROS Y DE FIANZAS, S.A. DE C.V.</strong><br>
        Calle 8 No- 424-E x 21 Col. Díaz Ordaz, Mérida, Yucatán. CP 97130<br>
        Tel: (999) 944-68-35 | WhatsApp: (999) 163-0369
        <br><br>
        Cotización generada por Agente ID: {{ $quote->agent_id }} el {{ date('d/m/Y H:i') }}
    </div>

</body>

</html>
