# SYSTEM CONTEXT - CONOCE Cotizador de Seguros

> **ARCHIVO DE CONTEXTO OBLIGATORIO** - Todo agente AI DEBE leer este archivo antes de trabajar en el proyecto.
> **Actualizado:** 2026-01-15

---

## LECTURA OBLIGATORIA

Antes de cualquier modificacion, el agente DEBE:
1. Leer este archivo completo
2. Leer `PROJECT_GUIDELINES.md` para reglas y estandares
3. Consultar `.agent/artifacts/IMPLEMENTATION_PLAN.md` para el roadmap

---

## RESUMEN EJECUTIVO

**Proyecto:** Sistema de Cotizacion de Seguros Automotrices para CONOCE Seguros y Fianzas
**Stack:** Laravel 12 + Vue 3 + Inertia.js + TailwindCSS v4 + PostgreSQL
**Arquitectura:** Hexagonal (DDD)
**Estado:** En desarrollo activo - Fase 1 completa

---

## ARQUITECTURA DEL SISTEMA

```
cotizador-autos/
├── app/                          # Infraestructura Laravel
│   ├── Http/
│   │   ├── Controllers/          # DashboardController (unico activo)
│   │   ├── Middleware/           # HandleInertiaRequests
│   │   └── Requests/             # (pendiente)
│   ├── Models/                   # User, Quote, Customer, Contact, Insurer
│   └── Providers/                # FortifyServiceProvider
│
├── src/Domain/                   # Capa de Dominio Puro
│   ├── Shared/
│   │   ├── Enums/UserRole.php
│   │   └── ValueObjects/         # Money, Email, RFC, PhoneNumber
│   ├── Quote/
│   │   ├── Enums/                # QuoteStatus, QuoteType, PaymentFrequency
│   │   └── Services/             # PremiumCalculatorService
│   ├── Customer/Enums/           # CustomerType
│   └── Contact/Enums/            # ContactType
│
├── resources/
│   ├── js/
│   │   ├── Pages/Dashboard.vue   # Dashboard principal
│   │   ├── Layouts/AppLayout.vue # Layout con sidebar
│   │   ├── app.js                # Entry point Vue/Inertia
│   │   └── ziggy.js              # Rutas de Laravel para JS
│   ├── views/
│   │   ├── app.blade.php         # Root template Inertia
│   │   └── auth/                 # Login, forgot-password, etc.
│   └── css/app.css               # TailwindCSS v4
│
├── config/
│   ├── fortify.php               # Autenticacion (home=/dashboard)
│   └── session.php               # Driver: database, 120min lifetime
│
├── database/migrations/          # Todas las tablas creadas
└── .agent/                       # Contexto para agentes AI
    ├── SYSTEM_CONTEXT.md         # ESTE ARCHIVO
    └── artifacts/
        └── IMPLEMENTATION_PLAN.md
```

---

## COMPONENTES CLAVE

### Autenticacion (Laravel Fortify)
- **Login:** Vista Blade en `resources/views/auth/login.blade.php`
- **Dashboard:** Vue/Inertia en `resources/js/Pages/Dashboard.vue`
- **Logout:** POST a `/logout` via `router.post(route('logout'))`
- **Redireccion post-login:** `/dashboard`
- **Sesion:** Database driver, 120 minutos

### Modelos Principales
| Modelo | Ubicacion | Estado |
|--------|-----------|--------|
| User | `app/Models/User.php` | Completo con roles y 2FA |
| Quote | `app/Models/Quote.php` | Completo con scopes |
| Customer | `app/Models/Customer.php` | Completo |
| Contact | `app/Models/Contact.php` | Completo |
| Insurer | `app/Models/Insurer.php` | Completo |

### Enums de Dominio
| Enum | Valores |
|------|---------|
| UserRole | SUPER_ADMIN(100), ADMIN(80), MANAGER(60), OPERATOR(40), VIEWER(20) |
| QuoteStatus | DRAFT, SENT, CONCRETED, ISSUED, REJECTED |
| QuoteType | NEW, RENEWAL |
| PaymentFrequency | ANNUAL, BIANNUAL, QUARTERLY, MONTHLY |
| CustomerType | PHYSICAL, MORAL |

### Rutas Activas
| Metodo | Ruta | Controlador | Middleware |
|--------|------|-------------|------------|
| GET | / | redirect to /dashboard or /login | - |
| GET | /dashboard | DashboardController@index | auth, verified |
| POST | /login | Fortify | - |
| POST | /logout | Fortify | auth |

---

## COLORES Y BRANDING

| Color | Hex | Uso |
|-------|-----|-----|
| Primario (Burgundy) | #7B2D3B | Botones, sidebar, headers |
| Primario Oscuro | #3D1520 | Gradientes, hover |
| Dorado (Secondary) | #C7A172 | Acentos, iconos activos |
| Dorado Claro | #E8D5B7 | Highlights |

**Font:** Plus Jakarta Sans (Google Fonts)

---

## CREDENCIALES DE DESARROLLO

| Usuario | Email | Password | Rol |
|---------|-------|----------|-----|
| Super Admin | admin@cotizador.com | password | super_admin |
| Operador | operador@cotizador.com | password | operator |

---

## ESTADO DE IMPLEMENTACION

### Completado
- [x] Autenticacion con Fortify + 2FA support
- [x] Sistema de roles (Spatie Permission)
- [x] Activity Log en modelos (Spatie Activity Log)
- [x] Modelos de dominio completos
- [x] Migraciones y seeders
- [x] Dashboard con metricas basicas
- [x] Layout responsive con sidebar
- [x] Login premium estilo SaaS

### Completado Hoy (2026-01-15)
- [x] Activity Log de sistema (login/logout para auditoria)
- [x] Seccion de actividad reciente en Dashboard
- [x] Archivo de contexto unificado para agentes AI

### Pendiente
- [ ] CRUD Cotizaciones
- [ ] CRUD Clientes
- [ ] CRUD Aseguradoras
- [ ] Wizard de cotizacion
- [ ] Generacion PDF
- [ ] Reportes
- [ ] Tests automatizados

---

## PROBLEMAS CONOCIDOS

| Problema | Estado | Solucion |
|----------|--------|----------|
| Logout no registra actividad | RESUELTO | Listener en AppServiceProvider |
| Dashboard sin activity log visual | RESUELTO | Seccion agregada en Dashboard.vue |

---

## REGLAS PARA AGENTES AI

### ANTES de modificar codigo:
1. Leer `PROJECT_GUIDELINES.md`
2. Verificar que el cambio sigue la arquitectura hexagonal
3. No instalar paquetes sin aprobacion
4. No modificar archivos de configuracion sin informar

### DESPUES de modificar codigo:
1. Registrar cambio en la seccion ACTIVITY LOG de este archivo
2. Actualizar estado de implementacion si aplica
3. Verificar que el build funciona: `npm run build`

### PROHIBICIONES
- NO crear nuevas tablas sin aprobacion
- NO cambiar estructura de arquitectura
- NO usar libreriras UI diferentes (no Bootstrap, no Material UI)
- NO hacer cambios visuales sin mockup/aprobacion
- NO hardcodear valores

---

## ACTIVITY LOG DE AGENTES

> Formato: [FECHA HORA] AGENTE: ACCION | ARCHIVOS | RESULTADO

### 2026-01-15

| Hora | Agente | Accion | Archivos Modificados | Resultado |
|------|--------|--------|---------------------|-----------|
| 10:40 | Gemini-Pro | Config UI colores | app.blade.php | OK |
| 10:45 | Gemini-Pro | Accessor User | User.php | OK |
| 10:50 | Gemini-Pro | Auth redirect | fortify.php | OK |
| 11:00 | Gemini-Pro | Rediseno Blade | app.blade.php, dashboard.blade.php | OBSOLETO |
| 11:23 | Gemini-Pro | Migracion a Vue/Inertia | Multiples | OK |
| 11:25 | Gemini-Pro | Install Inertia | composer.json | OK |
| 11:26 | Gemini-Pro | Middleware Inertia | HandleInertiaRequests.php | OK |
| 11:28 | Gemini-Pro | Config Frontend | app.js, app.blade.php | OK |
| 11:29 | Gemini-Pro | Componentes Vue | AppLayout.vue, Dashboard.vue | OK |
| 11:30 | Gemini-Pro | Update Controller | DashboardController.php | OK |
| 11:32 | Gemini-Pro | Install Ziggy | composer.json | OK |
| 11:35 | Gemini-Pro | Ziggy Frontend | package.json | OK |
| 11:37 | Gemini-Pro | Fix Ziggy Import | app.js | OK (error propio) |
| 11:42 | Gemini-Pro | Fix Vite Build | AppLayout.vue | OK (error propio) |
| 11:46 | Gemini-Pro | Build Success | - | OK |
| 11:51 | Gemini-Pro | Fix White Screen | cache clear | OK |
| 11:58 | Gemini-Pro | Fix Vite Alias | vite.config.js | OK (error propio) |
| 12:05 | Gemini-Pro | POST-MORTEM | - | Leccion aprendida |
| 12:15 | Gemini-Pro | Fix Ziggy Crash | AppLayout.vue | OK (rutas comentadas) |
| 12:20 | Gemini-Pro | Fix Logo | AppLayout.vue | OK |
| -- | -- | -- | -- | -- |
| 13:00 | Claude-Opus | Analisis sistema | - | Diagnostico completo |
| 13:05 | Claude-Opus | Crear SYSTEM_CONTEXT | .agent/SYSTEM_CONTEXT.md | OK |
| 13:10 | Claude-Opus | Eliminar logs corruptos | -.agent/*.md | OK |
| 13:15 | Claude-Opus | Implementar logout log | AppServiceProvider.php | OK |
| 13:20 | Claude-Opus | Activity Log en Dashboard | DashboardController.php | OK |
| 13:25 | Claude-Opus | UI Activity Log | Dashboard.vue | OK |
| 13:30 | Claude-Opus | Build verificado | npm run build | OK |

### ERRORES COMETIDOS POR AGENTES (POST-MORTEM)

| Fecha | Agente | Error | Causa Raiz | Leccion |
|-------|--------|-------|------------|---------|
| 2026-01-15 11:58 | Gemini-Pro | Pantalla blanca | Alias @ no configurado en vite antes de usarlo | Verificar config antes de codificar |
| 2026-01-15 12:15 | Gemini-Pro | Crash Vue | Rutas inexistentes en navegacion | Comentar rutas no implementadas |

---

## COMANDOS UTILES

```bash
# Desarrollo
npm run dev                    # Vite dev server
php artisan serve              # Laravel server
npm run build                  # Build produccion

# Base de datos
php artisan migrate:fresh --seed  # Reset con datos

# Cache
php artisan optimize:clear     # Limpiar todos los caches

# Verificar estado
php artisan route:list         # Ver rutas
php artisan tinker             # Consola interactiva
```

---

## CONTACTO Y SOPORTE

- **Proyecto:** CONOCE Seguros y Fianzas
- **Repositorio:** Local (c:\laragon\www\cotizador-autos)
- **URL Local:** http://127.0.0.1:8000

---

> **Ultima actualizacion:** 2026-01-15 13:30 por Claude-Opus
