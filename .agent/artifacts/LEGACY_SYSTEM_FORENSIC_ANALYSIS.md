# ANALISIS FORENSE DEL SISTEMA LEGACY
## Cotizador de Autos - Refactorizacion Completa

---

## 1. ESTRUCTURA DE BASE DE DATOS LEGACY

### 1.1 Tabla: `cotizacion_autos` (Cotizacion Principal)
```sql
CREATE TABLE `cotizacion_autos` (
  `Id_contizacion_autos` int(255) NOT NULL AUTO_INCREMENT,
  `tipo_cotizacion` varchar(100) NOT NULL,           -- NUEVA o RENOVACION
  `hora_solicitada` varchar(200) NOT NULL,
  `Id_contacto_tabla_autos` varchar(255) NOT NULL,   -- FK contactos
  `Id_prospecto_tabla_autos` varchar(255) NOT NULL,  -- FK prospectos
  `marca_auto` varchar(500) NOT NULL,
  `descripcion_auto` varchar(1000) NOT NULL,
  `modelo_auto` varchar(255) NOT NULL,               -- Ano del vehiculo
  `uso_de_unidad_auto` varchar(500) NOT NULL,
  `tipo_auto` varchar(100) NOT NULL,                 -- Tipo vehiculo (AUTO, CAMION, MOTO)
  `compania_autos` varchar(200) NOT NULL,            -- Aseguradora actual (renovacion)
  `fecha_vigencia_autos` varchar(100) NOT NULL,      -- Fecha vencimiento (renovacion)
  `poliza_renovar_autos` varchar(100) NOT NULL,      -- Numero poliza anterior
  `prima_ano_autos` varchar(100) NOT NULL,           -- Prima ano anterior
  `tipo_de_carga` varchar(200) NOT NULL,             -- Descripcion carga
  `paquete_solicitado` varchar(100) NOT NULL,        -- AMPLIA, LIMITADA, RC
  `cantidad_aseguradoras` varchar(100) NOT NULL,     -- 1-5
  `estatus_concretar_cotizacion` int(10) NOT NULL,   -- 0=COTIZADA, 1=CONCRETADA, 3=RECHAZADA
  `opcion_concreto` varchar(100) NOT NULL,           -- Opcion elegida
  `numero_poliza` varchar(200) NOT NULL,             -- Numero poliza emitida
  `motivos_no_concretacion` varchar(500) NOT NULL,
  `fecha_alta_auto` varchar(100) NOT NULL,
  `dio_alta_auto` varchar(100) NOT NULL,             -- Usuario que creo
  PRIMARY KEY (`Id_contizacion_autos`)
);
```

### 1.2 Tabla: `opciones_cotizaciones` (Opciones por Aseguradora)
```sql
CREATE TABLE `opciones_cotizaciones` (
  `id_opciones` int(255) NOT NULL AUTO_INCREMENT,
  `Id_tabla_contizacion_autos_opciones` int(255) NOT NULL,  -- FK a cotizacion_autos
  `aseguradora_opciones` varchar(100) NOT NULL,

  -- COBERTURAS BASICAS
  `danos_materiales_opciones` varchar(100) NOT NULL,        -- AMPARADO/NO AMPARADO/VALOR FACTURA
  `importe_factura_danos_materiales_opciones` varchar(200) NOT NULL,
  `deducible_dm_opciones` varchar(100) NOT NULL,            -- % o monto
  `cristales_opciones` varchar(100) NOT NULL,               -- AMPARADO/NO AMPARADO/10%
  `robo_total_opciones` varchar(200) NOT NULL,              -- AMPARADO/NO AMPARADO/VALOR FACTURA
  `importe_factura_robo_total_opciones` varchar(200) NOT NULL,
  `deducible_rt_opciones` varchar(200) NOT NULL,

  -- RESPONSABILIDAD CIVIL
  `rc_danos_a_terceros_opciones` varchar(200) NOT NULL,     -- Monto
  `deducible_de_rc_opciones` varchar(200) NOT NULL,
  `rc_fallecimiento_opciones` varchar(200) NOT NULL,        -- Monto

  -- COBERTURAS ADICIONALES
  `gastos_medicos_opciones` varchar(200) NOT NULL,          -- Monto
  `accidentes_conductor_opciones` varchar(200) NOT NULL,    -- Monto
  `proteccion_legal_opciones` varchar(200) NOT NULL,        -- AMPARADO/NO AMPARADO
  `asistencia_legal_opciones` varchar(200) NOT NULL,        -- AMPARADO/NO AMPARADO
  `danos_por_la_carga_opciones` varchar(200) NOT NULL,      -- AMPARADO/NO AMPARADO
  `adaptaciones_opciones` varchar(200) NOT NULL,            -- Monto
  `extension_rc_opciones` varchar(200) NOT NULL,            -- AMPARADO/NO AMPARADO

  -- COBERTURAS PERSONALIZADAS
  `opcion1_nombre_opciones` varchar(200) NOT NULL,          -- Nombre cobertura adicional 1
  `opcion1_valor_opciones` varchar(200) NOT NULL,           -- Valor/tipo cobertura 1
  `opcion2_nombre_opciones` varchar(200) NOT NULL,          -- Nombre cobertura adicional 2
  `opcion2_valor_opciones` varchar(200) NOT NULL,           -- Valor/tipo cobertura 2

  -- PRIMAS Y PAGOS
  `forma_de_pago_opciones` varchar(200) NOT NULL,           -- ANUAL/SEMESTRAL/TRIMESTRAL/MENSUAL
  `prima_neta_anual_opciones` varchar(200) NOT NULL,
  `prima_total_anual_opciones` varchar(200) NOT NULL,
  `primer_pago_opciones` varchar(200) NOT NULL,
  `subsecuentes_opciones` varchar(200) NOT NULL,
  `derecho_pago_opciones` varchar(200) NOT NULL,
  `recargo_por_cargo_opciones` varchar(200) NOT NULL,

  PRIMARY KEY (`id_opciones`)
);
```

### 1.3 Tabla: `costo_derecho_poliza` (Derecho de Poliza por Aseguradora)
```sql
CREATE TABLE `costo_derecho_poliza` (
  `id_derecho_poliza` int(255) NOT NULL AUTO_INCREMENT,
  `id_tabla_aseguradora` varchar(100) NOT NULL,             -- FK aseguradoras
  `derecho_costo` varchar(100) NOT NULL,                    -- Monto fijo
  PRIMARY KEY (`id_derecho_poliza`)
);
```

### 1.4 Tabla: `recargo_por_cargo_fraccionado` (Recargos por Fraccionamiento)
```sql
CREATE TABLE `recargo_por_cargo_fraccionado` (
  `id_recargo` int(255) NOT NULL AUTO_INCREMENT,
  `id_tabla_aseguradoras_recargo` varchar(200) NOT NULL,    -- FK aseguradoras
  `forma_de_pago` varchar(200) NOT NULL,                    -- SEMESTRAL/TRIMESTRAL/MENSUAL
  `cantidad_recargo` varchar(200) NOT NULL,                 -- Porcentaje (ej: 5, 8, 12)
  PRIMARY KEY (`id_recargo`)
);
```

---

## 2. FLUJO LEGACY COMPLETO

### 2.1 Flujo de Carga Inicial (inicio.js -> metodos)
```
1. Al cargar pagina:
   - $.ajax -> alta_contactos_metodos.php?contactos_lista=2 -> SELECT contactos -> <select>
   - $.ajax -> prospectos_metodos.php?prospectos_lista=3 -> SELECT prospectos -> <select>

2. Al seleccionar prospecto:
   - $.ajax -> prospectos_metodos.php?llenando_informacion_cotizacion={id}
   - Retorna: tipo_persona, apellido_paterno, apellido_materno, nombre, razon_social, cp, colonia, estado
```

### 2.2 Flujo de Calculo de Prima Neta (metodos_sacar_prima_neta)
```php
// FORMULA LEGACY:
// Si forma_pago == ANUAL:
$prima_neta = (prima_anual_neta / 1.16) - derecho_costo

// Si forma_pago == SEMESTRAL/TRIMESTRAL/MENSUAL:
$base = (prima_anual_neta / 1.16) - derecho_costo
$factor = 1 + (recargo / 100)
$prima_neta = $base / $factor

// Donde:
// - prima_anual_neta: Input del usuario (cantidad_prima_neta_opcion{N})
// - derecho_costo: SELECT derecho_costo FROM costo_derecho_poliza WHERE id_tabla_aseguradora = ?
// - recargo: SELECT cantidad_recargo FROM recargo_por_cargo_fraccionado WHERE id = ? AND forma_de_pago = ?
```

### 2.3 Flujo de Guardado (cotizacion_autos_alta)
```php
function cotizacion_autos_alta() {
    // 1. INSERT cotizacion principal
    INSERT INTO cotizacion_autos (
        tipo_cotizacion, hora_solicitada, Id_contacto_tabla_autos,
        Id_prospecto_tabla_autos, marca_auto, descripcion_auto,
        modelo_auto, uso_de_unidad_auto, tipo_auto, compania_autos,
        fecha_vigencia_autos, poliza_renovar_autos, prima_ano_autos,
        tipo_de_carga, paquete_solicitado, cantidad_aseguradoras,
        fecha_alta_auto, dio_alta_auto
    ) VALUES (...)

    // 2. Obtener ID insertado
    $id_ultimo = $pdo->lastInsertId()

    // 3. Loop por cada aseguradora (1 a cantidad_aseguradoras)
    for ($i = 0; $i < cantidad_aseguradoras; $i++) {
        // Obtener derecho de poliza
        SELECT derecho_costo FROM costo_derecho_poliza
        WHERE id_tabla_aseguradora = empresas_opcion{N}

        // Obtener recargo si no es ANUAL
        if (forma_de_pago != 'ANUAL') {
            SELECT cantidad_recargo FROM recargo_por_cargo_fraccionado
            WHERE id_tabla_aseguradoras_recargo = empresas_opcion{N}
            AND forma_de_pago = ?
        }

        // INSERT opcion
        INSERT INTO opciones_cotizaciones (
            Id_tabla_contizacion_autos_opciones,
            aseguradora_opciones,
            danos_materiales_opciones,
            importe_factura_danos_materiales_opciones,
            deducible_dm_opciones,
            cristales_opciones,
            robo_total_opciones,
            importe_factura_robo_total_opciones,
            deducible_rt_opciones,
            rc_danos_a_terceros_opciones,
            deducible_de_rc_opciones,
            rc_fallecimiento_opciones,
            gastos_medicos_opciones,
            accidentes_conductor_opciones,
            proteccion_legal_opciones,
            asistencia_legal_opciones,
            danos_por_la_carga_opciones,
            adaptaciones_opciones,
            extension_rc_opciones,
            opcion1_nombre_opciones,
            opcion1_valor_opciones,
            opcion2_nombre_opciones,
            opcion2_valor_opciones,
            forma_de_pago_opciones,
            prima_neta_anual_opciones,
            prima_total_anual_opciones,
            primer_pago_opciones,
            subsecuentes_opciones,
            derecho_pago_opciones,
            recargo_por_cargo_opciones
        ) VALUES (...)
    }

    // 4. Retornar ID y nombre para PDF
    echo "1*{$id_ultimo}*{$nombre_prospecto}-{$descripcion}"
}
```

---

## 3. MAPEO DE CAMPOS: LEGACY -> LARAVEL

### 3.1 Quote (cotizacion_autos -> quotes)
| Legacy Field | Laravel Field | Notes |
|--------------|---------------|-------|
| Id_contizacion_autos | id | Auto-increment |
| tipo_cotizacion | type | Enum: NUEVA->NEW, RENOVACION->RENEWAL |
| hora_solicitada | requested_at | Time format |
| Id_contacto_tabla_autos | contact_id | FK |
| Id_prospecto_tabla_autos | customer_id | FK |
| marca_auto | vehicle_data['brand'] | JSON |
| descripcion_auto | vehicle_data['description'] | JSON |
| modelo_auto | vehicle_data['year'] | JSON |
| uso_de_unidad_auto | vehicle_usage | String |
| tipo_auto | vehicle_type | String |
| compania_autos | previous_insurer | String |
| fecha_vigencia_autos | previous_expiry_date | Date |
| poliza_renovar_autos | previous_policy_number | String |
| prima_ano_autos | previous_premium_cents | Integer (centavos) |
| tipo_de_carga | cargo_description | String |
| paquete_solicitado | package_type | Enum: AMPLIA, LIMITADA, RC |
| cantidad_aseguradoras | options_count | Integer |
| estatus_concretar_cotizacion | status | Enum (ver abajo) |
| opcion_concreto | concluded_option_id | FK |
| numero_poliza | issued_policy_number | String |
| motivos_no_concretacion | rejection_reason | String |
| fecha_alta_auto | created_at | Timestamp |
| dio_alta_auto | agent_id | FK users |
| - | custom_coverage_1_name | YA EXISTE |
| - | custom_coverage_2_name | YA EXISTE |

### 3.2 QuoteOption (opciones_cotizaciones -> quote_options)
| Legacy Field | Laravel Field | Notes |
|--------------|---------------|-------|
| id_opciones | id | Auto-increment |
| Id_tabla_contizacion_autos_opciones | quote_id | FK |
| aseguradora_opciones | insurer_id | FK |
| danos_materiales_opciones | material_damage_type | YA EXISTE |
| importe_factura_danos_materiales_opciones | material_damage_amount | YA EXISTE |
| deducible_dm_opciones | material_damage_deductible | YA EXISTE |
| cristales_opciones | glass_coverage | YA EXISTE |
| robo_total_opciones | theft_type | YA EXISTE |
| importe_factura_robo_total_opciones | theft_amount | YA EXISTE |
| deducible_rt_opciones | theft_deductible | YA EXISTE |
| rc_danos_a_terceros_opciones | liability_third_party | YA EXISTE |
| deducible_de_rc_opciones | liability_deductible | YA EXISTE |
| rc_fallecimiento_opciones | liability_death | YA EXISTE |
| gastos_medicos_opciones | medical_expenses | YA EXISTE |
| accidentes_conductor_opciones | driver_accident | YA EXISTE |
| proteccion_legal_opciones | legal_protection | YA EXISTE |
| asistencia_legal_opciones | roadside_assistance | YA EXISTE |
| danos_por_la_carga_opciones | cargo_damage | YA EXISTE |
| adaptaciones_opciones | special_equipment | YA EXISTE |
| extension_rc_opciones | extended_liability | YA EXISTE |
| opcion1_nombre_opciones | (en Quote) custom_coverage_1_name | Compartido |
| opcion1_valor_opciones | custom_coverage_1_value | YA EXISTE |
| opcion2_nombre_opciones | (en Quote) custom_coverage_2_name | Compartido |
| opcion2_valor_opciones | custom_coverage_2_value | YA EXISTE |
| forma_de_pago_opciones | payment_frequency | Enum |
| prima_neta_anual_opciones | annual_net_premium_cents | YA EXISTE |
| prima_total_anual_opciones | annual_total_premium_cents | YA EXISTE |
| primer_pago_opciones | first_payment_cents | YA EXISTE |
| subsecuentes_opciones | subsequent_payment_cents | YA EXISTE |
| derecho_pago_opciones | policy_fee_cents | YA EXISTE |
| recargo_por_cargo_opciones | surcharge_cents | YA EXISTE |

### 3.3 InsurerFinancialSetting (costo_derecho_poliza + recargo_por_cargo_fraccionado)
| Legacy Tables | Laravel Field | Notes |
|---------------|---------------|-------|
| costo_derecho_poliza.derecho_costo | policy_fee_cents | Integer (centavos) |
| recargo_por_cargo_fraccionado.cantidad_recargo (SEMESTRAL) | surcharge_semiannual | Decimal (0-1) |
| recargo_por_cargo_fraccionado.cantidad_recargo (TRIMESTRAL) | surcharge_quarterly | Decimal (0-1) |
| recargo_por_cargo_fraccionado.cantidad_recargo (MENSUAL) | surcharge_monthly | Decimal (0-1) |

---

## 4. MAPEO DE CAMPOS: FRONTEND -> BACKEND

### 4.1 Campos del Formulario Vue -> Controller
| Frontend Field (Vue) | Backend Field (PHP) | Notes |
|---------------------|---------------------|-------|
| tipo_cotizacion | type | NUEVA/RENOVACION |
| hora_solicitada | requested_at | HH:MM |
| contactos | contact_id | ID |
| prospectos_asegurados | customer_id | ID |
| marca | vehicle_data.brand | String |
| descripcion | vehicle_data.description | String |
| modelo | vehicle_data.year | Integer |
| uso_de_unidad | vehicle_usage | String |
| tipo_auto | vehicle_type_id | FK |
| compania_actual | previous_insurer | String |
| fecha_vigencia | previous_expiry_date | Date |
| poliza_a_renovar | previous_policy_number | String |
| prima_ano | previous_premium_cents | *100 |
| tipo_de_carga | cargo_description | String |
| paquete | package_type | AMPLIA/LIMITADA/RC |
| cantidad_aseguradoras | options_count | 1-5 |
| forma_de_pago | (en cada opcion) payment_frequency | ANUAL/SEMESTRAL/etc |

### 4.2 Campos de Cobertura por Opcion (N = 1-5)
| Frontend Field | Backend Field |
|---------------|---------------|
| empresas_opcion{N} | insurer_id |
| danos_opcion{N}_selec | material_damage_type |
| danos_material_importe_factura_{N} | material_damage_amount |
| deducible_opcion{N} | material_damage_deductible |
| cristales_opcion{N}_selec | glass_coverage |
| robo_opcion{N}_selec | theft_type |
| robo_importe_factura_{N} | theft_amount |
| deducible_rt{N} | theft_deductible |
| danos_tercero_opcion_{N} | liability_third_party |
| deducible_de_rc_opcion{N} | liability_deductible |
| fallecimiento_opcion_{N} | liability_death |
| gastos_medicos_opcion_{N} | medical_expenses |
| accidente_conducir_opcion_{N} | driver_accident |
| proteccion_opcion{N}_selec | legal_protection |
| asistencia_vial_opcion{N}_selec | roadside_assistance |
| danos_carga_opcion_selec_{N} | cargo_damage |
| adaptaciones_opcion_{N} | special_equipment |
| extension_rc_opcion{N} | extended_liability |
| cobertura_opcion_1 | (Quote) custom_coverage_1_name |
| cobertura_opcion_{N}_select | custom_coverage_1_value |
| cobertura_opcion_2 | (Quote) custom_coverage_2_name |
| cobertura_opcion_2_{N}_select | custom_coverage_2_value |
| cantidad_prima_neta_opcion{N} | annual_net_premium_cents |
| cantidad_total_anual_opcion_{N} | annual_total_premium_cents |
| primer_pago_opcion_{N} | first_payment_cents |

---

## 5. FORMULAS DE CALCULO LEGACY

### 5.1 Prima Neta (metodos_sacar_prima_neta)
```php
// Input: prima_anual_neta (lo que ingresa el usuario como prima total)
// El sistema calcula hacia atras la prima neta

if ($forma_pago == 'ANUAL') {
    // Sin recargo
    $prima_neta = ($prima_anual_neta / 1.16) - $derecho_costo;
} else {
    // Con recargo (SEMESTRAL, TRIMESTRAL, MENSUAL)
    $base = ($prima_anual_neta / 1.16) - $derecho_costo;
    $factor = 1 + ($recargo / 100);  // recargo es porcentaje (ej: 5, 8, 12)
    $prima_neta = $base / $factor;
}
```

### 5.2 Subsecuentes (cotizaciones_nuevas_crear_subsecuentes)
```php
switch ($forma_de_pago) {
    case 'ANUAL':
        $subsecuentes = 'N/A';
        break;
    case 'SEMESTRAL':
        $subsecuentes = $primer_pago;  // 2 pagos iguales
        break;
    case 'TRIMESTRAL':
        $subsecuentes = ($prima_total_anual - $primer_pago) / 3;  // 4 pagos (1+3)
        break;
    case 'MENSUAL':
        $subsecuentes = ($prima_total_anual - $primer_pago) / 11;  // 12 pagos (1+11)
        break;
}
```

---

## 6. VALIDACIONES LEGACY (formulario.js)

### 6.1 Validaciones de Campos Principales
```javascript
// Tipo cotizacion
if (tipo_cotizacion == 0) {
    error = '¡Debes seleccionar un tipo de cotización!';
}

// Hora solicitada
if (hora_solicitada == '') {
    error = '¡Debes ingresar la hora solicitada!';
}

// Contacto
if (contactos == 0) {
    error = '¡Debes seleccionar un contacto!';
}

// Prospecto
if (prospectos_asegurados == 0) {
    error = '¡Debe seleccionar un prospecto!';
}

// Marca
if (marca == '' || !/^([a-zA-Z0-9...])/.test(marca)) {
    error = '¡Debes ingresar una marca!' o '¡No se aceptan caracteres especiales!';
}

// Descripcion (max 100 chars)
if (descripcion == '' || descripcion.length > 100) {
    error = '¡Debes ingresar una descripción!' o '¡Solo se permite hasta 100 caracteres!';
}

// Modelo (solo numeros, 4 digitos)
if (modelo == '' || modelo.length < 4 || modelo.length > 4) {
    error = '¡Debes ingresar el modelo del auto!' o '¡Sólo se permiten 4 caracteres!';
}

// Paquete
if (paquete == 0) {
    error = '¡Debes seleccionar un paquete!';
}

// Cantidad aseguradoras
if (cantidad_aseguradoras == 0) {
    error = '¡Debes seleccionar una cantidad de aseguradoras a cotizar!';
}
```

### 6.2 Validaciones Dinamicas por Paquete
```javascript
// Si paquete == 'AMPLIA':
// - Todos los campos de cobertura son requeridos

// Si paquete == 'LIMITADA':
// - danos_materiales: NO REQUERIDO (puede ser NO AMPARADO)
// - robo_total: REQUERIDO
// - Los demas: depende del valor seleccionado

// Si paquete == 'RC':
// - Solo RC es requerido
// - danos_materiales: NO REQUERIDO
// - robo_total: NO REQUERIDO
```

### 6.3 Validacion Cobertura Adicional (Bidireccional)
```javascript
// Si se selecciona valor en cobertura_opcion_1_select:
if (cobertura_opcion_1_select != 0 && cobertura_opcion_1 == '') {
    error = '¡Debes ingresar nombre de cobertura!';
}

// Si se ingresa nombre en cobertura_opcion_1:
if (cobertura_opcion_1 != '' && cobertura_opcion_1_select == 0) {
    error = '¡Debes seleccionar un tipo de cobertura!';
}
```

### 6.4 Validacion de Aseguradora Unica
```javascript
// No puede seleccionarse la misma aseguradora en multiples opciones
if (opcion2 != 0 && opcion2 == opcion1) {
    error = '¡Ya se encuentra selecciona la asegurada. Selecciona una diferente!';
}
```

---

## 7. ESTADO ACTUAL DEL SISTEMA LARAVEL

### 7.1 Modelos Existentes (YA IMPLEMENTADOS)
- Quote.php - Cotizacion principal
- QuoteOption.php - Opciones por aseguradora
- InsurerFinancialSetting.php - Configuracion financiera
- Customer.php - Prospectos/Clientes
- Contact.php - Contactos
- Insurer.php - Aseguradoras

### 7.2 Enums Existentes
- QuoteStatus: DRAFT, QUOTED, SENT, CONCRETED, ISSUED, REJECTED, EXPIRED, CANCELLED
- QuoteType: NEW, RENEWAL
- CoveragePackage: FULL, LIMITED, LIABILITY_ONLY
- PaymentFrequency: ANNUAL, SEMIANNUAL, QUARTERLY, MONTHLY

### 7.3 Controladores Existentes
- QuoteController.php
- CustomerController.php
- ContactController.php

---

## 8. GAPS IDENTIFICADOS (PENDIENTES DE IMPLEMENTAR)

### 8.1 Backend
1. **Calculo de Prima Neta** - Implementar formula legacy en servicio
2. **Calculo de Subsecuentes** - Implementar formula legacy
3. **Endpoint para obtener derecho/recargo** - GET /api/insurers/{id}/financial-settings
4. **Mapeo completo de campos** - En StoreQuoteRequest y controlador

### 8.2 Frontend (Create.vue)
1. **AJAX para cargar derecho/recargo** - Al seleccionar aseguradora
2. **Calculo automatico de prima neta** - Al cambiar prima total anual
3. **Calculo automatico de subsecuentes** - Al cambiar primer pago
4. **Validaciones completas** - Implementar todas las del legacy

### 8.3 Migraciones (si aplica)
- Las tablas ya existen con campos equivalentes
- No se requieren migraciones adicionales

---

## 9. PLAN DE IMPLEMENTACION

### Fase 1: Backend Services
1. Crear `PremiumCalculationService.php`
2. Implementar `calculateNetPremium()`
3. Implementar `calculateSubsequentPayments()`
4. Crear endpoint API para configuracion financiera

### Fase 2: Frontend Integration
1. Agregar watch para aseguradora -> cargar derecho/recargo
2. Agregar watch para prima total -> calcular prima neta
3. Agregar watch para primer pago -> calcular subsecuentes
4. Completar validaciones faltantes

### Fase 3: Form Submission
1. Mapear campos frontend -> backend
2. Crear opciones por cada aseguradora
3. Guardar derecho y recargo por opcion
4. Retornar datos para PDF

---

## 10. NOTAS IMPORTANTES

1. **NO CAMBIAR** campos existentes que ya funcionan
2. **NO CAMBIAR** validaciones que ya estan implementadas
3. **AGREGAR** funcionalidad faltante sin modificar lo existente
4. **MANTENER** compatibilidad con el flujo actual
5. **PRESERVAR** la logica de negocio del sistema legacy exactamente igual
