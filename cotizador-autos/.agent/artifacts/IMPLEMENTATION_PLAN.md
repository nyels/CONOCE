# 🏗️ Plan de Implementación Empresarial
## Sistema de Cotizador de Seguros Automotrices

**Fecha de Creación:** 2026-01-15
**Versión:** 1.0.0
**Estado:** En Desarrollo

---

## 📋 Resumen Ejecutivo

Este documento detalla el plan de implementación para transformar el sistema de cotización de seguros automotrices en una aplicación empresarial de nivel bancario, utilizando **Laravel 12 con Arquitectura Hexagonal**, **Vue 3 + Pinia**, y las mejores prácticas de desarrollo de software.

---

## 🎯 Objetivos del Proyecto

### Objetivos Principales
1. ✅ **Seguridad Nivel Bancario**: Autenticación robusta, encriptación, auditoría completa
2. ✅ **Arquitectura Escalable**: Hexagonal/Clean Architecture para facilitar mantenimiento y expansión
3. ✅ **Trazabilidad Completa**: Sistema de logs, eventos, auditoría y monitoreo
4. ✅ **Experiencia de Usuario Premium**: UI moderna, intuitiva y responsiva
5. ✅ **Alta Disponibilidad**: Diseño para soportar carga empresarial

### Criterios de Éxito
- [ ] Tiempo de respuesta < 200ms en operaciones críticas
- [ ] Cobertura de tests > 80%
- [ ] Cero vulnerabilidades críticas en auditoría de seguridad
- [ ] Documentación completa de API

---

## 🏛️ Arquitectura del Sistema

### Stack Tecnológico

| Capa | Tecnología | Versión |
|------|------------|---------|
| **Backend** | Laravel | 12.x |
| **Frontend** | Vue 3 + Inertia.js | 3.5+ |
| **State Management** | Pinia | 2.x |
| **UI Framework** | TailwindCSS + HeadlessUI | 4.x |
| **Auth** | Laravel Sanctum + Fortify | 4.x |
| **Database** | MySQL 8.0 / PostgreSQL 15 | - |
| **Cache** | Redis | 7.x |
| **Queue** | Laravel Horizon + Redis | - |
| **PDF** | DomPDF / Snappy | 3.x |
| **Testing** | PHPUnit + Pest + Vitest | - |
| **Monitoring** | Laravel Telescope + Sentry | - |

### Estructura de Directorios Hexagonal

```
📁 cotizador-autos/
├── 📁 app/                          # Capa de Infraestructura Laravel
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/          # Controllers API/Web
│   │   ├── 📁 Middleware/           # Seguridad, Rate Limiting, etc.
│   │   ├── 📁 Requests/             # Form Requests con validación
│   │   └── 📁 Resources/            # API Resources (Transformers)
│   ├── 📁 Models/                   # Eloquent Models (Persistence)
│   ├── 📁 Providers/                # Service Providers
│   ├── 📁 Repositories/             # Implementaciones de Repositorios
│   ├── 📁 Events/                   # Eventos de Dominio
│   ├── 📁 Listeners/                # Handlers de Eventos
│   ├── 📁 Jobs/                     # Background Jobs
│   └── 📁 Observers/                # Model Observers (Auditoría)
│
├── 📁 src/                          # Capa de Dominio (Pura)
│   ├── 📁 Domain/                   # Bounded Contexts
│   │   ├── 📁 Shared/               # Componentes compartidos
│   │   │   ├── 📁 ValueObjects/     # Money, Email, etc.
│   │   │   ├── 📁 Enums/            # Enums compartidos
│   │   │   └── 📁 Contracts/        # Interfaces base
│   │   │
│   │   ├── 📁 User/                 # Gestión de Usuarios
│   │   │   ├── 📁 Models/
│   │   │   ├── 📁 Enums/
│   │   │   ├── 📁 ValueObjects/
│   │   │   └── 📁 Contracts/
│   │   │
│   │   ├── 📁 Contact/              # Intermediarios (Agentes)
│   │   │   ├── 📁 Models/
│   │   │   ├── 📁 Enums/
│   │   │   └── 📁 Contracts/
│   │   │
│   │   ├── 📁 Customer/             # Prospectos/Clientes
│   │   │   ├── 📁 Models/
│   │   │   ├── 📁 Enums/
│   │   │   ├── 📁 ValueObjects/
│   │   │   └── 📁 Contracts/
│   │   │
│   │   ├── 📁 Insurer/              # Aseguradoras
│   │   │   ├── 📁 Models/
│   │   │   ├── 📁 ValueObjects/
│   │   │   ├── 📁 Services/
│   │   │   └── 📁 Contracts/
│   │   │
│   │   ├── 📁 Quote/                # Cotizaciones
│   │   │   ├── 📁 Models/
│   │   │   ├── 📁 Enums/
│   │   │   ├── 📁 ValueObjects/
│   │   │   ├── 📁 Services/
│   │   │   └── 📁 Contracts/
│   │   │
│   │   └── 📁 Policy/               # Pólizas Emitidas
│   │       ├── 📁 Models/
│   │       ├── 📁 ValueObjects/
│   │       └── 📁 Contracts/
│   │
│   └── 📁 Application/              # Casos de Uso
│       ├── 📁 Quote/
│       │   ├── CreateQuoteUseCase.php
│       │   ├── CalculatePremiumUseCase.php
│       │   ├── SendQuoteUseCase.php
│       │   ├── ConcludeQuoteUseCase.php
│       │   └── RejectQuoteUseCase.php
│       ├── 📁 Customer/
│       ├── 📁 Insurer/
│       └── 📁 Policy/
│
├── 📁 resources/
│   ├── 📁 js/
│   │   ├── 📁 Pages/                # Páginas Inertia
│   │   ├── 📁 Components/           # Componentes Vue
│   │   ├── 📁 Composables/          # Lógica reutilizable
│   │   ├── 📁 Stores/               # Pinia stores
│   │   └── 📁 Layouts/              # Layouts de página
│   └── 📁 css/                      # Estilos
│
├── 📁 tests/
│   ├── 📁 Unit/                     # Tests unitarios
│   ├── 📁 Feature/                  # Tests de integración
│   └── 📁 E2E/                      # Tests end-to-end
│
└── 📁 docs/                         # Documentación
    ├── 📁 api/                      # Docs de API
    └── 📁 architecture/             # Decisiones arquitectónicas
```

---

## 📊 Modelo de Datos Completo

### Diagrama Entidad-Relación

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              MODELO DE DATOS                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌──────────────┐     ┌──────────────────┐     ┌──────────────────┐        │
│  │    USERS     │────▶│    CONTACTS      │────▶│    CUSTOMERS     │        │
│  │  (Operadores)│     │  (Intermediarios)│     │ (Prospectos/Cli) │        │
│  └──────────────┘     └──────────────────┘     └────────┬─────────┘        │
│         │                                               │                   │
│         │                                               │                   │
│         │          ┌───────────────────────────────────▼─────────┐         │
│         │          │                 QUOTES                       │         │
│         │          │  (Cotizaciones: DRAFT/SENT/CONCRETED/REJ)   │         │
│         └─────────▶│  - vehicle_data (JSON)                      │         │
│                    │  - type (NEW/RENEWAL)                        │         │
│                    │  - folio (único)                             │         │
│                    └──────────────────────┬──────────────────────┘         │
│                                           │                                 │
│  ┌──────────────┐                        │                                 │
│  │   INSURERS   │◀───────────────────────┼──────────────────────┐         │
│  │(Aseguradoras)│                        │                       │         │
│  └──────┬───────┘                        ▼                       │         │
│         │                    ┌───────────────────────┐           │         │
│         │                    │    QUOTE_OPTIONS      │───────────┘         │
│         │                    │ (1-5 opciones por cot)│                     │
│         │                    │ - coverages (JSON)    │                     │
│         │                    │ - net_premium         │                     │
│         │                    │ - policy_fee          │                     │
│         │                    │ - total_premium       │                     │
│         │                    └───────────────────────┘                     │
│         │                                                                   │
│         ▼                                                                   │
│  ┌─────────────────────────┐                                               │
│  │ INSURER_FINANCIAL_      │                                               │
│  │ SETTINGS                │  (Configuración de costos)                    │
│  │ - policy_fee            │                                               │
│  │ - surcharge_*           │                                               │
│  └─────────────────────────┘                                               │
│                                                                             │
│  ┌──────────────────────────────────────────────────────────────────┐      │
│  │                    MÓDULO DE AUDITORÍA                           │      │
│  ├──────────────────────────────────────────────────────────────────┤      │
│  │  ACTIVITY_LOGS  │  AUDIT_TRAILS  │  SETTINGS_HISTORY            │      │
│  └──────────────────────────────────────────────────────────────────┘      │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Tablas Principales

| Tabla | Propósito | Campos Clave |
|-------|-----------|--------------|
| `users` | Operadores del sistema | email, password, role, active |
| `contacts` | Agentes/Subagentes/Empleados | type, name, commission_rate |
| `customers` | Prospectos y clientes | type (PHYSICAL/MORAL), name, rfc |
| `insurers` | Catálogo de aseguradoras | name, logo, is_active |
| `insurer_financial_settings` | Config. financiera por aseguradora | policy_fee, surcharges |
| `quotes` | Cotizaciones principales | uuid, folio, status, vehicle_data |
| `quote_options` | Opciones de cada cotización | coverages (JSON), premiums |
| `policies` | Pólizas emitidas | policy_number, validity |
| `activity_logs` | Auditoría de actividad | causer, subject, properties |
| `settings` | Configuraciones del sistema | key, value, type |

---

## 🚀 Fases de Implementación

### Fase 1: Fundamentos (Semana 1-2)
**Estado: ⏳ En Progreso**

- [x] Estructura de proyecto Laravel 12
- [x] Configuración de Vite + Vue 3
- [x] Migraciones básicas
- [ ] Implementar sistema de autenticación completo
- [ ] Configurar Sanctum para APIs
- [ ] Sistema de roles y permisos (Spatie)
- [ ] Middleware de seguridad
- [ ] Configurar logging y monitoreo

### Fase 2: Dominio Core (Semana 3-4)
**Estado: 🔲 Pendiente**

- [ ] Value Objects (Money, Email, RFC, etc.)
- [ ] Enums de dominio completos
- [ ] Modelos de dominio puros
- [ ] Interfaces de repositorios
- [ ] Servicios de dominio (PremiumCalculator)
- [ ] Eventos de dominio

### Fase 3: Capa de Aplicación (Semana 5-6)
**Estado: 🔲 Pendiente**

- [ ] Use Cases de Cotizaciones
- [ ] Use Cases de Clientes
- [ ] Use Cases de Aseguradoras
- [ ] DTOs y Request/Response objects
- [ ] Validadores de negocio

### Fase 4: Infraestructura (Semana 7-8)
**Estado: 🔲 Pendiente**

- [ ] Repositorios Eloquent
- [ ] Servicios externos (PDF, Email)
- [ ] Sistema de colas
- [ ] Cache strategy
- [ ] API Controllers
- [ ] Form Requests

### Fase 5: Frontend Vue 3 (Semana 9-12)
**Estado: 🔲 Pendiente**

- [ ] Layout principal y navegación
- [ ] Dashboard con estadísticas
- [ ] CRUD de Clientes
- [ ] CRUD de Aseguradoras
- [ ] Wizard de Cotizaciones
- [ ] Visualizador/Editor de Opciones
- [ ] Generación y envío de PDFs
- [ ] Reportes y exportaciones

### Fase 6: QA y Optimización (Semana 13-14)
**Estado: 🔲 Pendiente**

- [ ] Tests unitarios (>80% coverage)
- [ ] Tests de integración
- [ ] Tests E2E con Playwright
- [ ] Optimización de queries
- [ ] Security audit
- [ ] Performance tuning
- [ ] Documentación API (Swagger)

---

## 🔐 Especificaciones de Seguridad

### Autenticación y Autorización

| Componente | Implementación |
|------------|----------------|
| **Auth Provider** | Laravel Fortify + Sanctum |
| **Hash Algorithm** | bcrypt (cost 12) / Argon2id |
| **2FA** | TOTP (Google Authenticator) |
| **Session** | DB/Redis con rotación |
| **API Tokens** | Sanctum con abilities |

### Roles del Sistema

```php
enum UserRole: string {
    case SUPER_ADMIN = 'super_admin';    // Todo + Config sistema
    case ADMIN = 'admin';                 // Gestión completa
    case MANAGER = 'manager';             // Supervisión + Reportes
    case OPERATOR = 'operator';           // Cotizaciones propias
    case VIEWER = 'viewer';               // Solo lectura
}
```

### Permisos Granulares

| Módulo | Permisos |
|--------|----------|
| **quotes** | view, create, edit, delete, send, conclude, reject, annul |
| **customers** | view, create, edit, delete, export |
| **insurers** | view, create, edit, delete, configure |
| **users** | view, create, edit, delete, assign_roles |
| **reports** | view, export, schedule |
| **settings** | view, update |

### Auditoría y Trazabilidad

```php
// Todos los modelos auditables registrarán:
[
    'user_id',           // Quién
    'action',            // Qué (create, update, delete)
    'model_type',        // Dónde (Quote, Customer, etc.)
    'model_id',          // ID del registro
    'old_values',        // Valores anteriores (JSON)
    'new_values',        // Valores nuevos (JSON)
    'ip_address',        // Desde dónde
    'user_agent',        // Con qué navegador
    'created_at',        // Cuándo
]
```

---

## 📱 Especificaciones de UI/UX

### Diseño General

| Aspecto | Especificación |
|---------|----------------|
| **Estilo** | Moderno, minimalista, profesional |
| **Colores** | Paleta corporativa con modo oscuro |
| **Tipografía** | Inter (principal), Roboto Mono (datos) |
| **Iconos** | Heroicons + Phosphor Icons |
| **Animaciones** | Transiciones suaves (150-300ms) |
| **Responsive** | Mobile-first, breakpoints standard |

### Componentes Clave

1. **Dashboard**
   - Métricas en tiempo real (cotizaciones del día/mes)
   - Gráficas de conversión
   - Alertas y notificaciones
   - Accesos rápidos

2. **Wizard de Cotizaciones** (Multi-step)
   - Paso 1: Tipo y Cliente
   - Paso 2: Datos del Vehículo
   - Paso 3: Selección de Coberturas
   - Paso 4: Opciones de Aseguradoras
   - Paso 5: Resumen y Generación

3. **Tabla de Cotizaciones**
   - Búsqueda avanzada
   - Filtros por estado/fecha/aseguradora
   - Acciones rápidas
   - Exportación masiva

4. **Calculadora de Primas**
   - Cálculo en tiempo real
   - Comparativa visual entre opciones
   - Desglose detallado

---

## 📈 Métricas y Monitoreo

### KPIs del Sistema

| Métrica | Objetivo | Herramienta |
|---------|----------|-------------|
| Response Time | < 200ms | Laravel Telescope |
| Error Rate | < 0.1% | Sentry |
| Availability | 99.9% | UptimeRobot |
| Query Time | < 50ms | MySQL Slow Query Log |

### Logging

```php
// Niveles de log configurados
'channels' => [
    'daily' => [...],                    // Logs diarios
    'security' => [...],                 // Eventos de seguridad
    'business' => [...],                 // Eventos de negocio
    'performance' => [...],              // Métricas de performance
    'sentry' => [...],                   // Errores críticos
]
```

---

## 🗄️ Próximos Pasos Inmediatos

### Esta Sesión
1. ✅ Crear plan de implementación
2. 🔄 Configurar paquetes empresariales
3. 🔄 Implementar sistema de autenticación completo
4. 🔄 Crear sistema de roles y permisos
5. 🔄 Implementar auditoría completa

### Siguiente Sesión
1. Completar Domain Layer (Value Objects, Enums)
2. Implementar Use Cases
3. Crear repositorios

---

## 📚 Referencias

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Vue 3 Composition API](https://vuejs.org/guide/introduction.html)
- [Hexagonal Architecture](https://alistair.cockburn.us/hexagonal-architecture/)
- [Domain-Driven Design](https://martinfowler.com/tags/domain%20driven%20design.html)
- [OWASP Security Guidelines](https://owasp.org/www-project-web-security-testing-guide/)
