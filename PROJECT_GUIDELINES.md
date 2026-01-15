# 📋 PROJECT GUIDELINES - CONOCE Cotizador de Autos

> **IMPORTANTE:** Este archivo es la fuente de verdad del proyecto. Cualquier IA o desarrollador DEBE leer y seguir estas directrices ANTES de hacer cualquier cambio.

---

## 📜 CHANGELOG (Historial de Cambios)

> Ordenado de forma **DESCENDENTE** - Los cambios más recientes van ARRIBA

### 2026-01-15 10:10 CST - Implementación de Login Premium SaaS
**Autor:** Claude AI + Usuario  
**Cambios:**
- Creación de vista de login con diseño split-screen premium
- Integración del logo de CONOCE Seguros y Fianzas
- Colores institucionales: marrón (#7B2D3B) y dorado (#C7A172)
- Diseño responsive para móviles y tablets

**Razón:** El usuario solicitó un diseño nuevo, fresco, audaz, profesional premium tipo SaaS

---

### 2026-01-15 09:45 CST - Migración Inicial del Sistema
**Autor:** Claude AI + Usuario  
**Cambios:**
- Migración del proyecto de `c:\xampp\htdocs\laravel\cotizador-autos` a `c:\laragon\www\cotizador-autos`
- Configuración de PostgreSQL como base de datos
- Habilitación de extensiones pdo_pgsql en PHP
- Ejecución exitosa de migraciones y seeders
- Creación de usuarios de prueba (admin@cotizador.com / password)
- Carga de 8 aseguradoras con configuración financiera
- Carga de 32 estados de México

**Razón:** Centralizar el proyecto en Laragon para mejor manejo de PostgreSQL

---

### 2026-01-15 08:30 CST - Implementación de Arquitectura Empresarial
**Autor:** Claude AI + Usuario  
**Cambios:**
- Implementación de Arquitectura Hexagonal (Domain-Driven Design)
- Creación de Enums de dominio (UserRole, QuoteStatus, QuoteType, etc.)
- Creación de Value Objects (Money, Email, RFC, PhoneNumber)
- Creación de modelos Eloquent con auditoría (User, Quote, Customer, etc.)
- Integración de Spatie Permission para roles y permisos
- Integración de Spatie Activity Log para auditoría
- Configuración de Laravel Fortify para autenticación

**Razón:** El usuario solicitó migrar el sistema legacy a una arquitectura empresarial robusta y escalable

---

## 🧠 REGLAS ESTRICTAS PARA INTELIGENCIA ARTIFICIAL

> **TODA IA QUE TRABAJE EN ESTE PROYECTO DEBE CUMPLIR ESTAS REGLAS SIN EXCEPCIÓN**

### 🚫 PROHIBICIONES ABSOLUTAS

1. **NO crear nuevas tablas de base de datos** sin autorización explícita del usuario
2. **NO cambiar la estructura de arquitectura** (Hexagonal/DDD) establecida
3. **NO instalar nuevos paquetes de composer o npm** sin preguntar primero
4. **NO modificar archivos de configuración** (.env, config/*) sin informar
5. **NO eliminar código existente** que funcione correctamente
6. **NO cambiar el diseño visual** sin mostrar mockup o pedir aprobación
7. **NO usar librerías de UI** diferentes a las establecidas (no Bootstrap, no Material UI)
8. **NO crear endpoints API** sin documentarlos
9. **NO saltarse las validaciones** de datos
10. **NO usar consultas SQL raw** cuando Eloquent pueda hacerlo

### ✅ OBLIGACIONES

1. **SIEMPRE preguntar** antes de cambios estructurales mayores
2. **SIEMPRE documentar** el código con PHPDoc/JSDoc
3. **SIEMPRE seguir** los patrones establecidos en el proyecto
4. **SIEMPRE usar** los Value Objects para datos sensibles (Money, Email, RFC)
5. **SIEMPRE usar** los Enums definidos en lugar de strings hardcodeados
6. **SIEMPRE actualizar** este archivo cuando se hagan cambios importantes
7. **SIEMPRE mantener** la consistencia visual con el branding de CONOCE
8. **SIEMPRE verificar** que el código funcione antes de reportar como terminado
9. **SIEMPRE usar** transacciones de BD para operaciones críticas
10. **SIEMPRE respetar** los permisos y roles del sistema

### 📝 ANTES DE CADA TAREA

1. Leer este archivo completo
2. Entender la arquitectura actual
3. Verificar si la tarea requiere cambios en la estructura
4. Si hay dudas, PREGUNTAR antes de actuar
5. Si se va a crear algo nuevo, explicar DÓNDE y POR QUÉ

### 🔄 DESPUÉS DE CADA TAREA

1. Actualizar la sección CHANGELOG de este archivo
2. Documentar decisiones técnicas tomadas
3. Informar al usuario qué archivos fueron modificados
4. Verificar que no se rompió funcionalidad existente

---

## 🏛️ ARQUITECTURA DEL SISTEMA

### Stack Tecnológico

| Capa | Tecnología | Versión |
|------|------------|---------|
| **Backend** | Laravel | 12.x |
| **Frontend** | Vue 3 + Inertia.js | 3.x |
| **Base de Datos** | PostgreSQL | 15+ |
| **Autenticación** | Laravel Fortify + Sanctum | |
| **Permisos** | Spatie Laravel Permission | 6.x |
| **Auditoría** | Spatie Laravel Activitylog | 4.x |
| **Estilos** | CSS Vanilla (NO TailwindCSS en vistas Blade) | |
| **Build Tool** | Vite | 7.x |

### Arquitectura Hexagonal

```
src/
├── Domain/                    # 🔵 CAPA DE DOMINIO (Lógica de negocio pura)
│   ├── Shared/
│   │   ├── Enums/            # Enums compartidos (UserRole)
│   │   └── ValueObjects/     # Value Objects (Money, Email, RFC, PhoneNumber)
│   │
│   ├── Quote/
│   │   ├── Enums/            # QuoteStatus, QuoteType, PaymentFrequency, CoveragePackage
│   │   └── Services/         # PremiumCalculatorService
│   │
│   ├── Customer/
│   │   └── Enums/            # CustomerType
│   │
│   └── Contact/
│       └── Enums/            # ContactType
│
app/
├── Models/                   # 🟢 Modelos Eloquent (Infraestructura)
│   ├── User.php
│   ├── Quote.php
│   ├── QuoteOption.php
│   ├── Customer.php
│   ├── Contact.php
│   ├── Insurer.php
│   ├── InsurerFinancialSetting.php
│   └── Traits/
│       └── HasFolio.php
│
├── Http/Controllers/         # 🟡 Controladores HTTP
│   └── DashboardController.php
│
└── Providers/                # Providers de servicio
    └── FortifyServiceProvider.php
```

### Estructura de Base de Datos

| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios del sistema con roles |
| `customers` | Clientes/prospectos |
| `contacts` | Intermediarios (agentes, subagentes) |
| `insurers` | Aseguradoras |
| `insurer_financial_settings` | Configuración financiera de aseguradoras |
| `quotes` | Cotizaciones |
| `quote_options` | Opciones de cada cotización |
| `settings` | Configuraciones del sistema |
| `sequences` | Secuencias para folios |
| `states` | Estados de México |
| `roles` / `permissions` | Roles y permisos (Spatie) |
| `activity_log` | Log de actividad (Spatie) |

---

## 📐 ESTÁNDARES DE CÓDIGO

### PHP / Laravel

```php
// ✅ CORRECTO - Usar Value Objects
$money = Money::fromCents(150000);
$email = Email::fromString('usuario@ejemplo.com');

// ❌ INCORRECTO - Usar tipos primitivos para datos sensibles
$price = 1500.00; // NO para dinero
$email = 'usuario@ejemplo.com'; // NO sin validación
```

```php
// ✅ CORRECTO - Usar Enums
$quote->status = QuoteStatus::SENT;

// ❌ INCORRECTO - Usar strings
$quote->status = 'SENT'; // NO
```

```php
// ✅ CORRECTO - Usar transacciones
DB::transaction(function () {
    // operaciones críticas
});

// ❌ INCORRECTO - Operaciones sin transacción
$quote->save();
$options->save(); // Si falla, queda inconsistente
```

### Nombres de Archivos

| Tipo | Convención | Ejemplo |
|------|------------|---------|
| Modelos | PascalCase | `QuoteOption.php` |
| Migraciones | snake_case con fecha | `2026_01_15_090300_create_quotes_tables_v2.php` |
| Vistas Blade | kebab-case | `forgot-password.blade.php` |
| Componentes Vue | PascalCase | `QuoteForm.vue` |
| CSS | kebab-case | `dashboard-styles.css` |

### Comentarios

```php
/**
 * Calcula la prima total con recargos
 *
 * @param Money $netPremium Prima neta sin recargos
 * @param Insurer $insurer Aseguradora para obtener configuración
 * @param PaymentFrequency $frequency Frecuencia de pago
 * @return PremiumCalculationResult Resultado del cálculo
 */
public function calculate(Money $netPremium, Insurer $insurer, PaymentFrequency $frequency): PremiumCalculationResult
```

---

## ✅ FUNCIONALIDADES DEL SISTEMA

### Implementadas ✅

- [x] Sistema de autenticación (login/logout)
- [x] Roles y permisos (super_admin, admin, manager, operator, viewer)
- [x] Auditoría de acciones (activity log)
- [x] Modelo de usuarios con 2FA support
- [x] Modelos de dominio (Quote, Customer, Contact, Insurer)
- [x] Value Objects (Money, Email, RFC, PhoneNumber)
- [x] Enums de dominio (QuoteStatus, QuoteType, etc.)
- [x] Servicio de cálculo de primas
- [x] Migraciones de base de datos
- [x] Seeders con datos iniciales (aseguradoras, estados)
- [x] Vista de login premium
- [x] Dashboard básico

### Pendientes 🔄

- [ ] CRUD de Cotizaciones
- [ ] CRUD de Clientes
- [ ] CRUD de Contactos/Intermediarios
- [ ] CRUD de Aseguradoras
- [ ] Wizard de nueva cotización
- [ ] Comparador de opciones
- [ ] Generación de PDF de cotización
- [ ] Envío de cotización por email/WhatsApp
- [ ] Reportes y estadísticas
- [ ] API REST documentada
- [ ] Tests automatizados
- [ ] Configuración de catálogos (coberturas, vehículos)

---

## 🎨 BRANDING Y DISEÑO

### Colores Institucionales CONOCE

| Color | Hex | Uso |
|-------|-----|-----|
| Marrón Primario | `#7B2D3B` | Botones principales, headers |
| Marrón Oscuro | `#5A1F2C` | Hover states, gradientes |
| Marrón Muy Oscuro | `#3D1520` | Backgrounds oscuros |
| Dorado/Beige | `#C7A172` | Acentos, highlights |
| Dorado Claro | `#E8D5B7` | Textos destacados |

### Tipografía

- **Principal:** Plus Jakarta Sans (Google Fonts)
- **Fallback:** -apple-system, BlinkMacSystemFont, sans-serif

### Principios de Diseño

1. **Profesional y Premium:** Diseño limpio, espaciado generoso
2. **SaaS Moderno:** Interfaces tipo dashboard, cards, estadísticas
3. **Responsive First:** Diseño que funcione en móvil primero
4. **Consistencia:** Usar componentes reutilizables
5. **Accesibilidad:** Contraste adecuado, etiquetas descriptivas

---

## 📁 UBICACIÓN DEL PROYECTO

```
Ruta principal: c:\laragon\www\cotizador-autos
URL local: http://127.0.0.1:8000
URL Laragon: http://cotizador-autos.test (si está configurado)
```

---

## 🔐 CREDENCIALES DE DESARROLLO

| Usuario | Email | Password | Rol |
|---------|-------|----------|-----|
| Super Admin | admin@cotizador.com | password | super_admin |
| Operador Demo | operador@cotizador.com | password | operator |

---

## 📚 REFERENCIAS

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [Spatie Activity Log](https://spatie.be/docs/laravel-activitylog)
- [Vue 3 Documentation](https://vuejs.org/)

---

> **Última actualización:** 2026-01-15 10:13 CST  
> **Mantenido por:** Equipo de Desarrollo CONOCE
