# Plan de Implementación: Roles, Personal y Usuarios

## Análisis del Sistema Actual

### Estado Actual de Roles y Permisos

| Componente | Estado | Descripción |
|------------|--------|-------------|
| **Spatie Permission** | Instalado pero NO usado | Tablas creadas, trait en User, pero roles vienen de Enum |
| **UserRole Enum** | En uso | 5 roles hardcodeados: super_admin, admin, manager, operator, viewer |
| **Modelo User** | Funcional | Tiene `role` como enum string, con métodos de autorización |
| **Permisos granulares** | No implementado | Solo hay verificaciones por rol, no por permiso individual |

### Roles Actuales (Enum)

```
super_admin (nivel 100) → Acceso total
admin       (nivel 80)  → Gestión completa menos super_admin
manager     (nivel 60)  → Gestión de equipo y operaciones
operator    (nivel 40)  → Operaciones diarias (cotizaciones)
viewer      (nivel 20)  → Solo lectura
```

---

## Guía Recomendada: Spatie Laravel Permission

### ¿Por qué Spatie Permission?

1. **Ya está instalado** - Solo requiere configuración
2. **Estándar de la industria** - Más de 50M de descargas
3. **Flexible** - Soporta roles Y permisos granulares
4. **Compatible** - Laravel 12, PHP 8.3+
5. **Caché integrado** - Performance optimizado
6. **Blade directives** - `@role`, `@permission`, `@hasanyrole`

### Estrategia Recomendada: Híbrido

**Mantener** el Enum `UserRole` para compatibilidad y simplicidad en la UI.
**Usar** Spatie para:
- Permisos granulares (ej: `quotes.create`, `quotes.delete`)
- Control de acceso a módulos específicos
- Middleware de rutas

---

## Arquitectura Propuesta

### 1. Estructura de Tablas

```
┌─────────────────────────────────────────────────────────────────┐
│                        TABLAS NUEVAS                            │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────┐       ┌─────────────┐       ┌─────────────┐   │
│  │  positions  │       │   staff     │       │   users     │   │
│  │  (puestos)  │◄──────│ (personal)  │◄──────│ (usuarios)  │   │
│  ├─────────────┤       ├─────────────┤       ├─────────────┤   │
│  │ id          │       │ id          │       │ id          │   │
│  │ name        │       │ first_name  │       │ username    │   │
│  │ is_active   │       │ last_name_p │       │ password    │   │
│  │ sort_order  │       │ last_name_m │       │ role        │   │
│  └─────────────┘       │ position_id │       │ staff_id    │   │
│                        │ emails[]    │       │ is_active   │   │
│                        │ phone       │       └─────────────┘   │
│                        │ phone_ext   │                         │
│                        │ mobile      │                         │
│                        │ is_active   │                         │
│                        └─────────────┘                         │
│                                                                 │
│  ┌─────────────────┐                                           │
│  │  staff_emails   │  (tabla pivote para múltiples emails)     │
│  ├─────────────────┤                                           │
│  │ id              │                                           │
│  │ staff_id        │                                           │
│  │ email           │                                           │
│  │ is_primary      │                                           │
│  └─────────────────┘                                           │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 2. Relaciones

```
Position (1) ──────► (N) Staff
Staff    (1) ──────► (N) StaffEmail
Staff    (1) ◄────── (0..1) User (opcional)
```

### 3. Roles y Acceso por Módulo

```
┌──────────────────────────────────────────────────────────────────────────────┐
│                           MATRIZ DE ACCESO                                    │
├──────────────────┬──────────────┬──────────────┬──────────────┬─────────────┤
│ Módulo           │ super_admin  │ admin        │ operator     │ viewer      │
├──────────────────┼──────────────┼──────────────┼──────────────┼─────────────┤
│ Dashboard        │ Completo     │ Completo     │ Solo propio  │ Solo propio │
│ Cotizaciones     │ CRUD + Anular│ CRUD + Anular│ CRUD propio  │ Ver propio  │
│ Clientes         │ CRUD todas   │ CRUD todas   │ CRUD propio  │ Ver propio  │
│ Contactos        │ CRUD todas   │ CRUD todas   │ CRUD propio  │ Ver propio  │
│ Personal         │ CRUD         │ CRUD         │ ❌           │ ❌          │
│ Usuarios         │ CRUD         │ CRUD (no SA) │ ❌           │ ❌          │
│ Catálogos Admin  │ CRUD         │ CRUD         │ ❌           │ ❌          │
│ Auditoría        │ Ver          │ Ver          │ ❌           │ ❌          │
└──────────────────┴──────────────┴──────────────┴──────────────┴─────────────┘
```

---

## Plan de Implementación - Fases

### FASE 1: Catálogo de Puestos (Posiciones)
**Prioridad:** Alta (requisito para Personal)

**Tabla `positions`:**
| Campo | Tipo | Validación |
|-------|------|------------|
| id | bigint PK | auto |
| name | varchar(100) | required, único, regex letras/espacios/puntos |
| description | varchar(255) | nullable |
| is_active | boolean | default true |
| sort_order | tinyint | default 0 |
| created_at | timestamp | auto |
| updated_at | timestamp | auto |

**Archivos a crear:**
- `database/migrations/xxxx_create_positions_table.php`
- `app/Models/Position.php`
- `app/Http/Controllers/Admin/PositionController.php`
- `app/Http/Requests/StorePositionRequest.php`
- `app/Http/Requests/UpdatePositionRequest.php`
- `resources/js/Pages/Admin/Positions/Index.vue`

---

### FASE 2: CRUD de Personal (Staff)
**Prioridad:** Alta

**Tabla `staff`:**
| Campo | Tipo | Validación |
|-------|------|------------|
| id | bigint PK | auto |
| first_name | varchar(100) | required, regex solo letras/acentos/espacios |
| last_name_paternal | varchar(100) | required, regex solo letras |
| last_name_maternal | varchar(100) | nullable, regex solo letras |
| position_id | FK positions | required, exists |
| phone | varchar(20) | nullable, 10 dígitos |
| phone_extension | varchar(10) | nullable, solo números |
| mobile | varchar(20) | nullable, 10 dígitos |
| is_active | boolean | default true |
| created_by | FK users | auto |
| created_at | timestamp | auto |
| updated_at | timestamp | auto |
| deleted_at | timestamp | soft delete |

**Tabla `staff_emails`:**
| Campo | Tipo | Validación |
|-------|------|------------|
| id | bigint PK | auto |
| staff_id | FK staff | required |
| email | varchar(100) | required, email válido, único |
| is_primary | boolean | default false |
| created_at | timestamp | auto |

**Archivos a crear:**
- `database/migrations/xxxx_create_staff_table.php`
- `database/migrations/xxxx_create_staff_emails_table.php`
- `app/Models/Staff.php`
- `app/Models/StaffEmail.php`
- `app/Http/Controllers/Admin/StaffController.php`
- `app/Http/Requests/StoreStaffRequest.php`
- `app/Http/Requests/UpdateStaffRequest.php`
- `resources/js/Pages/Admin/Staff/Index.vue`

**Componente especial:**
- Campo de emails múltiples con botón `+` para agregar más

---

### FASE 3: Refactorización del CRUD de Usuarios
**Prioridad:** Alta

**Modificar tabla `users`:**
| Campo | Tipo | Cambio |
|-------|------|--------|
| staff_id | FK staff | AGREGAR (nullable) |
| username | varchar(50) | AGREGAR (único, para login) |

**Seguridad de Contraseña (Nivel Bancario):**
```php
// Requisitos mínimos:
- Longitud mínima: 12 caracteres
- Al menos 1 mayúscula
- Al menos 1 minúscula
- Al menos 1 número
- Al menos 1 carácter especial (!@#$%^&*()_+-=[]{}|;:,.<>?)
- NO puede contener el username
- NO puede ser igual a contraseñas anteriores (historial de 5)
- Hash: bcrypt con cost 12 (o Argon2id)
- Expiración: Forzar cambio cada 90 días (opcional)
```

**Archivos a modificar:**
- `database/migrations/xxxx_add_staff_id_to_users_table.php`
- `app/Models/User.php` (agregar relación staff)
- `app/Http/Controllers/Admin/UserController.php`
- `app/Http/Requests/StoreUserRequest.php`
- `app/Http/Requests/UpdateUserRequest.php`
- `app/Rules/StrongPassword.php` (nueva regla)
- `resources/js/Pages/Admin/Users/Index.vue`

---

### FASE 4: Implementar Spatie Permission para Control de Acceso
**Prioridad:** Media-Alta

**Permisos a crear:**
```php
// Módulo Cotizaciones
'quotes.view_own'      // Ver solo propias
'quotes.view_all'      // Ver todas
'quotes.create'        // Crear
'quotes.edit'          // Editar
'quotes.delete'        // Eliminar
'quotes.annul'         // Anular

// Módulo Clientes
'customers.view_own'
'customers.view_all'
'customers.create'
'customers.edit'
'customers.delete'

// Módulo Contactos
'contacts.view_own'
'contacts.view_all'
'contacts.create'
'contacts.edit'
'contacts.delete'

// Módulo Administración
'admin.users'          // CRUD usuarios
'admin.staff'          // CRUD personal
'admin.catalogs'       // CRUD catálogos
'admin.audit'          // Ver auditoría
```

**Asignación automática por rol:**
```php
'super_admin' => ['*']  // Todos los permisos
'admin'       => ['quotes.*', 'customers.*', 'contacts.*', 'admin.users', 'admin.staff', 'admin.catalogs', 'admin.audit']
'manager'     => ['quotes.*', 'customers.*', 'contacts.*']
'operator'    => ['quotes.view_own', 'quotes.create', 'quotes.edit', 'customers.view_own', 'customers.create', 'customers.edit', 'contacts.view_own', 'contacts.create', 'contacts.edit']
'viewer'      => ['quotes.view_own', 'customers.view_own', 'contacts.view_own']
```

**Archivos a crear/modificar:**
- `database/seeders/PermissionSeeder.php`
- `app/Http/Middleware/CheckPermission.php`
- Modificar rutas con middleware de permisos

---

### FASE 5: Dashboard Diferenciado por Rol
**Prioridad:** Media

**Dashboard para super_admin/admin:**
- Estadísticas globales
- Todas las cotizaciones
- Todos los clientes
- Métricas de equipo

**Dashboard para operator/viewer:**
- Solo sus cotizaciones
- Solo sus clientes
- Sus métricas personales

---

## Validaciones Estándar

### Backend (Laravel)

```php
// Nombres (solo letras, acentos, espacios, puntos)
'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.]+$/'

// Teléfono (exactamente 10 dígitos)
'regex:/^\d{10}$/'

// Extensión (solo números, máx 10)
'regex:/^\d{1,10}$/'

// Email
'email:rfc,dns'

// Contraseña fuerte
new StrongPassword() // Regla personalizada
```

### Frontend (Vue)

```javascript
// Mismo patrón regex que backend
const validateName = (value) => {
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.]+$/.test(value)) {
        return 'Solo letras, espacios y puntos';
    }
    return null;
};
```

---

## Estructura de Archivos Final

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── PositionController.php      [NUEVO]
│   │       ├── StaffController.php         [NUEVO]
│   │       └── UserController.php          [MODIFICAR]
│   ├── Requests/
│   │   ├── StorePositionRequest.php        [NUEVO]
│   │   ├── UpdatePositionRequest.php       [NUEVO]
│   │   ├── StoreStaffRequest.php           [NUEVO]
│   │   ├── UpdateStaffRequest.php          [NUEVO]
│   │   ├── StoreUserRequest.php            [MODIFICAR]
│   │   └── UpdateUserRequest.php           [MODIFICAR]
│   └── Middleware/
│       └── CheckPermission.php             [NUEVO]
├── Models/
│   ├── Position.php                        [NUEVO]
│   ├── Staff.php                           [NUEVO]
│   ├── StaffEmail.php                      [NUEVO]
│   └── User.php                            [MODIFICAR]
└── Rules/
    └── StrongPassword.php                  [NUEVO]

database/
├── migrations/
│   ├── xxxx_create_positions_table.php     [NUEVO]
│   ├── xxxx_create_staff_table.php         [NUEVO]
│   ├── xxxx_create_staff_emails_table.php  [NUEVO]
│   └── xxxx_add_staff_id_to_users.php      [NUEVO]
└── seeders/
    ├── PositionSeeder.php                  [NUEVO]
    └── PermissionSeeder.php                [NUEVO]

resources/js/Pages/Admin/
├── Positions/
│   └── Index.vue                           [NUEVO]
├── Staff/
│   └── Index.vue                           [NUEVO]
└── Users/
    └── Index.vue                           [MODIFICAR]
```

---

## Orden de Implementación Recomendado

1. **Crear tabla y CRUD de Puestos (positions)** - Base para Personal
2. **Crear tabla y CRUD de Personal (staff)** - Con emails múltiples
3. **Modificar tabla Users** - Agregar staff_id, username
4. **Refactorizar CRUD de Usuarios** - Con contraseña fuerte y enlace a personal
5. **Configurar Spatie Permissions** - Permisos granulares
6. **Implementar middleware de permisos** - Control de acceso a rutas
7. **Ajustar vistas por rol** - Dashboard diferenciado
8. **Filtrar datos por usuario** - Cotizaciones, clientes propios

---

## Seguridad de Contraseña - Especificación Detallada

### Regla `StrongPassword`

```php
class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Mínimo 12 caracteres
        if (strlen($value) < 12) {
            $fail('La contraseña debe tener al menos 12 caracteres.');
            return;
        }

        // Al menos una mayúscula
        if (!preg_match('/[A-Z]/', $value)) {
            $fail('Debe contener al menos una letra mayúscula.');
            return;
        }

        // Al menos una minúscula
        if (!preg_match('/[a-z]/', $value)) {
            $fail('Debe contener al menos una letra minúscula.');
            return;
        }

        // Al menos un número
        if (!preg_match('/\d/', $value)) {
            $fail('Debe contener al menos un número.');
            return;
        }

        // Al menos un carácter especial
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{}|;:,.<>?]/', $value)) {
            $fail('Debe contener al menos un carácter especial.');
            return;
        }

        // No puede contener espacios
        if (preg_match('/\s/', $value)) {
            $fail('No puede contener espacios.');
            return;
        }
    }
}
```

### Hash Configuration (config/hashing.php)

```php
'bcrypt' => [
    'rounds' => 12,  // Default es 10, aumentar a 12 para mayor seguridad
],

// O usar Argon2id (más seguro)
'driver' => 'argon2id',
'argon' => [
    'memory' => 65536,
    'threads' => 4,
    'time' => 4,
],
```

---

## Decisiones Confirmadas por el Usuario

1. **Puestos iniciales:** Director, Gerente, Administrativo, Contabilidad

2. **Login:** Se usará **username** (no email)

3. **Expiración de contraseña:** Sí, cada **90 días**

4. **Historial de contraseñas:** Sí, **no repetir las últimas 5**

5. **Bloqueo de cuenta:** No requerido

6. **Roles:**
   - `super_admin` → Full access (control total)
   - `admin` → Acceso limitado a ciertas configuraciones

---

## Recursos y Documentación

### Spatie Laravel Permission
- Documentación oficial: https://spatie.be/docs/laravel-permission/v6/introduction
- GitHub: https://github.com/spatie/laravel-permission

### Mejores Prácticas de Seguridad de Contraseñas
- OWASP Password Guidelines
- NIST SP 800-63B Digital Identity Guidelines

### Laravel Security
- Laravel Security Best Practices
- Fortify Two-Factor Authentication (ya instalado)

---

## Notas Finales

Este plan está diseñado para:
1. **Mantener compatibilidad** con el código existente
2. **Escalar gradualmente** sin romper funcionalidad
3. **Seguir el patrón DDD** ya establecido en el proyecto
4. **Reutilizar componentes** Vue existentes (CrudTable, CrudModal, etc.)
5. **Implementar seguridad nivel bancario** en contraseñas

El usuario debe aprobar este plan antes de proceder con la implementación.
