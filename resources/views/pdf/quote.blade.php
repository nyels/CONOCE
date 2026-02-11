<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Cotización #{{ $quote->folio }}</title>

    {{-- LÓGICA DE TAMAÑOS DINÁMICOS --}}
    @php
        $count = $quote->options->count();

        // Tamaños adaptativos según número de opciones
        if ($count <= 2) {
            $tableSize = 12;
            $padding = 4;
            $logoSize = 50;
        } elseif ($count == 3) {
            $tableSize = 11;
            $padding = 3;
            $logoSize = 45;
        } else {
            $tableSize = 10;
            $padding = 2;
            $logoSize = 40;
        }

        // Soportar tanto draft (string 'AMPLIA') como guardado (enum CoveragePackage::FULL)
        $rawPackage = $quote->coverage_package ?? (property_exists($quote, 'package_type') ? $quote->package_type : null) ?? 'AMPLIA';
        $packageMap = ['FULL' => 'AMPLIA', 'LIMITED' => 'LIMITADA', 'LIABILITY_ONLY' => 'RESPONSABILIDAD CIVIL'];
        $packageStr = is_object($rawPackage) ? ($rawPackage->value ?? (string)$rawPackage) : (string)$rawPackage;
        $package = $packageMap[$packageStr] ?? $packageStr;
        $isAmplia = $package === 'AMPLIA';
        $isLimitada = $package === 'LIMITADA';
        $isRC = $package === 'RESPONSABILIDAD CIVIL';
    @endphp

    <style>
        @page {
            margin: 0.3cm 0.4cm 1.2cm 0.4cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.2;
            margin: 0;
            padding: 0;
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
            border-bottom: 2px solid #632533;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }

        .quote-title {
            font-size: 22px;
            font-weight: bold;
            color: #632533;
            text-transform: uppercase;
            margin: 0;
        }

        .agency-logo {
            max-height: 55px;
            display: block;
            margin-left: auto;
        }

        /* Cajas Info */
        .info-box {
            border: 1px solid #ccc;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 4px;
        }

        .info-header {
            background-color: #eee;
            color: #632533;
            font-weight: bold;
            padding: 3px 6px;
            font-size: 10px;
            border-bottom: 1px solid #ccc;
            text-transform: uppercase;
        }

        .info-content {
            padding: 3px 6px;
            font-size: 10px;
            line-height: 1.4;
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

        .section-header td {
            background-color: #eee;
            color: #632533;
            font-weight: bold;
            font-size: {{ $tableSize + 1 }}px;
            text-align: center;
            border: 1px solid #e0e0e0;
            padding: 3px;
        }

        /* Totales */
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

        /* Notas */
        .notes {
            margin-top: 6px;
            font-size: 9px;
            color: #444;
            line-height: 1.4;
        }

        .notes p {
            margin: 2px 0;
        }

        /* Footer empresa */
        .company-footer {
            margin-top: 6px;
            text-align: center;
            font-size: 10px;
            color: #632533;
            border-top: 1px solid #632533;
            padding-top: 4px;
        }

        .elaborated-by {
            margin-top: 3px;
            text-align: center;
            font-size: 9px;
            color: #555;
        }

        .whatsapp-link {
            text-decoration: none;
            color: #632533;
            font-weight: bold;
            display: inline-block;
            vertical-align: middle;
            font-size: 10px;
        }

        .whatsapp-icon {
            width: 14px;
            height: 14px;
            vertical-align: middle;
            margin-right: 2px;
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

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td width="65%" style="vertical-align: bottom;">
                <h1 class="quote-title">Cotización</h1>
                <div style="font-size: 11px; margin-top: 2px;">
                    @php
                        // Soportar draft (string 'NUEVA') y guardado (enum QuoteType::NEW)
                        $quoteTypeRaw = $quote->quote_type ?? (property_exists($quote, 'type') ? $quote->type : null) ?? '';
                        $typeMap = ['NEW' => 'NUEVA', 'RENEWAL' => 'RENOVACION'];
                        $quoteTypeStr = is_object($quoteTypeRaw) ? ($quoteTypeRaw->value ?? (string)$quoteTypeRaw) : (string)$quoteTypeRaw;
                        $quoteTypeDisplay = $typeMap[$quoteTypeStr] ?? $quoteTypeStr;
                    @endphp
                    @if (!empty($quoteTypeDisplay))
                        <span class="brand-color">Tipo:</span> <strong>{{ $quoteTypeDisplay }}</strong> |
                    @endif
                    <span class="brand-color">Fecha:</span> {{ $quote->created_at->format('d/m/Y') }} |
                    <span class="brand-color">Folio:</span> <strong>{{ $quote->folio }}</strong>
                </div>
            </td>
            <td width="35%">
                <img src="{{ public_path('logo.png') }}" class="agency-logo">
            </td>
        </tr>
    </table>

    {{-- INFORMACIÓN DEL ASEGURADO + VEHÍCULO --}}
    <table>
        <tr>
            <td width="50%" style="padding-right: 4px; vertical-align: top;">
                <div class="info-box">
                    <div class="info-header">Información del Asegurado</div>
                    <div class="info-content">
                        @php
                            // Modelo Customer tiene accessor full_name (incluye apellido materno)
                            // Draft stdClass solo tiene 'name'
                            $customerName = ($customer instanceof \App\Models\Customer)
                                ? $customer->full_name
                                : ($customer->name ?? 'Cliente Prospecto');
                        @endphp
                        <div><span class="label">Nombre:</span> {{ $customerName }}</div>
                        <div>
                            <span class="label">CP:</span> {{ $customer->zip_code ?? '' }}
                            @if (!empty($customer->neighborhood))
                                | <span class="label">Colonia:</span> {{ $customer->neighborhood }}
                            @endif
                            @if (!empty($customer->state))
                                | <span class="label">Estado:</span> {{ $customer->state }}
                            @endif
                        </div>
                    </div>
                </div>
            </td>
            <td width="50%" style="padding-left: 4px; vertical-align: top;">
                <div class="info-box">
                    <div class="info-header">Descripción del Vehículo</div>
                    <div class="info-content">
                        @php $vType = $quote->vehicle_data['type'] ?? (property_exists($quote, 'vehicle_type') ? $quote->vehicle_type : null) ?? ''; @endphp
                        <div><span class="label">Marca:</span> {{ $quote->vehicle_data['brand'] ?? '' }}
                            @if (!empty($vType) && $vType !== '0')
                                | <span class="label">Tipo:</span> {{ $vType }}
                            @endif
                        </div>
                        <div><span class="label">Descripción:</span> {{ $quote->vehicle_data['description'] ?? $quote->vehicle_data['model'] ?? '' }}</div>
                        <div><span class="label">Modelo:</span> {{ $quote->vehicle_data['year'] ?? '' }}
                            @if (!empty($quote->vehicle_data['usage']))
                                | <span class="label">Uso:</span> {{ $quote->vehicle_data['usage'] }}
                            @endif
                        </div>
                        @php $cargoVal = $quote->vehicle_data['cargo'] ?? $quote->vehicle_data['cargo_type'] ?? ''; @endphp
                        @if (!empty($cargoVal) && $cargoVal !== '0')
                            <div><span class="label">Carga:</span> {{ $cargoVal }}</div>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- INFORMACIÓN DE PÓLIZA A RENOVAR (condicional) --}}
    @php
        $isRenewal = $quoteTypeDisplay === 'RENOVACION';
        // Soportar draft (renewal_data array) y guardado (columnas individuales)
        $rd = is_array($quote->renewal_data ?? null) ? $quote->renewal_data : [];
        $renewalInsurer = $rd['current_insurer'] ?? (property_exists($quote, 'previous_insurer') ? $quote->previous_insurer : '') ?? '';
        $renewalExpiry = $rd['expiry_date'] ?? (property_exists($quote, 'previous_expiry_date') && $quote->previous_expiry_date ? $quote->previous_expiry_date->format('d/m/Y') : '');
        $renewalPolicy = $rd['policy_number'] ?? (property_exists($quote, 'previous_policy_number') ? $quote->previous_policy_number : '') ?? '';
        $renewalPremium = $rd['previous_premium'] ?? (property_exists($quote, 'previous_premium_cents') && $quote->previous_premium_cents ? number_format($quote->previous_premium_cents / 100, 2) : '0');
    @endphp
    @if ($isRenewal && ($renewalInsurer || $renewalPolicy))
        <div class="info-box">
            <div class="info-header">Información Póliza a Renovar</div>
            <div class="info-content">
                <table>
                    <tr>
                        <td width="50%">
                            <span class="label">Compañía actual:</span> {{ $renewalInsurer }}
                        </td>
                        <td width="50%">
                            <span class="label">Fin de vigencia:</span> {{ $renewalExpiry }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="label">Póliza a renovar:</span> {{ $renewalPolicy }}
                        </td>
                        <td>
                            <span class="label">Prima total año anterior:</span> ${{ $renewalPremium }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endif

    {{-- PAQUETE + PAGO --}}
    @php
        $freq = $quote->options->first()->payment_frequency ?? 'ANUAL';
    @endphp
    <div style="margin: 4px 0 3px 0; font-weight: bold; color: #632533; font-size: 11px;">
        PAQUETE: {{ $package }} — PAGO: {{ $freq }}
    </div>

    {{-- TABLA COMPARATIVA COMPLETA --}}
    <table class="comparison-table">
        <thead>
            <tr>
                <th class="col-concept">COBERTURAS</th>
                @foreach ($quote->options as $option)
                    <th>
                        @if ($option->insurer->logo_path && file_exists(public_path($option->insurer->logo_path)))
                            <img src="{{ public_path($option->insurer->logo_path) }}" class="insurer-logo-circle">
                        @else
                            <div style="font-weight: bold;">{{ $option->insurer->name }}</div>
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- DAÑOS MATERIALES (solo AMPLIA) --}}
            @if ($isAmplia)
                <tr>
                    <td class="col-concept">Daños Materiales</td>
                    @foreach ($quote->options as $option)
                        <td>{{ $option->coverages['danos'] ?? 'V.COMERCIAL' }}</td>
                    @endforeach
                </tr>

                {{-- Importe factura DM (si aplica) --}}
                @if (collect($quote->options)->contains(fn($o) => ($o->coverages['danos'] ?? '') === 'V.FACTURA'))
                    <tr>
                        <td class="col-concept" style="padding-left: 12px; font-weight: normal;">Importe Factura</td>
                        @foreach ($quote->options as $option)
                            <td>{{ !empty($option->coverages['danos_importe']) ? '$' . number_format(floatval(str_replace(',', '', $option->coverages['danos_importe'])), 2) : '' }}</td>
                        @endforeach
                    </tr>
                @endif

                <tr>
                    <td class="col-concept">Deducible DM</td>
                    @foreach ($quote->options as $option)
                        @php $dedDm = $option->coverages['deducible_dm'] ?? 'na'; @endphp
                        <td>{{ $dedDm === 'na' ? 'N/A' : $dedDm . '%' }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td class="col-concept">Cristales</td>
                    @foreach ($quote->options as $option)
                        <td>{{ $option->coverages['cristales'] ?? 'AMPARADA' }}</td>
                    @endforeach
                </tr>
            @endif

            {{-- ROBO TOTAL (AMPLIA y LIMITADA) --}}
            @if ($isAmplia || $isLimitada)
                <tr>
                    <td class="col-concept">Robo Total</td>
                    @foreach ($quote->options as $option)
                        <td>{{ $option->coverages['robo'] ?? 'V.COMERCIAL' }}</td>
                    @endforeach
                </tr>

                {{-- Importe factura RT (si aplica) --}}
                @if (collect($quote->options)->contains(fn($o) => ($o->coverages['robo'] ?? '') === 'V.FACTURA'))
                    <tr>
                        <td class="col-concept" style="padding-left: 12px; font-weight: normal;">Importe Factura</td>
                        @foreach ($quote->options as $option)
                            <td>{{ !empty($option->coverages['robo_importe']) ? '$' . number_format(floatval(str_replace(',', '', $option->coverages['robo_importe'])), 2) : '' }}</td>
                        @endforeach
                    </tr>
                @endif

                <tr>
                    <td class="col-concept">Deducible RT</td>
                    @foreach ($quote->options as $option)
                        @php $dedRt = $option->coverages['deducible_rt'] ?? 'na'; @endphp
                        <td>{{ $dedRt === 'na' ? 'N/A' : $dedRt . '%' }}</td>
                    @endforeach
                </tr>
            @endif

            {{-- COBERTURAS COMUNES (todos los paquetes) --}}
            <tr>
                <td class="col-concept">R.C. Daños a Terceros</td>
                @foreach ($quote->options as $option)
                    <td>{{ !empty($option->coverages['rc_terceros']) ? '$' . number_format(floatval(str_replace(',', '', $option->coverages['rc_terceros'])), 2) : '' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">Deducible de R.C.</td>
                @foreach ($quote->options as $option)
                    @php $dedRc = $option->coverages['deducible_rc'] ?? ''; @endphp
                    <td>{{ $dedRc !== '' ? $dedRc . '%' : '' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">R.C. Fallecimiento</td>
                @foreach ($quote->options as $option)
                    <td>{{ !empty($option->coverages['rc_fallecimiento']) ? '$' . number_format(floatval(str_replace(',', '', $option->coverages['rc_fallecimiento'])), 2) : '' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">Gastos Médicos Ocupantes</td>
                @foreach ($quote->options as $option)
                    <td>{{ !empty($option->coverages['gastos_medicos']) ? '$' . number_format(floatval(str_replace(',', '', $option->coverages['gastos_medicos'])), 2) : '' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">Accidentes al Conductor</td>
                @foreach ($quote->options as $option)
                    <td>{{ !empty($option->coverages['accidentes_conductor']) ? '$' . number_format(floatval(str_replace(',', '', $option->coverages['accidentes_conductor'])), 2) : '' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">Protección Legal</td>
                @foreach ($quote->options as $option)
                    <td>{{ $option->coverages['proteccion_legal'] ?? 'AMPARADA' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">Asistencia Vial</td>
                @foreach ($quote->options as $option)
                    <td>{{ $option->coverages['asistencia_vial'] ?? 'AMPARADA' }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">Daños por la Carga</td>
                @foreach ($quote->options as $option)
                    @php $dc = $option->coverages['danos_carga'] ?? '0'; @endphp
                    <td>{{ $dc === '0' ? '' : $dc }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">Extensión de R.C.</td>
                @foreach ($quote->options as $option)
                    @php $erc = $option->coverages['extension_rc'] ?? '0'; @endphp
                    <td>{{ $erc === '0' ? '' : $erc }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="col-concept">Adaptaciones / Eq. Especial</td>
                @foreach ($quote->options as $option)
                    <td>{{ !empty($option->coverages['adaptaciones']) ? '$' . number_format(floatval(str_replace(',', '', $option->coverages['adaptaciones'])), 2) : '' }}</td>
                @endforeach
            </tr>

            {{-- Descripción (global) --}}
            @php $desc = $quote->options->first()->coverages['descripcion'] ?? ''; @endphp
            @if (!empty($desc))
                <tr>
                    <td class="col-concept">Descripción</td>
                    <td colspan="{{ $count }}" style="text-align: left;">{{ $desc }}</td>
                </tr>
            @endif

            {{-- Coberturas opcionales custom --}}
            @php $c1name = $quote->options->first()->coverages['custom1_name'] ?? (property_exists($quote, 'custom_coverage_1_name') ? $quote->custom_coverage_1_name : null) ?? ''; @endphp
            @if (!empty($c1name))
                <tr>
                    <td class="col-concept">{{ $c1name }}</td>
                    @foreach ($quote->options as $option)
                        @php $cv = $option->coverages['custom1_value'] ?? '0'; @endphp
                        <td>{{ $cv === '0' ? '' : $cv }}</td>
                    @endforeach
                </tr>
            @endif

            @php $c2name = $quote->options->first()->coverages['custom2_name'] ?? (property_exists($quote, 'custom_coverage_2_name') ? $quote->custom_coverage_2_name : null) ?? ''; @endphp
            @if (!empty($c2name))
                <tr>
                    <td class="col-concept">{{ $c2name }}</td>
                    @foreach ($quote->options as $option)
                        @php $cv2 = $option->coverages['custom2_value'] ?? '0'; @endphp
                        <td>{{ $cv2 === '0' ? '' : $cv2 }}</td>
                    @endforeach
                </tr>
            @endif

            {{-- ===== SECCIÓN: DESGLOSE DE PRIMA ===== --}}
            <tr class="section-header">
                <td colspan="{{ $count + 1 }}">DESGLOSE DE PRIMA</td>
            </tr>

            <tr>
                <td class="col-concept">Prima Neta Anual</td>
                @foreach ($quote->options as $option)
                    <td>${{ number_format($option->net_premium, 2) }}</td>
                @endforeach
            </tr>

            <tr class="total-row">
                <td style="text-align: left;">PRIMA TOTAL ANUAL</td>
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
            @php
                $subsCount = match ($freq) {
                    'SEMESTRAL' => 1,
                    'TRIMESTRAL' => 3,
                    'MENSUAL' => 11,
                    default => 0,
                };
            @endphp
            @if ($subsCount > 0)
                <tr class="payment-row">
                    <td class="col-concept">Subsecuentes ({{ $subsCount }})</td>
                    @foreach ($quote->options as $option)
                        <td><strong>${{ number_format($option->subsequent_payment ?? 0, 2) }}</strong></td>
                    @endforeach
                </tr>
            @endif
        </tbody>
    </table>

    {{-- NOTAS LEGALES --}}
    <div class="notes">
        <p><strong>Notas sobre la cotización:</strong></p>
        <p>*La Prima Total Anual incluye derecho de póliza, IVA y recargo por pago fraccionado, si la forma de pago es diferente a ANUAL.</p>
        <p>*La presente cotización tiene una vigencia de 15 días naturales contados a partir de la fecha de elaboración y/o vencimiento de su póliza a renovar.</p>
        <p>*En ningún caso se considera que esta cotización es una póliza de seguro.</p>
        <p>*En caso de contratar el seguro, el periodo de gracia para pagar la póliza es de 30 días naturales contados a partir de la fecha de inicio de vigencia de su póliza, excepto Qualitas y GNP que solo otorgan 14 días.</p>
    </div>

    {{-- FOOTER EMPRESA + ELABORÓ --}}
    <div class="company-footer">
        <strong>CONOCE AGENTE DE SEGUROS Y DE FIANZAS, S.A. DE C.V.</strong><br>
        Calle 8 No. 424-E x 21 Col. Díaz Ordaz, Mérida, Yucatán. C.P. 97130<br>
        Tel: (999) 944-68-35 | (999) 944-68-39
        <a href="https://wa.me/5219991630369" class="whatsapp-link" target="_blank">
            <img style="width: 16px;" src="{{ public_path('whatsapp.png') }}" class="whatsapp-icon">
            (999) 163-0369
        </a>
    </div>
    <div class="elaborated-by">
        <strong>Elaboró:</strong> {{ $elaboratedBy ?? 'Usuario' }} |
        <strong>Fecha de elaboración:</strong> {{ $quote->created_at->format('d/m/Y') }}
    </div>
</body>

</html>
