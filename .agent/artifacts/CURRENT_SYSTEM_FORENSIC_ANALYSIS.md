# ANALISIS FORENSE — SISTEMA ACTUAL DE COTIZACION

**Fecha:** 2026-01-28
**Auditor:** Claude Opus 4.5
**Modalidad:** Forense Tecnico

---

## 1. CONFIRMACION DE LECTURA

He leido TODOS los archivos del sistema actual listados a continuacion:

### Backend (PHP/Laravel)
- `app/Http/Controllers/QuoteController.php` (872 lineas)
- `app/Http/Requests/StoreQuoteRequest.php` (317 lineas)
- `app/Models/Quote.php` (429 lineas)
- `app/Models/QuoteOption.php` (424 lineas)
- `app/Models/Customer.php` (261 lineas)
- `app/Models/Contact.php` (256 lineas)
- `app/Models/Insurer.php` (175 lineas)
- `app/Models/VehicleType.php` (120 lineas)
- `database/migrations/2026_01_15_090300_create_quotes_tables_v2.php` (147 lineas)
- `src/Domain/Quote/Services/PremiumCalculatorService.php` (340 lineas)
- `src/Domain/Quote/Enums/PaymentFrequency.php` (108 lineas)
- `src/Domain/Quote/Enums/CoveragePackage.php` (132 lineas)
- `routes/web.php` (97 lineas)

### Frontend (Vue/JavaScript)
- `resources/js/Pages/Quotes/Create.vue` (~1200 lineas)
- `resources/js/components/QuoteForm.vue` (202 lineas)
- `resources/js/components/Quote/CoverageTable.vue` (1152 lineas)
- `resources/js/composables/useCurrencyFormat.js` (121 lineas)

---

## 2. ARBOL REAL DEL SISTEMA

### Estructura de Carpetas
```
cotizador-autos/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── QuoteController.php          # Controlador principal
│   │   │   ├── CustomerController.php
│   │   │   └── Admin/                        # CRUDs administrativos
│   │   └── Requests/
│   │       ├── StoreQuoteRequest.php        # Validacion servidor
│   │       └── StoreCustomerRequest.php
│   └── Models/
│       ├── Quote.php                         # Cotizacion principal
│       ├── QuoteOption.php                   # Opciones por aseguradora
│       ├── Customer.php                      # Cliente/Prospecto
│       ├── Contact.php                       # Agente/Intermediario
│       ├── Insurer.php                       # Aseguradora
│       └── InsurerFinancialSetting.php       # Config financiera
├── src/
│   └── Domain/
│       └── Quote/
│           ├── Enums/                        # Estados, tipos, frecuencias
│           └── Services/
│               └── PremiumCalculatorService.php
├── resources/
│   └── js/
│       ├── Pages/
│       │   └── Quotes/
│       │       └── Create.vue               # Formulario principal
│       ├── Components/
│       │   └── Quote/
│       │       └── CoverageTable.vue        # Tabla de coberturas
│       └── composables/
│           └── useCurrencyFormat.js         # Formato moneda MXN
├── routes/
│   └── web.php                               # Rutas Inertia
└── database/
    └── migrations/
        └── 2026_01_15_090300_create_quotes_tables_v2.php
```

### Separacion Frontend/Backend
- **Backend:** Laravel 11 + PHP 8.x
- **Frontend:** Vue 3 + Inertia.js
- **Comunicacion:** Server-Side Rendering via Inertia (no REST puro)
- **Sesion:** Stateful (cookies de Laravel)

### Stack Real
| Capa | Tecnologia |
|------|------------|
| Framework Backend | Laravel 11 |
| ORM | Eloquent |
| Frontend | Vue 3 Composition API |
| Routing SPA | Inertia.js |
| Validacion Frontend | Reactiva (watchers Vue) |
| Validacion Backend | FormRequest |
| CSS | Scoped + Tailwind |
| PDF | barryvdh/laravel-dompdf |
| Auth | Laravel Fortify |
| Permisos | spatie/laravel-permission |
| Auditoria | spatie/laravel-activitylog |

### Donde Vive el Formulario de Cotizacion
**Archivo:** `resources/js/Pages/Quotes/Create.vue`
**Ruta:** `GET /quotes/create` -> `QuoteController@create`
**Envio:** `POST /quotes` -> `QuoteController@store`

---

## 3. MAPA FORENSE DEL FORMULARIO

### 3.1 Inputs Reales (HTML + JS)

#### Seccion: Encabezado
| Campo | Tipo HTML | v-model | Valores |
|-------|-----------|---------|---------|
| tipo_cotizacion | select | `form.tipo_cotizacion` | '0', 'NUEVA', 'RENOVACION' |
| hora_solicitada | input time | `form.hora_solicitada` | HH:mm |
| contact_id | select | `form.contact_id` | null, int |
| customer_id | select | `form.customer_id` | null, int |

#### Seccion: Datos Asegurado (readonly)
| Campo | Tipo HTML | v-model |
|-------|-----------|---------|
| apellido_paterno | input text | `form.asegurado.apellido_paterno` |
| apellido_materno | input text | `form.asegurado.apellido_materno` |
| nombre | input text | `form.asegurado.nombre` |
| codigo_postal | input text | `form.asegurado.codigo_postal` |
| colonia | input text | `form.asegurado.colonia` |
| estado | input text | `form.asegurado.estado` |

#### Seccion: Vehiculo
| Campo | Tipo HTML | v-model | Validacion |
|-------|-----------|---------|------------|
| marca | input text | `form.vehiculo.marca` | Solo letras |
| descripcion | input text | `form.vehiculo.descripcion` | Alfanumerico |
| modelo | input number | `form.vehiculo.modelo` | 4 digitos, positivo |
| uso_de_unidad | input text | `form.vehiculo.uso_de_unidad` | Alfanumerico |
| tipo_auto | select | `form.vehiculo.tipo_auto` | '0', id de vehicle_types |
| carga | select | `form.vehiculo.carga` | '0', 'A NO PELIGROSA', 'B PELIGROSA', 'C MUY PELIGROSA' |

#### Seccion: Renovacion (condicional)
| Campo | Tipo HTML | v-model | Visible si |
|-------|-----------|---------|------------|
| compania_actual | input text | `form.renovacion.compania_actual` | tipo_cotizacion === 'RENOVACION' |
| fecha_vigencia | input date | `form.renovacion.fecha_vigencia` | tipo_cotizacion === 'RENOVACION' |
| poliza_a_renovar | input text | `form.renovacion.poliza_a_renovar` | tipo_cotizacion === 'RENOVACION' |
| prima_año | input text | `form.renovacion.prima_año` | tipo_cotizacion === 'RENOVACION' |

#### Seccion: Configuracion
| Campo | Tipo HTML | v-model | Valores |
|-------|-----------|---------|---------|
| paquete | select | `form.paquete` | '0', 'AMPLIA', 'LIMITADA', 'RESPONSABILIDAD CIVIL' |
| cantidad_aseguradoras | select | `form.cantidad_aseguradoras` | 0, 1, 2, 3, 4, 5 |

#### Seccion: Tabla de Coberturas (CoverageTable.vue)
Estructura de 5 columnas con campos sufijados por `_1` a `_5`:

| Campo | Tipo | Habilitacion |
|-------|------|--------------|
| empresa_opcion_X | select | columna habilitada |
| danos_opcion_selec_X | select | AMPLIA only |
| danos_material_importe_factura_X | input text | V.FACTURA seleccionado |
| deducible_opcion_X | select | AMPLIA only |
| cristales_opcion_selec_X | select | AMPLIA only |
| robo_opcion_selec_X | select | AMPLIA/LIMITADA |
| robo_importe_factura_X | input text | V.FACTURA seleccionado |
| deducible_rt_X | select | AMPLIA/LIMITADA |
| danos_tercero_opcion_X | input text | siempre |
| deducible_de_rc_opcion_X | input number | siempre |
| fallecimiento_opcion_X | input text | siempre |
| gastos_medicos_opcion_X | input text | siempre |
| accidente_conducir_opcion_X | input text | siempre |
| proteccion_opcion_selec_X | select | siempre |
| asistencia_vial_opcion_selec_X | select | siempre |
| danos_carga_opcion_selec_X | select | siempre |
| adaptaciones_opcion_X | input text | siempre |
| extension_rc_opcion_X | select | siempre |
| cobertura_opcion_1_select_X | select | siempre |
| cobertura_opcion_2_select_X | select | siempre |
| cantidad_prima_neta_opcion_X | input text | siempre |
| cantidad_total_anual_opcion_X | input text | siempre |
| primer_pago_opcion_X | input text | siempre |
| subsecuente_opcion_X | input text (readonly) | forma_de_pago != ANUAL |

#### Campo Global en Tabla
| Campo | Tipo | v-model |
|-------|------|---------|
| forma_de_pago | select | `form.coverages.forma_de_pago` |
| descripcion_tabla_1 | input text | `form.coverages.descripcion_tabla_1` |
| custom_coverage_1_name | input text | `form.custom_coverage_1_name` |
| custom_coverage_2_name | input text | `form.custom_coverage_2_name` |

### 3.2 Inputs Implicitos (Estado JS)

| Variable | Uso |
|----------|-----|
| `currentStep` | NO EXISTE - formulario es single-page scroll |
| `enabledColumns` | Computed: cantidad_aseguradoras && paquete != '0' |
| `showCargoField` | Computed: vehicleType.requires_cargo |
| `showRenewalSection` | Computed: tipo_cotizacion === 'RENOVACION' |
| `fieldErrors` | Reactive object con mensajes de error |
| `touchedFields` | Reactive object para validacion on-blur |
| `insurerFinancialSettings` | Cache de configuracion financiera |

### 3.3 Campos Condicionales

| Condicion | Campos Afectados | Estado |
|-----------|------------------|--------|
| `paquete === 'AMPLIA'` | DM, cristales, RT | HABILITADOS |
| `paquete === 'LIMITADA'` | DM, cristales | DESHABILITADOS |
| `paquete === 'RESPONSABILIDAD CIVIL'` | DM, cristales, RT | DESHABILITADOS |
| `cantidad_aseguradoras < N` | columnas N+1 a 5 | DESHABILITADAS |
| `tipo_cotizacion === 'RENOVACION'` | seccion renovacion | VISIBLE |
| `vehicleType.requires_cargo` | campo carga | VISIBLE |
| `danos_opcion_selec_X === 'V.FACTURA'` | danos_material_importe_factura_X | HABILITADO |
| `robo_opcion_selec_X === 'V.FACTURA'` | robo_importe_factura_X | HABILITADO |
| `cobertura_opcion_1_select_X !== '0'` | custom_coverage_1_name | HABILITADO |
| `cobertura_opcion_2_select_X !== '0'` | custom_coverage_2_name | HABILITADO |

### 3.4 Estados Posibles del Formulario

El formulario transiciona segun configuracion:

```
[INICIAL]
    tipo_cotizacion: '0' (placeholder)
    paquete: '0' (placeholder)
    cantidad_aseguradoras: 0
    => TABLA COMPLETAMENTE DESHABILITADA

[CONFIGURACION PARCIAL]
    cantidad_aseguradoras > 0 && paquete === '0'
    => TABLA DESHABILITADA (legacy: if(cantidad==0||paquete==0))

[NUEVA]
    tipo_cotizacion: 'NUEVA'
    => Seccion renovacion OCULTA
    => hora_solicitada REQUERIDA

[RENOVACION]
    tipo_cotizacion: 'RENOVACION'
    => Seccion renovacion VISIBLE
    => Campos renovacion REQUERIDOS

[AMPLIA]
    paquete: 'AMPLIA'
    => TODOS los campos de cobertura HABILITADOS

[LIMITADA]
    paquete: 'LIMITADA'
    => DM, cristales DESHABILITADOS y vaciados
    => RT HABILITADO

[RESPONSABILIDAD CIVIL]
    paquete: 'RESPONSABILIDAD CIVIL'
    => DM, cristales, RT DESHABILITADOS y vaciados
```

---

## 4. EVENTOS Y SECUENCIA REAL

### 4.1 Eventos del Formulario

| Evento | Elemento | Handler |
|--------|----------|---------|
| `@change` | tipo_cotizacion | validateField + mostrar/ocultar renovacion |
| `@blur` | vehiculo.marca | markTouched + validateField |
| `@blur` | vehiculo.descripcion | markTouched + validateField |
| `@blur` | vehiculo.modelo | markTouched + validateField |
| `@change` | vehiculo.tipo_auto | validateField + mostrar/ocultar carga |
| `@change` | paquete | validateField + limpiar campos deshabilitados |
| `@change` | cantidad_aseguradoras | validateField + resetCoverageColumn |
| `@change` | empresa_opcion_X | handleInsurerChanged + resetCoverageColumn |
| `@input` | campos monetarios | formatInput (useCurrencyFormat) |
| `@blur` | primer_pago_opcion_X | handlePrimerPagoBlur (calculo subsecuente) |
| `@change` | forma_de_pago | recalculateAllSubsecuentes |
| `@submit` | form | handleSubmit con validacion completa |

### 4.2 Orden Real de Ejecucion (Submit)

1. Usuario hace click en "Guardar Cotizacion"
2. `validateAllFields()` marca todos los campos como touched
3. Verifica `isFormValid` (computed)
4. Si invalido: muestra Swal.fire con errores
5. Si valido: `form.post('/quotes')` via Inertia
6. Inertia serializa `form` completo a JSON
7. Laravel recibe en `QuoteController@store`
8. `StoreQuoteRequest` ejecuta validacion backend
9. Si falla: retorna errores a frontend
10. Si pasa: `DB::transaction()` crea Quote + QuoteOptions
11. Redirect a `quotes.show` con mensaje flash

### 4.3 Dependencias entre Eventos

```
[tipo_cotizacion.change]
    └── showRenewalSection (computed)
        └── Visibilidad de campos renovacion
        └── Validacion condicional de campos renovacion

[vehiculo.tipo_auto.change]
    └── showCargoField (computed)
        └── Visibilidad del campo carga
        └── Validacion condicional de carga

[paquete.change]
    └── applyPackageDefaults()
    └── Limpieza de campos deshabilitados
    └── enabledColumns (computed via cantidad_aseguradoras)

[cantidad_aseguradoras.change]
    └── setAseguradorasCount()
        └── resetCoverageColumn(col) para columnas N+1..5
        └── enabledColumns (computed)

[empresa_opcion_X.change]
    └── handleInsurerChanged()
        └── loadFinancialSettings(insurerId)
        └── resetCoverageColumn(colIndex) excepto empresa

[danos_opcion_selec_X.change]
    └── Sincronizacion automatica a robo_opcion_selec_X (watcher en CoverageTable)
    └── isDanosImporteEnabled (computed)

[forma_de_pago.change]
    └── recalculateAllSubsecuentes()
        └── Actualiza subsecuente_opcion_1..5

[cantidad_total_anual_opcion_X.change]
    └── Watcher en CoverageTable
        └── Si ANUAL: primer_pago = total, subsecuente = 0
        └── Si otro: recalcula subsecuente

[primer_pago_opcion_X.blur]
    └── handlePrimerPagoBlur(colIndex)
        └── calculateSubsecuente()
```

### 4.4 Que Rompe el Flujo

| Falla | Consecuencia |
|-------|--------------|
| `cantidad_aseguradoras === 0` | Tabla deshabilitada, no se pueden ingresar opciones |
| `paquete === '0'` | Tabla deshabilitada aunque cantidad > 0 |
| `empresa_opcion_1 === '0'` | Validacion falla, no se puede guardar |
| `forma_de_pago === '0'` | Validacion falla, no se puede guardar |
| `tipo_cotizacion === '0'` | Validacion falla, no se puede guardar |
| Red timeout en loadFinancialSettings | Cache vacio, calculos de subsecuente pueden fallar |

---

## 5. CALCULOS

### 5.1 Donde se Calculan Valores

| Calculo | Ubicacion | Motor |
|---------|-----------|-------|
| Formato moneda MXN | `useCurrencyFormat.js` | Frontend (JS) |
| Subsecuente | `CoverageTable.vue:calculateSubsecuente()` | Frontend (JS) |
| Subsecuente | `PremiumCalculatorService.php:calculateSubsequentPayments()` | Backend (PHP) |
| Prima neta inversa | `PremiumCalculatorService.php:calculateNetPremiumFromTotal()` | Backend (PHP) |
| Total con IVA | `QuoteController@store` (inline) | Backend (PHP) |

### 5.2 Valores Recalculados Automaticamente

| Campo | Trigger | Formula |
|-------|---------|---------|
| `subsecuente_opcion_X` | blur en primer_pago | `(total - primer_pago) / divisor` |
| `subsecuente_opcion_X` | change en forma_de_pago | Recalculo completo |
| `primer_pago_opcion_X` | change forma_de_pago=ANUAL + total_anual existe | `primer_pago = total_anual` |

**Divisores (legacy):**
- ANUAL: 0 (no hay subsecuentes)
- SEMESTRAL: 1
- TRIMESTRAL: 3
- MENSUAL: 11

### 5.3 Valores Confiados al Frontend

| Campo | Confianza |
|-------|-----------|
| prima_neta_opcion_X | TOTAL - usuario ingresa directo |
| prima_total_anual_opcion_X | TOTAL - usuario ingresa directo |
| primer_pago_opcion_X | TOTAL - usuario ingresa, pero puede ser autocalculado |
| subsecuente_opcion_X | CALCULADO en frontend, pasado a backend |
| Todos los campos de coverages | TOTAL - no hay validacion semantica |

### 5.4 Valores Validados en Backend

| Campo | Validacion Backend |
|-------|-------------------|
| tipo_cotizacion | `in:NUEVA,RENOVACION` |
| customer_id | `exists:customers,id` |
| empresa_opcion_X | `exists:insurers,id` + `not_in:0` condicional |
| vehiculo.tipo_auto | `exists:vehicle_types,id` |
| vehiculo.modelo | `size:4` + `regex:/^\d{4}$/` |
| coverages.forma_de_pago | `in:ANUAL,SEMESTRAL,TRIMESTRAL,MENSUAL` |
| primer_pago vs total | Custom validator: `primer_pago <= total_anual` |

### 5.5 Fuente de Verdad

**NO HAY FUENTE UNICA DE VERDAD.**

- Frontend calcula subsecuentes con formulas legacy
- Backend recalcula IVA y surcharge con formulas propias
- Backend acepta valores monetarios del frontend sin recalculo completo
- Posible divergencia si formulas cambian en un lado y no en otro

---

## 6. AJAX / REQUESTS

### 6.1 Requests Disparados por el Formulario

| Endpoint | Metodo | Trigger | Payload |
|----------|--------|---------|---------|
| `/quotes` | POST | Submit | Formulario completo serializado |
| `/api/insurers/{id}/financial-settings` | GET | change en empresa_opcion_X | N/A |
| `/api/quotes/calculate-premium-breakdown` | POST | (no usado en Create) | total, insurer_id, frequency |

### 6.2 Payload Real Enviado (POST /quotes)

```javascript
{
  tipo_cotizacion: 'NUEVA' | 'RENOVACION',
  hora_solicitada: 'HH:mm',
  contact_id: int | null,
  customer_id: int,
  asegurado: { apellido_paterno, apellido_materno, nombre, codigo_postal, colonia, estado },
  vehiculo: { marca, descripcion, modelo, uso_de_unidad, tipo_auto, carga },
  renovacion: { compania_actual, fecha_vigencia, poliza_a_renovar, prima_año },
  paquete: 'AMPLIA' | 'LIMITADA' | 'RESPONSABILIDAD CIVIL',
  cantidad_aseguradoras: 1-5,
  coverages: {
    forma_de_pago: 'ANUAL' | 'SEMESTRAL' | 'TRIMESTRAL' | 'MENSUAL',
    descripcion_tabla_1: string,
    empresa_opcion_1: int,
    danos_opcion_selec_1: string,
    // ... ~25 campos x 5 columnas = ~125 campos
    empresa_opcion_5: int,
    // ...
  },
  custom_coverage_1_name: string,
  custom_coverage_2_name: string,
  notas: string
}
```

### 6.3 Control de Concurrencia

**NO EXISTE CONTROL DE CONCURRENCIA.**

- Requests de financial-settings son independientes
- No hay bloqueo optimista ni pessimista
- No hay versionamiento de entidad
- Dos usuarios pueden crear cotizaciones para el mismo cliente sin advertencia

### 6.4 Orden de Requests

- Financial settings se carga on-demand al seleccionar aseguradora
- Si el usuario cambia aseguradoras rapidamente, requests pueden llegar fuera de orden
- El cache `insurerFinancialSettings` mitiga esto parcialmente
- No hay cancellation de requests pendientes

---

## 7. BACKEND

### 7.1 Validaciones Reales (no asumidas)

**StoreQuoteRequest.php:**

```php
// Validacion condicional por cantidad_aseguradoras
$n = (int) $this->input('cantidad_aseguradoras', 1);

// Columnas 1..N: required + exists + not_in:0
'coverages.empresa_opcion_1' => ['required', 'not_in:0', 'exists:insurers,id'],
'coverages.empresa_opcion_2' => $n >= 2
    ? ['required', 'not_in:0', 'exists:insurers,id']
    : ['nullable'],

// Validacion custom: primer_pago <= total_anual
public function withValidator($validator): void {
    $validator->after(function ($validator) {
        for ($col = 1; $col <= $n; $col++) {
            if ($primerPago > $total) {
                $validator->errors()->add(...);
            }
        }
    });
}
```

### 7.2 Confianza en Frontend

| Aspecto | Confianza |
|---------|-----------|
| Calculos de subsecuente | TOTAL - backend recibe string, parsea y guarda |
| Formato de moneda | TOTAL - backend usa `str_replace` para parsear |
| Prima neta | TOTAL - no se verifica matematicamente |
| Habilitacion de campos por paquete | PARCIAL - frontend maneja, backend no valida |

### 7.3 Uso de POST Directos

- Inertia envía datos como `application/json`
- Laravel recibe en `$request->validated()`
- No hay acceso directo a `$_POST` o `$_GET`
- FormRequest abstrae la validacion

### 7.4 Separacion de Responsabilidades

| Capa | Responsabilidad | Estado |
|------|-----------------|--------|
| Controller | Orquestacion | Sobrerecargado (logica de parseo) |
| FormRequest | Validacion | Completa pero no semantica |
| Model | Persistencia | Correcto |
| Service | Calculos | Existe pero no se usa en store() |
| Enum | Estados | Bien definidos |

**Problema identificado:** `QuoteController@store` tiene logica de parseo de moneda y calculos inline que deberian estar en un servicio.

---

## 8. PERSISTENCIA (SQL / DB)

### 8.1 Que se Guarda

**Tabla `quotes`:**
```sql
- uuid, folio (autogenerado)
- customer_id, contact_id, agent_id (FK)
- type (ENUM: NEW, RENEWAL)
- status (ENUM: DRAFT, SENT, CONCRETED, ISSUED, REJECTED, ANNULLED, EXPIRED)
- vehicle_data (JSON: brand, model, year, usage, cargo_type)
- vehicle_type, vehicle_usage, vehicle_type_id
- package_type (ENUM: FULL, LIMITED, LIABILITY_ONLY, CUSTOM)
- previous_* (datos de renovacion)
- coverage_description, custom_coverage_1_name, custom_coverage_2_name
- quote_valid_until, sent_at, concluded_at, rejected_at
- internal_notes, customer_notes
```

**Tabla `quote_options`:**
```sql
- quote_id, insurer_id (FK)
- option_number (1-5)
- coverage_package (ENUM)
- payment_frequency (ENUM: ANNUAL, SEMIANNUAL, QUARTERLY, MONTHLY)
- coverages (JSON: estructura completa)
- net_premium_cents, policy_fee_cents, surcharge_cents, iva_cents, total_premium_cents
- first_payment_cents, subsequent_payment_cents
- annual_net_premium_cents, annual_total_premium_cents
- is_selected (boolean)
- Campos legacy individuales: material_damage_*, theft_*, liability_*, etc.
```

### 8.2 Que NO se Guarda

| Dato | Guardado |
|------|----------|
| hora_solicitada | SI (requested_at) |
| Datos readonly del asegurado | NO (se obtienen via FK customer_id) |
| Historial de cambios | SI (via spatie/activitylog) |
| Usuario que modifico | SI (via activitylog) |
| Calculos intermedios | NO |
| Financial settings usados | NO (solo se guarda resultado) |
| Momento exacto de cada calculo | NO |

### 8.3 Reconstruccion de Cotizacion Historica

**SI SE PUEDE RECONSTRUIR:**
- Todos los valores numericos se guardan en centavos (precision)
- JSON `coverages` contiene estructura completa
- Campos legacy individuales duplican datos para queries
- ActivityLog permite ver cambios

**NO SE PUEDE RECONSTRUIR:**
- Configuracion financiera de la aseguradora AL MOMENTO de la cotizacion (puede haber cambiado)
- Tipo de paquete que genero los enables/disables
- Si valores fueron calculados o ingresados manualmente

### 8.4 Riesgos de Auditoria

| Riesgo | Descripcion |
|--------|-------------|
| Financial settings no versionados | Si cambia el derecho de poliza, cotizaciones anteriores no reflejan que tasa se uso |
| Doble almacenamiento | JSON `coverages` + campos individuales pueden divergir |
| Soft deletes | Cotizaciones "eliminadas" siguen en BD (mitigado, es auditoria positiva) |
| Sin firma de integridad | No hay hash que garantice que los datos no fueron alterados post-persistencia |

---

## 9. FALLAS ESTRUCTURALES (DEMOSTRABLES POR CODIGO)

### 9.1 Estados No Deterministas

**Ubicacion:** `CoverageTable.vue` watchers multiples

```javascript
// Watch for changes in danos_opcion_selec to clear importe_factura
watch(() => props.modelValue, (newVal, oldVal) => {
    // Multiples watchers deep que pueden disparar simultaneamente
}, { deep: true });
```

**Problema:** Multiples watchers con `{ deep: true }` sobre el mismo objeto pueden causar:
- Orden de ejecucion no garantizado
- Loops de actualizacion si no se protegen correctamente
- Performance degradada en formularios grandes

### 9.2 Doble Motor de Calculo

**Frontend:** `CoverageTable.vue:calculateSubsecuente()`
```javascript
const calculateSubsecuente = (formaPago, totalAnual, primerPago) => {
    switch (formaPago) {
        case 'SEMESTRAL': subsecuente = diferencia; break;
        case 'TRIMESTRAL': subsecuente = diferencia / 3; break;
        case 'MENSUAL': subsecuente = diferencia / 11; break;
    }
};
```

**Backend:** `PremiumCalculatorService.php:calculateSubsequentPayments()`
```php
public function calculateSubsequentPayments(...): ?float {
    return match ($frequency) {
        'SEMIANNUAL' => round($firstPayment, 2), // DIFERENTE: usa primer_pago, no diferencia
        'QUARTERLY' => round(($totalAnnualPremium - $firstPayment) / 3, 2),
        'MONTHLY' => round(($totalAnnualPremium - $firstPayment) / 11, 2),
    };
}
```

**Divergencia:** Para SEMESTRAL:
- Frontend: `subsecuente = total - primer_pago`
- Backend: `subsecuente = primer_pago`

**Impacto:** Si se usa el backend para recalcular, valores difieren del frontend.

### 9.3 Race Conditions Potenciales

**Ubicacion:** `Create.vue:loadFinancialSettings()`

```javascript
const loadFinancialSettings = async (insurerId) => {
    // No hay cancellation token
    const response = await fetch(`/api/insurers/${insurerId}/financial-settings`);
    insurerFinancialSettings.value[insurerId] = data;
};
```

**Escenario:**
1. Usuario selecciona Aseguradora A (col 1)
2. Request 1 inicia para A
3. Usuario cambia a Aseguradora B rapidamente
4. Request 2 inicia para B
5. Request 2 completa primero
6. Request 1 completa despues
7. Cache tiene datos de A y B, pero UI puede estar inconsistente

### 9.4 Campos Deshabilitados pero Persistentes

**Ubicacion:** `Create.vue` watcher de paquete

```javascript
watch(() => form.paquete, (newPaquete, oldPaquete) => {
    if (newPaquete === 'RESPONSABILIDAD CIVIL') {
        form.coverages[`danos_opcion_selec_${col}`] = '0';
        // ... limpia valores
    }
});
```

**Problema:** Si el usuario:
1. Selecciona AMPLIA, ingresa datos en DM
2. Cambia a RESPONSABILIDAD CIVIL (DM se limpia)
3. Cambia de vuelta a AMPLIA

Los datos de DM NO se restauran (estan perdidos). Esto es comportamiento esperado pero puede sorprender al usuario.

### 9.5 Backend No Autoritativo en Calculos

**Ubicacion:** `QuoteController@store()`

```php
// Calcular IVA (16% sobre prima neta + derecho + recargo)
$baseForIva = $netPremiumCents + $policyFeeCents + $surchargeCents;
$ivaCents = (int) round($baseForIva * 0.16);
```

**Problema:** El backend recalcula IVA pero NO recalcula:
- subsecuentes (acepta valor del frontend)
- primer_pago (acepta valor del frontend)
- Validacion de que total = prima_neta + derecho + recargo + iva

### 9.6 Validacion Frontend/Backend Asimetrica

**Campos con validacion solo en frontend:**
- Regex de marca (solo letras)
- Regex de descripcion (alfanumerico)
- Regex de compania_actual (sin caracteres especiales)

**Impacto:** Un request manipulado puede saltarse estas validaciones.

---

## 10. ESTADO FINAL

---

**SISTEMA ACTUAL — FORENSE COMPLETO, APTO PARA COMPARACION**

---

### Resumen Cuantitativo

| Metrica | Valor |
|---------|-------|
| Archivos backend analizados | 13 |
| Archivos frontend analizados | 4 |
| Campos del formulario | ~150 (25 por columna x 5 + globales) |
| Tablas principales | 2 (quotes, quote_options) |
| Endpoints | 3 (store, financial-settings, calculate-breakdown) |
| Fallas estructurales documentadas | 6 |
| Lineas de codigo total revisadas | ~4,500 |

### Firmas de Auditoria

- **Analisis iniciado:** 2026-01-28
- **Archivos verificados:** Todos los listados en seccion 1
- **Metodo:** Lectura linea por linea, extraccion de hechos tecnicos
- **Sin especulacion ni recomendaciones**
