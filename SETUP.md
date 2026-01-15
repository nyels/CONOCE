# 🚀 Guía de Instalación - Cotizador de Autos

## Requisitos Previos

- **Laragon** con PostgreSQL habilitado
- **PHP 8.2+** con extensión `pdo_pgsql`
- **Node.js 18+** y npm
- **Composer 2.x**

## Paso 1: Crear la Base de Datos

Si aún no has creado la base de datos:

```sql
CREATE DATABASE cotizador_autos;
```

## Paso 2: Instalar Dependencias de PHP

Abre la **terminal de Laragon** (importante: usar la terminal de Laragon, no PowerShell/CMD directamente) y ejecuta:

```bash
cd c:\xampp\htdocs\laravel\cotizador-autos
composer install
```

## Paso 3: Ejecutar Migraciones

```bash
php artisan migrate:fresh --seed
```

Esto creará:
- Tablas del sistema
- Roles y permisos
- Usuario administrador de prueba
- 8 aseguradoras con configuración financiera
- 32 estados de México

## Paso 4: Instalar Dependencias de Frontend

```bash
npm install
```

## Paso 5: Compilar Assets

Para desarrollo:
```bash
npm run dev
```

Para producción:
```bash
npm run build
```

## Paso 6: Iniciar el Servidor

```bash
php artisan serve
```

O usa el servidor de Laragon directamente.

---

## 🔐 Credenciales de Acceso

### Super Administrador
- **Email:** admin@cotizador.com
- **Password:** password

### Operador Demo
- **Email:** operador@cotizador.com
- **Password:** password

---

## 📋 Lo que se ha Implementado

### ✅ Backend (Laravel 12)

**Arquitectura Hexagonal:**
- `src/Domain/` - Capa de dominio pura
  - Enums: UserRole, QuoteStatus, QuoteType, PaymentFrequency, CoveragePackage, CustomerType, ContactType
  - Value Objects: Money, Email, RFC, PhoneNumber
  - Services: PremiumCalculatorService

**Modelos Eloquent:**
- User (con roles, permisos y auditoría)
- Contact (intermediarios: agentes, subagentes)
- Customer (clientes/prospectos)
- Insurer (aseguradoras)
- InsurerFinancialSetting (configuración financiera)
- Quote (cotizaciones)
- QuoteOption (opciones de cotización)

**Sistema de Autenticación:**
- Laravel Fortify configurado
- Rate limiting para login
- Logging de actividad
- Soporte para 2FA

**Permisos (Spatie):**
- Roles: super_admin, admin, manager, operator, viewer
- Permisos granulares por módulo

**Auditoría:**
- Spatie Activity Log integrado en todos los modelos

### ✅ Frontend

**Vistas de Autenticación:**
- Login (glassmorphism design)
- Forgot Password
- Reset Password

**Layouts:**
- `layouts/auth.blade.php` - Layout para autenticación
- `layouts/app.blade.php` - Layout principal con sidebar

**Dashboard:**
- Estadísticas de cotizaciones
- Cotizaciones recientes
- Accesos rápidos

### ⏳ Pendiente de Implementar

- [ ] CRUD de Cotizaciones (Vue 3 Components)
- [ ] CRUD de Clientes
- [ ] CRUD de Aseguradoras
- [ ] Wizard de nueva cotización
- [ ] Generación de PDFs
- [ ] Reportes y estadísticas
- [ ] API REST completa
- [ ] Tests automatizados

---

## 🗂️ Estructura del Proyecto

```
cotizador-autos/
├── app/
│   ├── Http/Controllers/
│   │   └── DashboardController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Contact.php
│   │   ├── Customer.php
│   │   ├── Insurer.php
│   │   ├── InsurerFinancialSetting.php
│   │   ├── Quote.php
│   │   ├── QuoteOption.php
│   │   └── Traits/
│   │       └── HasFolio.php
│   └── Providers/
│       └── FortifyServiceProvider.php
│
├── src/
│   └── Domain/
│       ├── Shared/
│       │   ├── Enums/UserRole.php
│       │   └── ValueObjects/
│       │       ├── Money.php
│       │       ├── Email.php
│       │       ├── RFC.php
│       │       └── PhoneNumber.php
│       ├── Quote/
│       │   ├── Enums/
│       │   │   ├── QuoteStatus.php
│       │   │   ├── QuoteType.php
│       │   │   ├── PaymentFrequency.php
│       │   │   └── CoveragePackage.php
│       │   └── Services/
│       │       └── PremiumCalculatorService.php
│       ├── Customer/Enums/CustomerType.php
│       └── Contact/Enums/ContactType.php
│
├── database/
│   ├── migrations/
│   │   ├── 2026_01_15_081958_create_permission_tables.php
│   │   ├── 2026_01_15_082001_create_activity_log_table.php
│   │   ├── 2026_01_15_090000_create_contacts_table.php
│   │   ├── 2026_01_15_090100_create_customers_table_v2.php
│   │   ├── 2026_01_15_090200_create_insurers_tables_v2.php
│   │   ├── 2026_01_15_090300_create_quotes_tables_v2.php
│   │   ├── 2026_01_15_090400_create_settings_tables.php
│   │   └── 2026_01_15_090500_update_users_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RolesAndPermissionsSeeder.php
│       └── InitialDataSeeder.php
│
└── resources/views/
    ├── auth/
    │   ├── login.blade.php
    │   ├── forgot-password.blade.php
    │   └── reset-password.blade.php
    ├── layouts/
    │   ├── auth.blade.php
    │   └── app.blade.php
    └── dashboard.blade.php
```
