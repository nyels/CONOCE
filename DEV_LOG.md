# 📜 Registro de Desarrollo IA (DEV_LOG)

> **INSTRUCCIÓN PARA IA:** Al iniciar una nueva sesión, LEE SIEMPRE este archivo para entender el contexto histórico, los errores recientes corregidos y el estado real del sistema.

---

## 📅 Sesión: 2026-01-15 (Noche)
**Estado:** ✅ Fase 3 Completada (Wizard, Clientes, PDFs)

### 🚀 Logros Principales
1.  **Wizard de Cotizaciones (`Quotes/Create.vue`) terminado y funcional.**
    - Conectado con cálculo de primas.
    - Validación de pasos implementada.
    - Guardado exitoso en base de datos.
2.  **Módulo de Clientes (`Customers/*.vue`) completado.**
    - CRUD completo (Index, Create, Show, Edit).
    - Integración con Wizard (selección/creación inline).
3.  **Configuración Regional e Idioma.**
    - Configurado Laravel en español (`lang/es/validation.php`).
    - Mensajes de error amigables.

### 🐛 Errores Corregidos (Debugging Log)
| Error / Problema | Causa | Solución Aplicada |
|------------------|-------|-------------------|
| `Ziggy error: route 'quotes.create' not found` | Rutas JS desactualizadas | Ejecutado `php artisan ziggy:generate`. |
| `Call to undefined method ...->ordered()` | Scope `ordered` no existía en modelos | Reemplazado por `->orderBy('name')` en controller. |
| `Property policy_fee_cents does not exist...` | Relación `hasMany` tratada como objeto | Usado `financialSettings->first()?` en lugar de acceso directo. |
| `options field must not have more than 5 items` | Validación estricta en Request | Cambiado `max:5` a `max:10` en `QuoteController`. |
| `physical is not valid backing value for enum` | Enum PHP es `PHYSICAL` (caps) pero front enviaba `physical`. | Agregado `strtoupper()` en controller al crear cliente. |
| `new_customer.name field required` (al elegir existente) | Validación `required` aplicaba siempre. | Cambiado a `nullable|required_without:customer_id` y se limpia `new_customer` en frontend al seleccionar cliente. |
| **SQL Error: Not null violation (coverage_package)** | `QuoteOption` requiere campos obligatorios no enviados. | Se agregaron `coverage_package` (heredado), `payment_frequency` (default ANNUAL) y cálculos de IVA al crear la opción. |

### ⚠️ Deuda Técnica / Pendientes Menores
1.  **Fuente faltante:** Error 404 con `jetbrainsmono.woff2`. No crítico pero molesto en consola.
2.  **Cálculos Hardcoded en Backend:** El cálculo de IVA en `QuoteController::store` es fijo al 16% y asume pago anual. Deberá refactorizarse para usar el servicio `PremiumCalculator` real en el futuro.
3.  `is_active` en Clientes: Faltaba en `fillable` (se agregó implícitamente o se debe verificar).

### 🔄 PIVOT DE NEGOCIO (Decisión Crítica)
**Problema:** Se implementó un motor de cálculo automático simulado. Esto es incorrecto porque el negocio opera obteniendo tarifas de portales externos manualmente.
**Nueva Dirección:** Refactorizar el "Paso 4" del Wizard para que sea una **Herramienta de Captura Manual**.
- Eliminar checkboxes y lógica de cálculo automático.
- Implementar tabla dinámica para ingresar: Aseguradora, Cobertura, Prima Neta, Derechos, IVA, Total.
- El sistema actuará como **Comparador y Generador de PDFs**, no como Tarifificador.

---

## 📅 Sesión: [FECHA] (Espacio para siguiente IA)
... registro de cambios ...
