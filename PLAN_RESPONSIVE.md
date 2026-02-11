# PLAN DE IMPLEMENTACION RESPONSIVE — COTIZADOR AUTOS

**Basado en:** Escaner forense del 2026-02-10 (39 componentes analizados)
**Alcance:** Solo arquitectura. No implementar aun.

---

## 1. ESTRATEGIA RESPONSIVE GLOBAL

### Principio rector: Mobile-First Correctivo

El sistema fue construido desktop-first con responsive parcial. La estrategia
NO es reescribir todo, sino **corregir los puntos de quiebre detectados** con
el minimo de cambios posible, concentrando las reglas en los archivos CSS
centrales y los wrappers reutilizables.

### Enfoque en 3 capas

```
CAPA 1 — Design System (variables.css + components.css + app.css)
  → Breakpoints oficiales como CSS custom properties
  → Reglas responsive para clases reutilizables (.form-row--3, .metric-value, etc.)
  → 0 cambios en componentes individuales

CAPA 2 — Wrappers compartidos (CrudTable, CrudModal, Toast, FormInput)
  → Media queries internas para comportamiento responsive automatico
  → Cada pagina que use el wrapper hereda el fix gratis

CAPA 3 — Paginas individuales (Show pages, Dashboards)
  → Solo donde las capas 1 y 2 no resuelvan
  → Minimo CSS scoped adicional
```

### Filosofia: Maxima cobertura con minimo cambio

| Cambio en... | Cubre automaticamente... |
|---|---|
| `components.css` → `.form-row--3` | Customers Create, Edit, y cualquier futuro formulario 3-col |
| `CrudTable.vue` → overflow-x | 9 paginas admin + Contacts + Customers Index |
| `CrudModal.vue` → body height | Todas las modales del sistema (~15 usos) |
| `components.css` → `.metric-value` | Todos los dashboards y metric cards |

---

## 2. PATRON OFICIAL PARA TABLAS

### Problema detectado

CrudTable.vue tiene `overflow: hidden` en `.crud-table-wrapper`. Las 9 paginas
admin que usan CrudTable NO tienen overflow-x individual. Solo Quotes/Index
tiene `overflow-x: auto` propio.

### Patron a implementar: Scroll horizontal automatico

```
.crud-table-wrapper
  └─ overflow-x: auto (NUEVO)
  └─ -webkit-overflow-scrolling: touch (iOS momentum)
  └─ Indicador visual de scroll (gradiente lateral)
```

**Regla:** En mobile (<768px), TODAS las tablas CrudTable tendran scroll
horizontal automatico. No se ocultan columnas. No se cambia a card-view.
Esto es la solucion de menor riesgo y menor esfuerzo.

### Justificacion de NO usar card-view

- Card-view requiere reescribir el template de cada tabla
- Cada tabla tiene columnas distintas, slots custom, actions custom
- Scroll horizontal es el patron mas usado en apps empresariales mobile
- CrudTable ya envuelve vue3-easy-data-table que mantiene alignment de headers

### Implementacion concreta (CrudTable.vue)

```css
/* Agregar a .crud-table-wrapper */
.crud-table-wrapper {
    overflow-x: auto;           /* ERA: overflow: hidden */
    -webkit-overflow-scrolling: touch;
}

/* Indicador visual de que hay scroll */
@media (max-width: 768px) {
    .crud-table-wrapper {
        position: relative;
    }
    .crud-table-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 24px;
        background: linear-gradient(to right, transparent, rgba(255,255,255,0.8));
        pointer-events: none;
        z-index: 1;
    }
}
```

### Caso especial: CoverageTable.vue

Ya tiene `overflow-x: auto`. NO necesita cambio de patron.
Sus inputs dentro de celdas son funcionales con scroll lateral.
Solo necesita ajuste de `min-width` en columnas a 768px en vez de 1024px.

### Resultado esperado por tabla

| Tabla | Cols | Con fix | Comportamiento |
|---|---|---|---|
| Customers Index | 8 | Scroll-x auto | Funcional en 320px+ |
| Contacts Index | 8 | Scroll-x auto | Funcional en 320px+ |
| Staff Index | 7 | Scroll-x auto | Funcional en 320px+ |
| Users Index | 6 | Scroll-x auto | Funcional en 320px+ |
| PaymentMethods | 6 | Scroll-x auto | Funcional en 320px+ |
| Insurers Index | 6 | Scroll-x auto | Funcional en 320px+ |
| Surcharges | 5 | Scroll-x auto | Funcional en 320px+ |
| VehicleBrands | 4 | Scroll-x auto | Funcional en 320px+ |
| VehicleTypes | 3 | Ya funcional | Sin cambio |
| Quotes Index | 8 | Ya tiene fix | Sin cambio |
| CoverageTable | 6 | Ya tiene fix | Ajuste menor min-width |

---

## 3. PATRON OFICIAL PARA FORMULARIOS

### Problema detectado

`.form-row--2col` tiene media query @ 640px (colapsa a 1 col).
`.form-row--3` NO tiene media query → 3 columnas forzadas en 320px.

### Patron a implementar: Grid colapsable consistente

Todas las clases `.form-row--*` colapsaran a 1 columna en mobile.
Reglas centralizadas en `components.css`.

```css
/* AGREGAR a components.css */

/* ===== FORM GRIDS RESPONSIVE ===== */
.form-row--2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-row--3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 1rem;
}

.form-row--4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

@media (max-width: 1024px) {
    .form-row--4 {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 640px) {
    .form-row--2col,
    .form-row--3,
    .form-row--4 {
        grid-template-columns: 1fr;
    }
}
```

### Resultado: Cobertura automatica

Paginas que heredan el fix sin tocar su codigo:
- `Customers/Create.vue` (seccion direccion: form-row--3)
- `Customers/Edit.vue` (seccion direccion: form-row--3)
- Cualquier futura pagina que use form-row--3 o form-row--4

### Patron para labels

Labels siempre arriba del input (ya es asi). No cambiar a inline.
Los inputs ya son `width: 100%`. Solo asegurar que el grid colapsa.

---

## 4. BREAKPOINTS DEFINITIVOS DEL SISTEMA

### Sistema de breakpoints (mobile-first)

```
NOMBRE     VARIABLE              VALOR    USO
───────────────────────────────────────────────────
xs         --bp-xs               0px      Base mobile (default styles)
sm         --bp-sm               640px    Tablet small / landscape phone
md         --bp-md               768px    Tablet portrait
lg         --bp-lg               1024px   Desktop / Tablet landscape
xl         --bp-xl               1280px   Desktop grande
2xl        --bp-2xl              1536px   Monitor full HD+
```

### Comportamiento por breakpoint

| Breakpoint | Sidebar | Grid formularios | Tablas | KPI cards |
|---|---|---|---|---|
| 0-639px | Overlay 280px, oculto | 1 columna | Scroll-x | 2 por fila |
| 640-767px | Overlay 280px, oculto | 1 columna | Scroll-x | 3 por fila |
| 768-1023px | Overlay 280px, oculto | 2 columnas | Visible (si cabe) | 3-4 por fila |
| 1024px+ | Fixed 280px, visible | 2-3 columnas | Visible completa | 6 por fila |
| 1280px+ | Fixed 280px, visible | 3-4 columnas | Visible completa | 6 por fila |

### Variables CSS (agregar a variables.css)

```css
:root {
    /* Breakpoints (solo documentacion, CSS no soporta variables en @media) */
    /* sm: 640px | md: 768px | lg: 1024px | xl: 1280px | 2xl: 1536px */

    /* Anchos responsive */
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 80px;
    --header-height: 64px;
    --content-max-width: 1400px;
    --modal-padding-mobile: 1rem;
    --modal-padding-desktop: 2rem;
}
```

---

## 5. QUICK WINS PRIORITARIOS

Cambios que cubren el maximo de issues con el minimo de archivos.

### QW-1: `components.css` — Form grids responsive
**Archivo:** `resources/css/components.css`
**Cambio:** Agregar media query para `.form-row--3` y `.form-row--4`
**Cubre:** Customers Create/Edit (seccion direccion rota en mobile)
**Impacto:** CRITICO → resuelto
**Esfuerzo:** ~15 minutos
**Lineas de codigo:** ~12

### QW-2: `CrudTable.vue` — overflow-x: auto
**Archivo:** `resources/js/components/Crud/CrudTable.vue`
**Cambio:** `.crud-table-wrapper { overflow-x: auto }` + indicador scroll
**Cubre:** 9 paginas admin + Customers Index + Contacts Index
**Impacto:** CRITICO → resuelto (11 tablas)
**Esfuerzo:** ~15 minutos
**Lineas de codigo:** ~15

### QW-3: `CrudModal.vue` — modal body height + sticky footer
**Archivo:** `resources/js/components/Crud/CrudModal.vue`
**Cambio:** `max-height: 70vh` en mobile, footer sticky
**Cubre:** ~15 modales del sistema
**Impacto:** ALTO → resuelto
**Esfuerzo:** ~10 minutos
**Lineas de codigo:** ~8

### QW-4: `components.css` — metric-value responsive
**Archivo:** `resources/css/components.css`
**Cambio:** `.metric-value { font-size: clamp(1.5rem, 4vw, 2.5rem); word-break: break-word; }`
**Cubre:** Admin/Manager/Operator dashboards, KpiCard, PremiumStatCard
**Impacto:** CRITICO → resuelto
**Esfuerzo:** ~10 minutos
**Lineas de codigo:** ~6

### QW-5: `Toast.vue` — remover min-width en mobile
**Archivo:** `resources/js/components/Ui/Toast.vue`
**Cambio:** `@media (max-width: 640px) { min-width: auto; width: 100%; }`
**Cubre:** Todas las notificaciones del sistema
**Impacto:** ALTO → resuelto
**Esfuerzo:** ~5 minutos
**Lineas de codigo:** ~4

### QW-6: AppLayout.vue — notifications dropdown responsive
**Archivo:** `resources/js/Layouts/AppLayout.vue`
**Cambio:** Dropdown width `calc(100vw - 2rem)` en <425px, max-width 360px
**Cubre:** Layout principal
**Impacto:** ALTO → resuelto
**Esfuerzo:** ~10 minutos
**Lineas de codigo:** ~8

**Total Quick Wins: ~65 minutos, ~53 lineas de codigo, resuelve 80% de issues CRITICOS**

---

## 6. COMPONENTES BASE A CORREGIR (WRAPPERS)

### 6.1 CrudTable.vue (PRIORIDAD 1)

**Estado actual:**
- `overflow: hidden` en wrapper → corta contenido
- Padding 1rem constante → desperdicia espacio en mobile
- Search bar max-width 360px → funcional
- Action buttons 32x32px → debajo de 44px touch target

**Cambios requeridos:**
1. `overflow: hidden` → `overflow-x: auto`
2. Padding responsive: 1rem → 0.75rem en <640px
3. Indicador de scroll lateral (gradiente fade)
4. Action buttons: agregar padding para touch target 44px
5. Search bar: max-width: 100% en <640px

### 6.2 CrudModal.vue (PRIORIDAD 1)

**Estado actual:**
- `max-height: 60vh` desktop, `50vh` mobile → insuficiente con keyboard
- Footer NO sticky → desaparece al scrollear
- Close button 32x32px → debajo de 44px touch target
- Bottom-sheet en <640px → correcto, mantener

**Cambios requeridos:**
1. Modal body: `max-height: 50vh` → `max-height: 70vh` en mobile
2. Footer: agregar `position: sticky; bottom: 0`
3. Close button: agregar min-width/min-height 44px en touch devices
4. Mantener bottom-sheet pattern en mobile (ya funciona)

### 6.3 Toast.vue + ToastContainer.vue (PRIORIDAD 2)

**Estado actual:**
- Toast `min-width: 280px` → overflow en 320px
- Container `right: 1.5rem` desktop, `right: 1rem; left: 1rem` mobile
- Conflicto entre min-width del toast y constraints del container

**Cambios requeridos:**
1. Toast: `min-width: 280px` → `min-width: auto` en <640px
2. Toast: agregar `width: 100%` en mobile
3. Container: mantener left+right: 1rem en mobile (ya correcto)

### 6.4 FormInput.vue (PRIORIDAD 2)

**Estado actual:**
- Character counter `position: absolute; right: 10px` → se superpone al texto
- Input padding no compensa counter

**Cambios requeridos:**
1. Counter: reposicionar debajo del input en <425px
2. O: agregar padding-right al input cuando counter esta visible

### 6.5 FormSelect.vue (PRIORIDAD 3)

**Estado actual:**
- Arrow touch zone ~12px → dificil de tapear
- Funcional pero mejorable

**Cambios requeridos:**
1. Arrow container: aumentar zona de click a 44px en touch devices

### 6.6 ConfirmDialog.vue (PRIORIDAD 3)

**Estado actual:**
- Padding 2rem constante → come 64px de los 320px disponibles
- max-width 400px → funcional en >375px

**Cambios requeridos:**
1. Padding responsive: 2rem → 1.5rem en <640px
2. Botones: evaluar flex-wrap para 320px

---

## 7. ORDEN DE INTERVENCION POR IMPACTO UX

### FASE 1 — Quick Wins Criticos (1-2 horas)

Resuelve: 80% de issues CRITICOS. 0 cambios en paginas individuales.

| # | Archivo | Cambio | Issues resueltos |
|---|---|---|---|
| 1 | `components.css` | form-row--3 responsive | Customers Create/Edit address |
| 2 | `CrudTable.vue` | overflow-x: auto | 11 tablas sin scroll |
| 3 | `components.css` | metric-value clamp() | KPI overflow en 3 dashboards |
| 4 | `CrudModal.vue` | body 70vh + sticky footer | ~15 modales |
| 5 | `Toast.vue` | min-width mobile | Notificaciones |
| 6 | `AppLayout.vue` | notif dropdown responsive | Layout principal |

### FASE 2 — Show Pages (2-3 horas)

Resuelve: Vistas de detalle rotas. Requiere CSS scoped en cada pagina.

| # | Archivo | Cambio | Issue |
|---|---|---|---|
| 7 | `Quotes/Show.vue` | content-grid mobile-first (1fr default, 2col @1024px) | Grid 2-col forzado |
| 8 | `Quotes/Show.vue` | info-grid 1fr en <768px | Info ilegible |
| 9 | `Contacts/Show.vue` | content-grid + info-grid responsive | Grid 2-col sin breakpoint |
| 10 | `Customers/Show.vue` | quote-item grid 1fr en <768px | 4-col forzado |

### FASE 3 — Dashboards y Cards (2-3 horas)

Resuelve: Display numerico roto, chart labels, touch targets.

| # | Archivo | Cambio | Issue |
|---|---|---|---|
| 11 | `KpiCard.vue` | font-size clamp() + word-break | Valor 2rem fijo |
| 12 | `PremiumStatCard.vue` | font-size clamp() | Valor 3rem fijo |
| 13 | `Dashboard/Admin.vue` | chart bar labels responsive | Labels superpuestos |
| 14 | `Dashboard/Admin.vue` | header buttons touch targets | Iconos <44px |
| 15 | `Dashboard/Operator.vue` | action cards minmax ajuste | Cramped en 320px |

### FASE 4 — Pulido Mobile (1-2 horas)

Resuelve: Issues MEDIO y BAJO.

| # | Archivo | Cambio | Issue |
|---|---|---|---|
| 16 | `FormInput.vue` | counter reposicion mobile | Superposicion texto |
| 17 | `FormSelect.vue` | arrow touch zone 44px | Touch target chico |
| 18 | `ConfirmDialog.vue` | padding responsive | 224px content en 320px |
| 19 | `AppLayout.vue` | sidebar width min(90vw, 280px) | 280px > 320px viewport |
| 20 | `AppLayout.vue` | header datetime font-size | 9px ilegible |

---

## 8. ESTIMACION TECNICA POR FASE

| Fase | Archivos | Lineas CSS aprox | Tiempo estimado | Riesgo regresion |
|---|---|---|---|---|
| Fase 1 — Quick Wins | 4 archivos | ~55 lineas | 1-2 horas | MUY BAJO (solo CSS aditivo) |
| Fase 2 — Show Pages | 3 archivos | ~40 lineas | 2-3 horas | BAJO (CSS scoped aislado) |
| Fase 3 — Dashboards | 4 archivos | ~50 lineas | 2-3 horas | BAJO (CSS scoped aislado) |
| Fase 4 — Pulido | 5 archivos | ~35 lineas | 1-2 horas | BAJO |
| **TOTAL** | **16 archivos** | **~180 lineas** | **6-10 horas** | |

### Desglose de esfuerzo

```
Fase 1:  ████████████████░░░░  80% del impacto con 20% del esfuerzo
Fase 2:  ████████░░░░░░░░░░░░  12% del impacto
Fase 3:  ████░░░░░░░░░░░░░░░░   6% del impacto
Fase 4:  ██░░░░░░░░░░░░░░░░░░   2% del impacto
```

**La Fase 1 sola resuelve el 80% de los issues criticos del sistema.**

---

## 9. RIESGOS DE IMPLEMENTACION

### R1: Regresion en desktop (BAJO)
**Mitigacion:** Todos los cambios usan `@media (max-width: X)` o `clamp()`.
No se modifica ningun estilo base de desktop. Solo se agregan reglas para
pantallas pequenas.

### R2: Conflicto con vue3-easy-data-table (MEDIO)
**Detalle:** CrudTable usa `overflow: hidden` probablemente para contener
la libreria. Cambiar a `overflow-x: auto` podria exponer scrollbars
inesperados en desktop.
**Mitigacion:** Solo aplicar `overflow-x: auto` dentro de `@media (max-width: 768px)`.
Mantener `overflow: hidden` en desktop. Si la libreria genera scroll
horizontal propio, testear con datos largos.

### R3: Modal sticky footer + overflow (BAJO)
**Detalle:** `position: sticky` en footer podria causar problemas si
modal-body tiene `overflow-y: auto` (sticky no funciona dentro de
overflow scroll en ciertos browsers).
**Mitigacion:** Reestructurar el modal con flexbox:
```
.modal-content { display: flex; flex-direction: column; max-height: 90vh; }
.modal-body { flex: 1; overflow-y: auto; }
.modal-footer { flex-shrink: 0; }
```
Esto es mas robusto que sticky y funciona en todos los browsers.

### R4: clamp() en metric values (MUY BAJO)
**Detalle:** `clamp()` tiene soporte 95%+ en browsers modernos.
**Mitigacion:** Dado que es app interna (no publica), el riesgo es nulo.
Los usuarios usan Chrome/Edge moderno.

### R5: Scroll horizontal en tablas oculta datos (MEDIO)
**Detalle:** Usuarios mobile podrian no darse cuenta de que hay mas
columnas a la derecha.
**Mitigacion:** Indicador visual (gradiente fade en el borde derecho) +
instruccion en UI si es la primera vez.

### R6: Touch targets 44px aumentan altura de filas en tablas (BAJO)
**Detalle:** Action buttons de 32px → 44px touch zone podria
aumentar el row-height.
**Mitigacion:** Usar padding en vez de height para touch zone:
`padding: 6px` alrededor del boton de 32px = 44px zona efectiva.

---

## 10. REGLAS RESPONSIVE DEL DESIGN SYSTEM FINAL

### 10.1 Reglas de Grid

```
REGLA GRID-1: Todo grid multi-columna DEBE colapsar a 1 columna en <640px
REGLA GRID-2: Grids de 4+ columnas DEBEN tener paso intermedio en <1024px (2 cols)
REGLA GRID-3: Usar CSS Grid (NO flexbox) para layouts de contenido
REGLA GRID-4: Usar flexbox para alineacion de elementos inline (headers, badges, botones)
```

### 10.2 Reglas de Tablas

```
REGLA TABLE-1: Toda tabla con 4+ columnas DEBE tener overflow-x: auto en su contenedor
REGLA TABLE-2: Tablas con inputs DEBEN tener min-width por celda de 120px
REGLA TABLE-3: En mobile, SIEMPRE scroll horizontal. NUNCA ocultar columnas
REGLA TABLE-4: Indicador visual obligatorio cuando hay scroll horizontal oculto
```

### 10.3 Reglas de Formularios

```
REGLA FORM-1: Labels SIEMPRE arriba del input (nunca inline en mobile)
REGLA FORM-2: Inputs SIEMPRE width: 100% dentro de su celda de grid
REGLA FORM-3: form-row--N colapsa a 1 columna en <640px
REGLA FORM-4: Botones de accion full-width en <425px
```

### 10.4 Reglas de Modales

```
REGLA MODAL-1: Bottom-sheet en <640px (align-items: flex-end)
REGLA MODAL-2: max-height: 90vh para modal completo
REGLA MODAL-3: Body scrolleable, footer SIEMPRE visible (flexbox, no sticky)
REGLA MODAL-4: Close button min 44x44px touch target
```

### 10.5 Reglas de Tipografia

```
REGLA TYPE-1: Valores numericos/monetarios usar clamp() (min 1.25rem, max 2.5rem)
REGLA TYPE-2: Texto minimo legible: 0.75rem (12px). NUNCA menor
REGLA TYPE-3: Headings ya usan clamp() en app.css — no modificar
REGLA TYPE-4: Monospace (JetBrains Mono) + word-break: break-word en cards
```

### 10.6 Reglas de Touch

```
REGLA TOUCH-1: Todo elemento interactivo min 44x44px en touch devices
REGLA TOUCH-2: Gap minimo entre elementos interactivos: 8px
REGLA TOUCH-3: Ya existe @media (hover: none) en app.css — reutilizar
```

### 10.7 Reglas de Contenedores

```
REGLA CONT-1: page-container padding: 1rem en <640px, 1.5rem en 640px+, 2rem en 1024px+
REGLA CONT-2: max-width: 1400px para contenido principal
REGLA CONT-3: Notifications/dropdowns: max-width: calc(100vw - 2rem) en mobile
REGLA CONT-4: Sidebar: min(90vw, 280px) en mobile overlay mode
```

### 10.8 Breakpoints oficiales (resumen ejecutivo)

```css
/* BREAKPOINTS OFICIALES — COTIZADOR AUTOS */
/* No tocar salvo decision de equipo */

/*  <640px   → mobile        → 1 col, scroll-x tablas, bottom-sheet modals */
/*  640px    → tablet-sm     → 1-2 cols, mismo comportamiento mobile */
/*  768px    → tablet        → 2 cols, tablas visibles si caben */
/*  1024px   → desktop       → sidebar visible, 2-3 cols, todo visible */
/*  1280px   → desktop-lg    → 3-4 cols, espaciado amplio */
/*  1536px   → desktop-xl    → max-width 1400px container */
```

---

## RESUMEN EJECUTIVO

```
 ESTADO ACTUAL          → ESTADO OBJETIVO
─────────────────────────────────────────────
 18 vistas rotas mobile → 0 vistas rotas
 11 tablas sin scroll   → 0 tablas sin scroll
 3 formularios rotos    → 0 formularios rotos
 15 modales limitados   → 15 modales funcionales
 KPIs ilegibles mobile  → KPIs responsive
─────────────────────────────────────────────
 ESFUERZO TOTAL: ~180 lineas CSS en 16 archivos
 TIEMPO TOTAL:   6-10 horas (4 fases)
 FASE 1 SOLA:    80% de los fixes criticos en 1-2 horas
```
