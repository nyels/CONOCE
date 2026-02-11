# Comparativa de Flujos de Trabajo: Sistema Legacy vs Sistema Actual

> **NOTA IMPORTANTE**: Este documento presenta los datos EXACTOS del sistema legacy sin reinterpretación.
> Los campos y valores son transcripciones literales del código fuente.

---

## 1. FLUJO GENERAL DE CAPTURA

### Sistema Legacy (cotizacion_autos.txt)
```
┌─────────────────────────────────────────────────────────────────┐
│           COTIZACION DE SEGURO DE AUTOMOVILES                   │
│                    (Formulario único)                           │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│ 1. TIPO DE COTIZACION                                           │
│    - NUEVA                                                      │
│    - RENOVACION                                                 │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│ 2. HORA SOLICITADA (campo type="time")                          │
│    → Registra la hora en que el cliente solicita la cotización  │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│ 3. CONTACTOS (selector de base de datos existente)              │
│    → Para saber QUIÉN atendió/gestionó la cotización            │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│ 4. PROSPECTOS/ASEGURADO (selector de base de datos)             │
│    → Muestra datos del asegurado (readonly):                    │
│    - Apellido Paterno                                           │
│    - Apellido Materno                                           │
│    - Nombre                                                     │
│    - C.P.                                                       │
│    - Colonia                                                    │
│    - Estado                                                     │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│ 5. DESCRIPCION DEL VEHICULO                                     │
│    - MARCA (input texto)                                        │
│    - DESCRIPCION (input texto - versión/línea)                  │
│    - MODELO (año, maxlength=4)                                  │
│    - USO DE LA UNIDAD (input texto)                             │
│    - TIPO AUTO: [AUTO | MOTO | PICK UP | CAMION]                │
│    - DESCRIPCION DE LA CARGA (solo si TIPO=CAMION):             │
│      [A NO PELIGROSA | B PELIGROSA | C MUY PELIGROSA]           │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼ (Solo si RENOVACION)
┌─────────────────────────────────────────────────────────────────┐
│ 6. INFORMACION POLIZA A RENOVAR                                 │
│    - COMPAÑIA ACTUAL                                            │
│    - FIN DE VIGENCIA                                            │
│    - POLIZA A RENOVAR (número)                                  │
│    - PRIMA DEL AÑO ANTERIOR ($)                                 │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│ 7. ASEGURADORAS                                                 │
│    - PAQUETE SOLICITADO: [AMPLIA | LIMITADA | RESPONSABILIDAD   │
│      CIVIL]                                                     │
│    - CANTIDAD DE ASEGURADORAS: [1 | 2 | 3 | 4 | 5]              │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│ 8. TABLA COMPARATIVA (5 columnas máximo)                        │
│    ┌──────────────────┬─────────┬─────────┬─────────┬─────────┐ │
│    │   ASEGURADORAS   │ Opción1 │ Opción2 │ Opción3 │ Opción4 │ │
│    ├──────────────────┼─────────┼─────────┼─────────┼─────────┤ │
│    │ Aseguradora      │ AXA     │ BANORTE │ BX+     │ CHUBB   │ │
│    │                  │ GNP     │ HDI     │QUALITAS │         │ │
│    ├──────────────────┴─────────┴─────────┴─────────┴─────────┤ │
│    │            DESGLOSE DE COBERTURAS                        │ │
│    ├──────────────────┬─────────┬─────────┬─────────┬─────────┤ │
│    │ DAÑOS MATERIALES │V.COMERC │V.CONVEN │V.FACTUR │         │ │
│    │ IMPORTE FACTURA  │   $     │   $     │   $     │         │ │
│    │ DEDUCIBLE DM     │0%|3%|5%|10%|15%|20%                   │ │
│    │ CRISTALES        │AMPARADA │         │         │         │ │
│    │ ROBO TOTAL       │V.COMERC │V.CONVEN │V.FACTUR │         │ │
│    │ IMPORTE FACTURA  │   $     │   $     │   $     │         │ │
│    │ DEDUCIBLE RT     │0%|3%|5%|10%|15%|20%                   │ │
│    │ RC PERSONAS      │   $     │   $     │   $     │         │ │
│    │ RC BIENES        │   $     │   $     │   $     │         │ │
│    │ GASTOS MÉDICOS   │   $     │   $     │   $     │         │ │
│    │ ASIST.VIAL       │AMPARADA │         │         │         │ │
│    │ ASIST.LEGAL      │AMPARADA │         │         │         │ │
│    │ EQUIPO ESPECIAL  │   $     │   $     │   $     │         │ │
│    │ ADAPT/CONVER     │   $     │   $     │   $     │         │ │
│    │ AUTO SUSTITUTO   │Días     │         │         │         │ │
│    │ MUERTE ACCID.    │   $     │   $     │   $     │         │ │
│    │ EXTEN.RC USA/CAN │ SÍ/NO   │         │         │         │ │
│    ├──────────────────┴─────────┴─────────┴─────────┴─────────┤ │
│    │            DESGLOSE DE COSTOS                            │ │
│    ├──────────────────┬─────────┬─────────┬─────────┬─────────┤ │
│    │ PRIMA NETA       │   $     │   $     │   $     │         │ │
│    │ DERECHO PÓLIZA   │   $     │   $     │   $     │         │ │
│    │ SUB TOTAL        │   $     │   $     │   $     │         │ │
│    │ IVA              │   $     │   $     │   $     │         │ │
│    │ TOTAL            │   $     │   $     │   $     │         │ │
│    │ FORMA PAGO       │ANUAL|SEMESTRAL|TRIMESTRAL|MENSUAL    │ │
│    │ MONTO PAGO       │   $     │   $     │   $     │         │ │
│    └──────────────────┴─────────┴─────────┴─────────┴─────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

---

### Sistema Actual (Quotes/Create.vue)
```
┌─────────────────────────────────────────────────────────────────┐
│                    WIZARD DE 5 PASOS                            │
│  [1.Cliente] → [2.Vehículo] → [3.Coberturas] → [4.Opciones] → [5.Resumen]
└─────────────────────────────────────────────────────────────────┘

PASO 1: CLIENTE
┌─────────────────────────────────────────────────────────────────┐
│ - Buscar cliente existente (search)                             │
│ - O crear nuevo:                                                │
│   - Nombre completo                                             │
│   - Teléfono (mask: phone)                                      │
│   - Email                                                       │
│   - RFC (mask: rfc)                                             │
└─────────────────────────────────────────────────────────────────┘

PASO 2: VEHICULO
┌─────────────────────────────────────────────────────────────────┐
│ - Tipo: [Nueva | Renovación]                                    │
│ - Marca (select catálogo)                                       │
│ - Modelo/Línea (input)                                          │
│ - Año (select)                                                  │
│ - Valor factura ($)                                             │
│                                                                 │
│ (Si Renovación - campos adicionales en form.renewal)            │
│ - Aseguradora anterior                                          │
│ - Número de póliza                                              │
│ - Prima anterior                                                │
│ - Fecha vencimiento                                             │
└─────────────────────────────────────────────────────────────────┘

PASO 3: COBERTURAS (Paquete)
┌─────────────────────────────────────────────────────────────────┐
│ Selección visual de tarjetas:                                   │
│ [📦 Básico]  [⭐ Amplio]  [💎 Premium]                          │
│                                                                 │
│ - Básico: (sin coberturas detalladas)                           │
│ - Amplio: ✓ Robo total, ✓ Daños materiales                      │
│ - Premium: + Auto sustituto, + 0 deducible                      │
└─────────────────────────────────────────────────────────────────┘

PASO 4: OPCIONES (Captura manual)
┌─────────────────────────────────────────────────────────────────┐
│ - Aseguradora (select catálogo)                                 │
│ - Paquete/Cobertura: [Amplia | Limitada | Responsabilidad Civil]│
│ - Forma de pago: [Anual | Semestral | Trimestral | Mensual]     │
│ - Prima Neta ($)                                                │
│ - Derecho de Póliza ($)                                         │
│ - IVA (calculado automático 16%)                                │
│ - Total (calculado)                                             │
│                                                                 │
│ [Agregar a Comparativa]                                         │
│ Tabla de opciones agregadas con botón eliminar                  │
└─────────────────────────────────────────────────────────────────┘

PASO 5: RESUMEN
┌─────────────────────────────────────────────────────────────────┐
│ - Datos cliente                                                 │
│ - Datos vehículo                                                │
│ - Opciones seleccionadas                                        │
│ [Vista Previa PDF] [Finalizar Cotización]                       │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. COMPARACIÓN CAMPO A CAMPO

| # | Campo Legacy | ID/Name Legacy | Sistema Actual | Estado |
|---|--------------|----------------|----------------|--------|
| 1 | TIPO COTIZACION | `tipo_cotizacion` | `form.quote_type` | ✅ EXISTE |
| 2 | HORA SOLICITADA | `hora_solicitada` | **NO EXISTE** | ❌ FALTA |
| 3 | CONTACTOS | `contactos` | **NO EXISTE** | ❌ FALTA |
| 4 | PROSPECTOS/ASEGURADO | `prospectos_asegurados` | `form.customer_id` / `selectedCustomer` | ✅ EXISTE |
| 5 | Apellido Paterno | `apellido_paterno` | Solo `customer.name` (completo) | ⚠️ DIFERENTE |
| 6 | Apellido Materno | `apellido_materno` | Solo `customer.name` (completo) | ⚠️ DIFERENTE |
| 7 | Nombre | `nombre_asegurado` | Solo `customer.name` (completo) | ⚠️ DIFERENTE |
| 8 | C.P. | `codigo_postal` | `customer.zip_code` | ✅ EXISTE |
| 9 | Colonia | `colonia` | **NO EXISTE en customer** | ❌ FALTA |
| 10 | Estado | `estado` | `customer.state` | ✅ EXISTE |
| 11 | MARCA | `marca` | `form.vehicle.brand` | ✅ EXISTE |
| 12 | DESCRIPCION | `descripcion` | `form.vehicle.model` | ⚠️ RENOMBRADO |
| 13 | MODELO (año) | `modelo` | `form.vehicle.year` | ⚠️ RENOMBRADO |
| 14 | USO DE LA UNIDAD | `uso_de_unidad` | `form.vehicle.usage` | ⚠️ SIMPLIFICADO (solo 'personal') |
| 15 | TIPO AUTO | `tipo_auto` | **NO EXISTE** | ❌ FALTA |
| 16 | DESC. CARGA (camión) | `carga` | `CargoDescription` enum en PHP | ⚠️ BACKEND ONLY |
| 17 | COMPAÑIA ACTUAL | `compañia_actual` | `form.renewal.insurer` | ✅ EXISTE |
| 18 | FIN DE VIGENCIA | `fecha_vigencia` | `form.renewal.expires_at` | ✅ EXISTE |
| 19 | POLIZA A RENOVAR | `poliza_a_renovar` | `form.renewal.policy_number` | ✅ EXISTE |
| 20 | PRIMA AÑO ANTERIOR | `prima_año` | `form.renewal.previous_premium` | ✅ EXISTE |
| 21 | PAQUETE | `paquete` | `form.coverage_package` | ⚠️ VALORES DIFERENTES |
| 22 | CANT. ASEGURADORAS | `cantidad_aseguradoras` | Implícito en `manualOptions.length` | ⚠️ DIFERENTE |

---

## 3. ANÁLISIS DE PAQUETES/COBERTURAS

### Sistema Legacy (Valores EXACTOS del código):
```
PAQUETE SOLICITADO O CONTRATADO:
├── AMPLIA
├── LIMITADA
└── RESPONSABILIDAD CIVIL
```

### Sistema Actual (Valores en el código):
```
coverage_package (Paso 3 visual):
├── basic    → "Básico" (📦)
├── standard → "Amplio" (⭐)  [NOTA: El nombre dice "Amplio" pero el valor es "standard"]
└── premium  → "Premium" (💎)

newOption.coverage_package (Paso 4 captura):
├── full          → "Cobertura Amplia"
├── limited       → "Cobertura Limitada"
└── liability_only → "Responsabilidad Civil"
```

### INCONSISTENCIA DETECTADA:
- El Paso 3 usa: `basic`, `standard`, `premium`
- El Paso 4 usa: `full`, `limited`, `liability_only`
- El Legacy usa: `AMPLIA`, `LIMITADA`, `RESPONSABILIDAD CIVIL`

---

## 4. DESGLOSE DE COBERTURAS - COMPARACIÓN DETALLADA

### Sistema Legacy (Tabla completa de coberturas):

| Cobertura | Tipo de Valor | Opciones Legacy |
|-----------|---------------|-----------------|
| DAÑOS MATERIALES | Select | V.COMERCIAL, V.CONVENIDO, V.FACTURA |
| IMPORTE FACTURA (DM) | $ Input | Monto en pesos |
| DEDUCIBLE DM | Select % | 0%, 3%, 5%, 10%, 15%, 20% |
| CRISTALES | Select | AMPARADA |
| ROBO TOTAL | Select | V.COMERCIAL, V.CONVENIDO, V.FACTURA |
| IMPORTE FACTURA (RT) | $ Input | Monto en pesos |
| DEDUCIBLE RT | Select % | 0%, 3%, 5%, 10%, 15%, 20% |
| RC PERSONAS | $ Input | Monto en pesos |
| RC BIENES | $ Input | Monto en pesos |
| GASTOS MÉDICOS | $ Input | Monto en pesos |
| ASISTENCIA VIAL | Select | AMPARADA |
| ASISTENCIA LEGAL | Select | AMPARADA |
| EQUIPO ESPECIAL | $ Input | Monto en pesos |
| ADAPT/CONVERSIONES | $ Input | Monto en pesos |
| AUTO SUSTITUTO | Input | Días |
| MUERTE ACCIDENTAL | $ Input | Monto en pesos |
| EXTENSION RC USA/CAN | Select | SÍ / NO |

### Sistema Actual:
**NO EXISTE** captura detallada de coberturas. Solo se captura:
- Prima Neta
- Derecho de Póliza
- IVA (calculado)
- Total

---

## 5. TIPO DE AUTO (LEGACY)

### Sistema Legacy:
```html
<select name="tipo_auto" id="tipo_auto">
    <option value="0">SELECCIONA TIPO DE AUTO</option>
    <option value="AUTO">AUTO</option>
    <option value="MOTO">MOTO</option>
    <option value="PICK UP">PICK UP</option>
    <option value="CAMION">CAMION</option>
</select>
```

**Comportamiento condicional**: Si `tipo_auto === "CAMION"`, se muestra el campo de DESCRIPCION DE LA CARGA.

### Sistema Actual:
- **NO EXISTE** selector de tipo de auto
- Existe el enum `CargoDescription` en backend pero no hay UI para seleccionarlo
- No hay lógica condicional para camiones

---

## 6. ASEGURADORAS (LEGACY)

### Sistema Legacy - Aseguradoras disponibles:
```
AXA
BANORTE
BX+
CHUBB
GNP
HDI SEGUROS
QUALITAS
```

### Sistema Actual:
- Las aseguradoras vienen de `props.insurers` (base de datos)
- No hay lista fija hardcodeada
- Más flexible pero depende de catálogo poblado

---

## 7. CAMPOS FALTANTES EN SISTEMA ACTUAL

### Campos críticos NO implementados:

| Campo | ID Legacy | Propósito de Negocio | Prioridad |
|-------|-----------|----------------------|-----------|
| **HORA SOLICITADA** | `hora_solicitada` | Registrar cuándo el cliente pidió la cotización (para métricas y seguimiento) | ALTA |
| **CONTACTOS** | `contactos` | Identificar quién gestionó/atendió la solicitud | ALTA |
| **TIPO AUTO** | `tipo_auto` | Clasificar vehículo para cálculo de prima (AUTO/MOTO/PICK UP/CAMION) | ALTA |
| **USO DE UNIDAD** | `uso_de_unidad` | Uso específico del vehículo (no solo personal) | MEDIA |
| **COLONIA** | `colonia` | Dato de ubicación del asegurado | BAJA |
| **DESGLOSE COBERTURAS** | 17 campos | Detalle completo de cada cobertura por aseguradora | ALTA |

### Campos de cobertura faltantes (17 en total):

1. `daños_opcion[N]_selec` - Tipo de daños materiales
2. `daños_material_importe_factura_[N]` - Importe factura DM
3. `deducible_opcion[N]` - Deducible DM %
4. `cristales_opcion[N]_selec` - Cobertura cristales
5. `robo_opcion[N]_selec` - Tipo robo total
6. `robo_importe_factura_[N]` - Importe factura RT
7. `robo_deducible_opcion[N]` - Deducible RT %
8. `rc_personas_opcion[N]` - RC Personas $
9. `rc_bienes_opcion[N]` - RC Bienes $
10. `gastos_medicos_opcion[N]` - Gastos médicos $
11. `asist_vial_opcion[N]` - Asistencia vial
12. `asist_legal_opcion[N]` - Asistencia legal
13. `equipo_especial_opcion[N]` - Equipo especial $
14. `adapt_conver_opcion[N]` - Adaptaciones $
15. `auto_sustituto_opcion[N]` - Auto sustituto (días)
16. `muerte_accidental_opcion[N]` - Muerte accidental $
17. `extension_rc_usa_can_opcion[N]` - Extensión RC USA/CAN

---

## 8. FLUJO DE TRABAJO VISUAL

### Legacy: Formulario Único Vertical
```
┌────────────────────────────────────────┐
│     FORMULARIO COTIZACIÓN              │
│ ┌────────────────────────────────────┐ │
│ │ Tipo Cotización   [▼ NUEVA     ]   │ │
│ │ Hora Solicitada   [  10:30      ]   │ │
│ │ Contacto          [▼ Seleccionar]   │ │
│ │ Prospecto         [▼ Seleccionar]   │ │
│ │ ─────────────────────────────────── │ │
│ │ DATOS ASEGURADO (readonly)          │ │
│ │ ─────────────────────────────────── │ │
│ │ DESCRIPCION VEHICULO                │ │
│ │ Marca:           [              ]   │ │
│ │ Descripción:     [              ]   │ │
│ │ Modelo (año):    [    ]             │ │
│ │ Uso de unidad:   [              ]   │ │
│ │ Tipo auto:       [▼ AUTO       ]   │ │
│ │ ─────────────────────────────────── │ │
│ │ (Si renovación)                     │ │
│ │ Compañía actual: [              ]   │ │
│ │ Fin vigencia:    [  /  /       ]   │ │
│ │ Póliza:          [              ]   │ │
│ │ Prima anterior:  [$             ]   │ │
│ │ ─────────────────────────────────── │ │
│ │ ASEGURADORAS                        │ │
│ │ Paquete:         [▼ AMPLIA     ]   │ │
│ │ Cantidad:        [▼ 3          ]   │ │
│ │ ─────────────────────────────────── │ │
│ │ TABLA COMPARATIVA DE 5 COLUMNAS     │ │
│ │ [Detalle completo de coberturas]    │ │
│ │ [Prima, Derechos, IVA, Total]       │ │
│ │ [Forma de pago, Monto]              │ │
│ └────────────────────────────────────┘ │
│          [GUARDAR COTIZACIÓN]          │
└────────────────────────────────────────┘
```

### Actual: Wizard Multi-Paso
```
┌─────────────────────────────────────────┐
│ [1]──[2]──[3]──[4]──[5]  Paso 1 de 5    │
│ ┌─────────────────────────────────────┐ │
│ │    PASO 1: CLIENTE                  │ │
│ │ ┌───────────────────────────────┐   │ │
│ │ │ [Buscar cliente...]           │   │ │
│ │ └───────────────────────────────┘   │ │
│ │ Clientes encontrados:               │ │
│ │ ┌─────────────────────┐             │ │
│ │ │ Juan Pérez | 999... │             │ │
│ │ └─────────────────────┘             │ │
│ │ ─── O ───                           │ │
│ │ [+ Crear Nuevo Cliente]             │ │
│ │ (Formulario expandible)             │ │
│ └─────────────────────────────────────┘ │
│     [← Anterior]    [Siguiente →]       │
└─────────────────────────────────────────┘
```

---

## 9. CONCLUSIONES OBJETIVAS

### Funcionalidad Preservada:
- Tipo de cotización (nueva/renovación)
- Selección/creación de cliente
- Datos básicos del vehículo
- Datos de póliza a renovar
- Captura de opciones de aseguradoras
- Cálculo de totales

### Funcionalidad Perdida:
1. **Hora solicitada** - Necesaria para métricas de servicio
2. **Selector de contactos** - Necesario para saber quién atendió
3. **Tipo de auto** - Necesario para clasificación de vehículos especiales (motos, camiones)
4. **Descripción de carga** - Solo backend, sin UI para camiones
5. **Desglose completo de coberturas** - 17 campos por opción de aseguradora
6. **Uso de unidad detallado** - Input libre vs valor fijo 'personal'

### Mejoras del Sistema Actual:
- UX más moderna con wizard paso a paso
- Validaciones en tiempo real (masks)
- Cálculo automático de IVA
- Vista previa de PDF
- Búsqueda de clientes con debounce

### Regresiones del Sistema Actual:
- Pérdida de granularidad en coberturas
- No se captura quién atendió la solicitud
- No se registra hora de solicitud
- No hay clasificación de tipo de vehículo
- Inconsistencia en valores de paquetes entre pasos

---

## 10. RECOMENDACIONES (Sin implementar)

Para igualar funcionalidad legacy:
1. Agregar campo `hora_solicitada` (datetime)
2. Agregar selector de `contacto_id` (relación con contactos)
3. Agregar selector de `tipo_auto` (AUTO/MOTO/PICK UP/CAMION)
4. Mostrar campo de carga condicionalmente para CAMION
5. Unificar valores de paquetes entre Paso 3 y Paso 4
6. Evaluar si se necesita desglose completo de coberturas o mantener captura simplificada

---

*Documento generado: 2026-01-27*
*Fuente Legacy: public/sistema viejo/cotizacion_autos.txt*
*Fuente Actual: resources/js/Pages/Quotes/Create.vue*
