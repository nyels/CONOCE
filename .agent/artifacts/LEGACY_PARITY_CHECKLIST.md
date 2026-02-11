# CHECKLIST DE PARIDAD FUNCIONAL LEGACY → LARAVEL 12 + VUE 3

**Fuente de verdad:** `LEGACY_FORENSIC_CANONICAL.md` → `LEGACY_SYSTEM_FORENSIC_ANALYSIS.md`

**Fecha de generación:** 2026-01-28

---

## 1. SERVICIOS DE CÁLCULO

### 1.1 PremiumCalculationService

| Elemento | Legacy | Laravel | Estado |
|----------|--------|---------|--------|
| Fórmula prima neta ANUAL | `(prima_anual_neta / 1.16) - derecho_costo` | `calculateNetPremium()` | ✅ IMPLEMENTADO |
| Fórmula prima neta NO ANUAL | `base / (1 + (recargo / 100))` | `calculateNetPremium()` | ✅ IMPLEMENTADO |
| String error sin derecho | `'no_derecho'` | Return `['error' => 'no_derecho']` | ✅ IMPLEMENTADO |
| String error sin recargo | `'no_recargo'` | Return `['error' => 'no_recargo']` | ✅ IMPLEMENTADO |
| Subsecuentes ANUAL | `'N/A'` | `calculateSubsequentPayments()` | ✅ IMPLEMENTADO |
| Subsecuentes SEMESTRAL | `primer_pago` (2 pagos iguales) | `calculateSubsequentPayments()` | ✅ IMPLEMENTADO |
| Subsecuentes TRIMESTRAL | `(total - primer) / 3` | `calculateSubsequentPayments()` | ✅ IMPLEMENTADO |
| Subsecuentes MENSUAL | `(total - primer) / 11` | `calculateSubsequentPayments()` | ✅ IMPLEMENTADO |

**Archivo:** `app/Services/PremiumCalculationService.php`

---

## 2. CONTROLLERS

### 2.1 CalculationController (API)

| Endpoint | Legacy | Laravel | Estado |
|----------|--------|---------|--------|
| `POST /api/quotes/calculate-premium` | `metodos_sacar_prima_neta` | `calculateNetPremium()` | ✅ IMPLEMENTADO |
| `POST /api/quotes/calculate-subsequent` | `cotizaciones_nuevas_crear_subsecuentes` | `calculateSubsequent()` | ✅ IMPLEMENTADO |
| `POST /api/quotes/calculate-realtime` | (unificado) | `calculateRealtime()` | ✅ IMPLEMENTADO |
| `POST /api/quotes/calculate-batch` | (múltiples columnas) | `calculateBatch()` | ✅ IMPLEMENTADO |

**Archivo:** `app/Http/Controllers/Api/CalculationController.php`

### 2.2 QuoteController

| Elemento | Legacy | Laravel | Estado |
|----------|--------|---------|--------|
| Response store | `"1*{id}*{nombre}-{desc}"` | Agregado en `store()` | ✅ IMPLEMENTADO |
| `storeLegacy()` method | N/A | Para compatibilidad legacy | ✅ IMPLEMENTADO |

**Archivo:** `app/Http/Controllers/QuoteController.php`

---

## 3. RUTAS API

| Ruta | Método | Controller | Estado |
|------|--------|------------|--------|
| `/api/quotes/calculate-premium` | POST | `CalculationController@calculateNetPremium` | ✅ REGISTRADA |
| `/api/quotes/calculate-subsequent` | POST | `CalculationController@calculateSubsequent` | ✅ REGISTRADA |
| `/api/quotes/calculate-realtime` | POST | `CalculationController@calculateRealtime` | ✅ REGISTRADA |
| `/api/quotes/calculate-batch` | POST | `CalculationController@calculateBatch` | ✅ REGISTRADA |

**Archivo:** `routes/api.php`

---

## 4. VALIDACIONES (StoreQuoteRequest)

### 4.1 Validaciones Principales

| Campo | Legacy | Laravel | Estado |
|-------|--------|---------|--------|
| `tipo_cotizacion` | `!= 0` | `required|in:NUEVA,RENOVACION` | ✅ |
| `hora_solicitada` | `!= ''` (si NUEVA) | `required_if` | ✅ |
| `customer_id` | `prospectos_asegurados != 0` | `required|exists` | ✅ |
| `vehiculo.marca` | regex alfanumérico | `required|regex` | ✅ |
| `vehiculo.modelo` | 4 dígitos | `required|size:4|regex` | ✅ |
| `paquete` | `!= 0` | `required|in:AMPLIA,LIMITADA,RC` | ✅ |
| `cantidad_aseguradoras` | `!= 0` | `required|integer|min:1|max:5` | ✅ |

### 4.2 Validaciones Condicionales por N (cantidad_aseguradoras)

| Campo | Condición | Estado |
|-------|-----------|--------|
| `empresa_opcion_1` | Siempre required | ✅ |
| `empresa_opcion_2` | Si N >= 2 | ✅ |
| `empresa_opcion_3` | Si N >= 3 | ✅ |
| `empresa_opcion_4` | Si N >= 4 | ✅ |
| `empresa_opcion_5` | Si N >= 5 | ✅ |

### 4.3 Validaciones Semánticas (Backend After)

| Validación | Legacy | Laravel | Estado |
|------------|--------|---------|--------|
| `primer_pago <= total_anual` | Implícita | `withValidator()` | ✅ |
| Aseguradora si hay total | Implícita | `withValidator()` | ✅ |
| Valores positivos | Implícita | `withValidator()` | ✅ |
| Regex vehículo (marca, desc) | Frontend | `validateVehicleFields()` | ✅ |
| Regex renovación | Frontend | `validateRenewalFields()` | ✅ |

**Archivo:** `app/Http/Requests/StoreQuoteRequest.php`

---

## 5. MODELOS

### 5.1 Quote

| Campo Legacy | Campo Laravel | Estado |
|--------------|---------------|--------|
| `Id_contizacion_autos` | `id` | ✅ |
| `tipo_cotizacion` | `type` (enum) | ✅ |
| `hora_solicitada` | `requested_at` | ✅ |
| `Id_contacto_tabla_autos` | `contact_id` | ✅ |
| `Id_prospecto_tabla_autos` | `customer_id` | ✅ |
| `marca_auto` | `vehicle_data['brand']` | ✅ |
| `descripcion_auto` | `vehicle_data['model']` | ✅ |
| `modelo_auto` | `vehicle_data['year']` | ✅ |
| `uso_de_unidad_auto` | `vehicle_usage` | ✅ |
| `tipo_auto` | `vehicle_type_id` | ✅ |
| `paquete_solicitado` | `package_type` (enum) | ✅ |
| `cantidad_aseguradoras` | `options_count` | ✅ |
| `compania_autos` | `previous_insurer` | ✅ |
| `fecha_vigencia_autos` | `previous_expiry_date` | ✅ |
| `poliza_renovar_autos` | `previous_policy_number` | ✅ |
| `prima_ano_autos` | `previous_premium_cents` | ✅ |
| `tipo_de_carga` | `vehicle_data['cargo_type']` | ✅ |

### 5.2 QuoteOption

| Campo Legacy | Campo Laravel | Estado |
|--------------|---------------|--------|
| `empresa_opcion_N` | `insurer_id` | ✅ |
| `forma_de_pago_opcion_N` | `payment_frequency` | ✅ |
| `cantidad_prima_neta_opcion_N` | `net_premium_cents` | ✅ |
| `cantidad_total_anual_opcion_N` | `total_premium_cents` | ✅ |
| `primer_pago_opcion_N` | `first_payment_cents` | ✅ |
| `subsecuente_opcion_N` | `subsequent_payment_cents` | ✅ |
| Coberturas (30+ campos) | `coverages` JSON + campos individuales | ✅ |

---

## 6. CONTRATOS DE RESPONSE

### 6.1 POST /api/quotes/calculate-premium

**Request:**
```json
{
    "prima_anual_neta": 15000.00,
    "forma_pago": "ANUAL",
    "insurer_id": 1
}
```

**Response SUCCESS:**
```json
{
    "prima_neta": 12431.03,
    "derecho_costo": 500.00,
    "recargo": 0
}
```

**Response ERROR:**
```json
{
    "prima_neta": 0,
    "derecho_costo": 0,
    "recargo": 0,
    "error": "no_derecho"
}
```

### 6.2 POST /api/quotes/calculate-subsequent

**Request:**
```json
{
    "prima_total_anual": 15000.00,
    "primer_pago": 4000.00,
    "forma_pago": "TRIMESTRAL"
}
```

**Response:**
```json
{
    "subsecuentes": 3666.67,
    "numero_pagos": 4
}
```

### 6.3 POST /api/quotes (store)

**Response JSON (si es AJAX):**
```json
{
    "success": true,
    "legacy_response": "1*123*Juan Pérez-Toyota Corolla 2024",
    "quote_id": 123,
    "quote_uuid": "abc-def-ghi",
    "folio": "COT-2026-00123"
}
```

---

## 7. RIESGOS Y LIMITACIONES

### 7.1 Implementado pero Requiere Verificación

| Elemento | Nota |
|----------|------|
| Recargos decimal vs porcentaje | El modelo almacena como decimal (0.05), legacy usa porcentaje (5). Conversión implementada. |
| Derecho en centavos | El modelo almacena en centavos, se divide por 100 para cálculo. |
| Orden de eventos Vue | Debe verificarse en frontend que los watchers respeten: change → focusout → ajax |

### 7.2 NO Implementado (Fuera de Alcance Actual)

| Elemento | Razón |
|----------|-------|
| CreateQuote.vue completo | Requiere implementación separada de frontend |
| PDF generation legacy | Requiere verificación de layout exacto |
| Validación aseguradora única | Debe verificarse en frontend (no duplicar aseguradoras) |
| Validación bidireccional coberturas | cobertura_nombre ↔ cobertura_valor |

---

## 8. ARCHIVOS MODIFICADOS/CREADOS

| Archivo | Acción | Descripción |
|---------|--------|-------------|
| `app/Services/PremiumCalculationService.php` | EXISTENTE | Fórmulas legacy ya implementadas |
| `app/Http/Controllers/Api/CalculationController.php` | CREADO | Endpoints legacy de cálculo |
| `app/Http/Controllers/QuoteController.php` | MODIFICADO | Response legacy en store() |
| `routes/api.php` | MODIFICADO | Rutas de cálculo legacy |
| `app/Http/Requests/StoreQuoteRequest.php` | EXISTENTE | Validaciones legacy ya implementadas |

---

## 9. PRÓXIMOS PASOS (NO EN ESTE ALCANCE)

1. Verificar CreateQuote.vue tiene los watchers correctos
2. Verificar PDF tiene layout idéntico
3. Pruebas de integración con valores reales
4. Verificar validaciones frontend coinciden con backend

---

## CONCLUSIÓN

**Estado:** BACKEND COMPLETO

Los servicios y endpoints de cálculo replican EXACTAMENTE las fórmulas legacy:
- Prima neta: ✅
- Subsecuentes: ✅
- Strings de error: ✅
- Response store: ✅

El frontend (Vue 3) debe consumir estos endpoints respetando el orden de eventos legacy.
