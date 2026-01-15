# 📋 PROJECT GUIDELINES - CONOCE Cotizador de Autos

> **IMPORTANTE:** Este archivo es la fuente de verdad del proyecto. Cualquier IA o desarrollador DEBE leer y seguir estas directrices ANTES de hacer cualquier cambio.

---

## 📜 CHANGELOG (Historial de Cambios)

> Ordenado de forma **DESCENDENTE** - Los cambios más recientes van ARRIBA

### 2026-01-15 10:26 CST - Directrices de Seguridad, Performance y Código Limpio
**Autor:** Claude AI + Usuario  
**Cambios:**
- Agregadas reglas estrictas de seguridad basadas en OWASP Top 10 y CVEs conocidos
- Implementación de principio "Defense in Depth" (seguridad en frontend Y backend)
- Reglas de cifrado de contraseñas (Bcrypt/Argon2id) y datos sensibles
- Mejores prácticas de Laravel: Eloquent eficiente, Scopes, Repositories
- Principios SOLID para código limpio y escalable
- Reglas de performance: Eager Loading, Cache, Pagination
- Estándar de commits Conventional Commits
- Fuentes oficiales de seguridad (NIST, OWASP, CVE, Snyk)

**Razón:** Establecer directrices profesionales y empresariales que cualquier IA o desarrollador debe seguir estrictamente

---

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

### 🎯 PRINCIPIOS FUNDAMENTALES

1. **ANALIZAR CONTEXTO PRIMERO:** Antes de cualquier acción, entender completamente el sistema existente
2. **NO REINVENTAR LA RUEDA:** Usar las herramientas y patrones ya establecidos en el proyecto
3. **CÓDIGO LIMPIO:** Seguir principios SOLID, DRY, KISS
4. **ESCALABILIDAD:** Todo código debe pensar en crecimiento futuro
5. **SEGURIDAD PRIMERO:** Nunca comprometer la seguridad por conveniencia

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
11. **NO hardcodear valores** que deberían ser configurables
12. **NO crear código duplicado** - reutilizar lo existente
13. **NO ignorar errores** - manejarlos apropiadamente
14. **NO comprometer seguridad** por velocidad de desarrollo

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

1. **Leer PROJECT_GUIDELINES.md completo**
2. **Entender la arquitectura actual** - revisar estructura de carpetas
3. **Analizar código relacionado** - ver cómo se ha hecho algo similar
4. **Verificar si la tarea requiere cambios estructurales** - si es así, informar
5. **Si hay dudas, PREGUNTAR** - nunca asumir
6. **Explicar DÓNDE y POR QUÉ** se creará algo nuevo

### 🔄 DESPUÉS DE CADA TAREA

1. Actualizar la sección CHANGELOG de este archivo
2. Documentar decisiones técnicas tomadas
3. Informar al usuario qué archivos fueron modificados
4. Verificar que no se rompió funcionalidad existente
5. Hacer commit siguiendo el estándar Conventional Commits

---

## 🔒 SEGURIDAD ROBUSTA (OWASP + CVE)

> Basado en OWASP Top 10 y últimas vulnerabilidades conocidas de Laravel

### Vulnerabilidades Conocidas a Prevenir

| CVE | Vulnerabilidad | Prevención |
|-----|----------------|------------|
| CVE-2024-52301 | Manipulación de environment via query strings | Mantener `register_argc_argv=Off` en php.ini |
| CVE-2024-40075 | XXE (XML External Entity) | No procesar XML de fuentes no confiables |
| CVE-2024-13918/19 | XSS en páginas de error | `APP_DEBUG=false` en producción |
| CVE-2025-27515 | Bypass de validación de archivos | Validar archivos individualmente, no con wildcards |
| CVE-2025-54068 | RCE en Livewire | Mantener Livewire actualizado (>=3.6.4) |

### Reglas de Seguridad Obligatorias

```php
// ✅ CORRECTO - Consultas seguras con Eloquent
$users = User::where('email', $email)->first();

// ❌ INCORRECTO - SQL Injection vulnerable
$users = DB::select("SELECT * FROM users WHERE email = '$email'"); // NUNCA

// ✅ CORRECTO - Si necesitas raw query, usar bindings
$users = DB::select("SELECT * FROM users WHERE email = ?", [$email]);
```

```php
// ✅ CORRECTO - Validación estricta
$validated = $request->validate([
    'email' => ['required', 'email:rfc,dns', 'max:255'],
    'amount' => ['required', 'numeric', 'min:0', 'max:999999999'],
    'file' => ['required', 'file', 'mimes:pdf,jpg,png', 'max:10240'],
]);

// ❌ INCORRECTO - Validación débil
$email = $request->input('email'); // Sin validar
```

```php
// ✅ CORRECTO - Prevenir Mass Assignment
class Quote extends Model {
    protected $fillable = ['customer_id', 'type', 'status']; // Solo campos permitidos
    // O usar $guarded = ['id', 'created_at', 'updated_at'];
}

// ❌ INCORRECTO - Vulnerable a mass assignment
Quote::create($request->all()); // NUNCA sin $fillable definido
```

### 🛡️ PRINCIPIO: NUNCA CONFIAR EN EL FRONTEND (pero protegerlo también)

> **Defense in Depth:** Implementar seguridad en TODAS las capas. El backend es la última línea de defensa, pero el frontend debe hacer su parte.

#### Seguridad en Frontend (Vue 3 / JavaScript)

```javascript
// ✅ CORRECTO - Sanitizar inputs antes de mostrar
import DOMPurify from 'dompurify';
const safeHtml = DOMPurify.sanitize(userInput);

// ✅ CORRECTO - Validar en frontend para UX (no para seguridad)
const validateEmail = (email) => {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
};

// ✅ CORRECTO - No almacenar datos sensibles en localStorage
// Usar httpOnly cookies para tokens de sesión

// ✅ CORRECTO - Usar HTTPS siempre
// ✅ CORRECTO - Implementar CSP (Content Security Policy)
// ✅ CORRECTO - Evitar eval() y innerHTML con datos de usuario

// ❌ INCORRECTO - Exponer claves API en frontend
const API_KEY = 'sk-12345'; // NUNCA en código frontend

// ✅ CORRECTO - Las API keys van en backend, frontend usa tokens de sesión
```

```html
<!-- ✅ CORRECTO - Vue escapa automáticamente con {{ }} -->
<p>{{ userInput }}</p> <!-- Seguro contra XSS -->

<!-- ❌ INCORRECTO - v-html con datos de usuario -->
<div v-html="userInput"></div> <!-- Vulnerable a XSS -->

<!-- ✅ CORRECTO - v-html solo con contenido sanitizado -->
<div v-html="sanitizedContent"></div>
```

#### Seguridad en Backend (Laravel) - Última Línea de Defensa

> **TODO dato que viene del cliente (browser, app, API) es potencialmente malicioso**

```php
// ❌ INCORRECTO - Confiar en datos del frontend
public function updatePrice(Request $request)
{
    $quote->update(['total' => $request->total]); // El usuario puede manipular el precio!
}

// ✅ CORRECTO - Recalcular SIEMPRE en backend
public function updatePrice(Request $request, PremiumCalculatorService $calculator)
{
    $total = $calculator->calculate($quote); // Recalcular, no confiar en frontend
    $quote->update(['total' => $total->cents()]);
}
```

```php
// ❌ INCORRECTO - Confiar en IDs del frontend para autorización
public function show(Request $request)
{
    $quote = Quote::find($request->quote_id); // Cualquiera puede ver cualquier cotización!
    return view('quotes.show', compact('quote'));
}

// ✅ CORRECTO - Siempre verificar autorización
public function show(Quote $quote)
{
    $this->authorize('view', $quote); // Policy verifica que el usuario puede ver esta cotización
    return view('quotes.show', compact('quote'));
}
```

```php
// ❌ INCORRECTO - Validar solo en frontend
// JavaScript: if (email.includes('@')) submitForm();

// ✅ CORRECTO - Validar siempre en backend (frontend es UX, no seguridad)
$validated = $request->validate([
    'email' => ['required', 'email:rfc,dns', 'max:255'],
]);
```

### 🔐 CIFRADO Y HASHING DE CONTRASEÑAS

> **Laravel usa Bcrypt por defecto (configurable a Argon2id)**

```php
// ✅ CORRECTO - Hashear contraseñas con Hash facade
use Illuminate\Support\Facades\Hash;

$user->password = Hash::make($request->password);
$user->save();

// ✅ CORRECTO - Verificar contraseña
if (Hash::check($request->password, $user->password)) {
    // Contraseña correcta
}

// ✅ CORRECTO - Rehashear si es necesario (cambio de costo)
if (Hash::needsRehash($user->password)) {
    $user->password = Hash::make($request->password);
    $user->save();
}
```

```php
// Configuración en config/hashing.php
'driver' => 'bcrypt',
'bcrypt' => [
    'rounds' => env('BCRYPT_ROUNDS', 12), // Aumentar para más seguridad
    'verify' => true,
],

// O usar Argon2id (más seguro, más lento)
'driver' => 'argon2id',
'argon' => [
    'memory' => 65536,  // 64MB
    'threads' => 4,
    'time' => 4,
],
```

```php
// ❌ NUNCA HACER ESTO
$user->password = md5($password);     // INSEGURO
$user->password = sha1($password);    // INSEGURO
$user->password = $password;          // TEXTO PLANO - CRÍTICO
```

### 🔒 CIFRADO DE DATOS SENSIBLES

```php
use Illuminate\Support\Facades\Crypt;

// Cifrar datos sensibles (documentos, tokens, etc.)
$encrypted = Crypt::encryptString($rfcCompleto);
$decrypted = Crypt::decryptString($encrypted);

// En el modelo - Cifrado automático
protected $casts = [
    'rfc' => 'encrypted',
    'curp' => 'encrypted',
    'bank_account' => 'encrypted',
];
```### Checklist de Seguridad

- [ ] **CSRF:** Todas las formas usan `@csrf`
- [ ] **XSS:** Todo output usa `{{ }}` en Blade (escapa automáticamente)
- [ ] **SQL Injection:** Usar Eloquent o prepared statements
- [ ] **Authentication:** Usar Laravel Fortify/Sanctum
- [ ] **Authorization:** Verificar permisos con `$this->authorize()` o middleware
- [ ] **File Upload:** Validar tipo, tamaño y almacenar fuera de public/
- [ ] **Passwords:** Usar `Hash::make()` (Bcrypt/Argon2)
- [ ] **Sensitive Data:** Nunca loguear passwords, tokens o datos sensibles
- [ ] **Environment:** `APP_DEBUG=false` y `APP_ENV=production` en producción
- [ ] **HTTPS:** Forzar HTTPS en producción
- [ ] **Rate Limiting:** Aplicar a login, registro y endpoints sensibles
- [ ] **Headers:** Configurar headers de seguridad (CSP, X-Frame-Options, etc.)

### Fuentes Oficiales de Seguridad

| Recurso | URL | Uso |
|---------|-----|-----|
| OWASP Top 10 | https://owasp.org/Top10/ | Guía de vulnerabilidades web |
| Laravel Security | https://laravel.com/docs/security | Documentación oficial |
| CVE Database | https://cve.mitre.org/ | Base de datos de vulnerabilidades |
| NVD (NIST) | https://nvd.nist.gov/ | Detalles técnicos de CVEs |
| Snyk | https://snyk.io/vuln/ | Vulnerabilidades en paquetes |
| GitHub Security | https://github.com/advisories | Advisories de seguridad |

---

## ⚡ PERFORMANCE Y OPTIMIZACIÓN

### Reglas de Performance

```php
// ✅ CORRECTO - Eager Loading para evitar N+1
$quotes = Quote::with(['customer', 'options', 'agent'])->get();

// ❌ INCORRECTO - N+1 Query Problem
$quotes = Quote::all();
foreach ($quotes as $quote) {
    echo $quote->customer->name; // Cada iteración hace una query
}
```

```php
// ✅ CORRECTO - Chunking para grandes datasets
Quote::chunk(1000, function ($quotes) {
    foreach ($quotes as $quote) {
        // Procesar
    }
});

// ❌ INCORRECTO - Cargar todo en memoria
$quotes = Quote::all(); // Si hay millones de registros, crashea
```

```php
// ✅ CORRECTO - Seleccionar solo columnas necesarias
$names = Customer::select('id', 'name', 'email')->get();

// ❌ INCORRECTO - Traer todas las columnas
$customers = Customer::all(); // Trae todo aunque solo necesites 2 campos
```

```php
// ✅ CORRECTO - Usar caché para datos que no cambian frecuentemente
$states = Cache::remember('mexican_states', 86400, function () {
    return State::all();
});

// ❌ INCORRECTO - Consultar siempre la BD
$states = State::all(); // Para cada request
```

### Checklist de Performance

- [ ] **Queries:** Usar `DB::enableQueryLog()` para detectar N+1
- [ ] **Indexes:** Crear índices en columnas de WHERE, JOIN, ORDER BY
- [ ] **Caché:** Cachear datos estáticos (catálogos, configuraciones)
- [ ] **Pagination:** Siempre paginar listados grandes
- [ ] **Lazy Loading:** Evitar cargar relaciones no necesarias
- [ ] **Assets:** Minificar CSS/JS en producción
- [ ] **Images:** Optimizar y usar tamaños apropiados

---

## 🧹 CÓDIGO LIMPIO Y ESCALABLE

### Principios SOLID

| Principio | Descripción | Ejemplo |
|-----------|-------------|---------|
| **S**ingle Responsibility | Una clase, una responsabilidad | `PremiumCalculatorService` solo calcula primas |
| **O**pen/Closed | Abierto a extensión, cerrado a modificación | Usar interfaces y herencia |
| **L**iskov Substitution | Subclases deben ser sustituibles | Enums con métodos polimórficos |
| **I**nterface Segregation | Interfaces pequeñas y específicas | No crear interfaces gigantes |
| **D**ependency Inversion | Depender de abstracciones | Inyectar servicios, no instanciar |

### Estructura de Código

```php
// ✅ CORRECTO - Métodos pequeños y descriptivos
public function calculateTotalPremium(Quote $quote): Money
{
    $netPremium = $this->calculateNetPremium($quote);
    $policyFee = $this->getPolicyFee($quote->insurer);
    $surcharge = $this->calculateSurcharge($netPremium, $quote->payment_frequency);
    
    return $netPremium->add($policyFee)->add($surcharge);
}

// ❌ INCORRECTO - Método de 200 líneas que hace todo
public function processQuote($data) {
    // 200 líneas de código mezclando validación, cálculo, persistencia, emails...
}
```

### Convenciones de Nombrado

| Tipo | Convención | Ejemplo |
|------|------------|---------|
| Clases | PascalCase, sustantivos | `QuoteService`, `CustomerRepository` |
| Métodos | camelCase, verbos | `calculatePremium()`, `findByEmail()` |
| Variables | camelCase, descriptivas | `$totalAmount`, `$activeCustomers` |
| Constantes | UPPER_SNAKE_CASE | `MAX_QUOTE_OPTIONS`, `DEFAULT_IVA_RATE` |
| Tablas BD | snake_case, plural | `quote_options`, `insurer_financial_settings` |
| Columnas BD | snake_case, singular | `created_at`, `customer_id` |

---

## 🚀 MEJORES PRÁCTICAS LARAVEL (Official Best Practices)

> Basado en la documentación oficial de Laravel y patrones recomendados

### Eloquent ORM - Consultas Eficientes

```php
// ✅ CORRECTO - Eager Loading con restricciones
$quotes = Quote::with(['customer' => function ($query) {
    $query->select('id', 'name', 'email');
}, 'options' => function ($query) {
    $query->where('is_selected', true);
}])->get();

// ✅ CORRECTO - Lazy Eager Loading cuando ya tienes la colección
$quotes = Quote::all();
$quotes->load('customer', 'options'); // Solo si lo necesitas después

// ✅ CORRECTO - Contar relaciones sin cargarlas
$quotes = Quote::withCount('options')->get();
// Acceso: $quote->options_count

// ✅ CORRECTO - Exists check eficiente
if (Quote::where('customer_id', $customerId)->exists()) {
    // Existe
}

// ❌ INCORRECTO - Cargar todo para verificar existencia
if (Quote::where('customer_id', $customerId)->first()) { // Carga datos innecesarios
}
```

### Query Scopes Reutilizables

```php
// En el modelo Quote.php
class Quote extends Model
{
    // Scope local
    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            QuoteStatus::DRAFT,
            QuoteStatus::SENT,
        ]);
    }
    
    public function scopeByAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }
    
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}

// Uso encadenado
$quotes = Quote::active()
    ->byAgent($userId)
    ->thisMonth()
    ->with('customer')
    ->paginate(15);
```

### Relaciones Eficientes

```php
// ✅ CORRECTO - Definir relaciones con índices foráneos
public function customer(): BelongsTo
{
    return $this->belongsTo(Customer::class);
}

public function options(): HasMany
{
    return $this->hasMany(QuoteOption::class)->orderBy('option_number');
}

// ✅ CORRECTO - Relación con condiciones por defecto
public function selectedOption(): HasOne
{
    return $this->hasOne(QuoteOption::class)->where('is_selected', true);
}

// ✅ CORRECTO - Relación polimórfica cuando aplique
public function activities(): MorphMany
{
    return $this->morphMany(Activity::class, 'subject');
}
```

### Accessors y Mutators (Laravel 9+)

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

class Quote extends Model
{
    // Accessor + Mutator combinado
    protected function vehicleData(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
    
    // Accessor computado
    protected function fullVehicleName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->vehicle_data['brand']} {$this->vehicle_data['model']} {$this->vehicle_data['year']}"
        );
    }
    
    // Castings automáticos
    protected $casts = [
        'status' => QuoteStatus::class,
        'type' => QuoteType::class,
        'package_type' => CoveragePackage::class,
        'sent_at' => 'datetime',
        'quote_valid_until' => 'date',
    ];
}
```

### Colecciones Eficientes

```php
// ✅ CORRECTO - Usar métodos de colección
$totalPremium = $quotes->sum('total_premium_cents');
$byStatus = $quotes->groupBy('status');
$activeQuotes = $quotes->filter(fn ($q) => $q->status->isActive());

// ✅ CORRECTO - Pluck para arrays simples
$customerIds = $quotes->pluck('customer_id')->unique();
$customerNames = $quotes->pluck('customer.name', 'id'); // key => value

// ✅ CORRECTO - Map para transformar
$summary = $quotes->map(fn ($q) => [
    'folio' => $q->folio,
    'customer' => $q->customer->name,
    'total' => $q->total_premium_cents / 100,
]);

// ❌ INCORRECTO - foreach cuando hay método de colección
$total = 0;
foreach ($quotes as $quote) {
    $total += $quote->total_premium_cents; // Usar ->sum() mejor
}
```

### Jobs y Queues para Tareas Pesadas

```php
// ✅ CORRECTO - Enviar email en background
SendQuoteEmail::dispatch($quote)->onQueue('emails');

// ✅ CORRECTO - Generar PDF en background
GenerateQuotePdf::dispatch($quote)
    ->delay(now()->addSeconds(5))
    ->onQueue('pdfs');

// ✅ CORRECTO - Batch de operaciones
Bus::batch([
    new SendQuoteEmail($quote),
    new GenerateQuotePdf($quote),
    new LogQuoteActivity($quote),
])->dispatch();
```

### Validación Avanzada

```php
// ✅ CORRECTO - Form Request con reglas complejas
class StoreQuoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'type' => ['required', Rule::enum(QuoteType::class)],
            'vehicle_data' => ['required', 'array'],
            'vehicle_data.brand' => ['required', 'string', 'max:50'],
            'vehicle_data.model' => ['required', 'string', 'max:50'],
            'vehicle_data.year' => ['required', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'options' => ['required', 'array', 'min:1', 'max:5'],
            'options.*.insurer_id' => ['required', 'exists:insurers,id'],
            'options.*.net_premium' => ['required', 'numeric', 'min:0'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Debe seleccionar un cliente',
            'customer_id.exists' => 'El cliente seleccionado no existe',
        ];
    }
}
```

### Service Pattern

```php
// ✅ CORRECTO - Lógica de negocio en servicios
class QuoteService
{
    public function __construct(
        private PremiumCalculatorService $calculator,
        private FolioGeneratorService $folioGenerator,
    ) {}
    
    public function create(array $data): Quote
    {
        return DB::transaction(function () use ($data) {
            $quote = Quote::create([
                'folio' => $this->folioGenerator->generate('COT'),
                'customer_id' => $data['customer_id'],
                'type' => $data['type'],
                // ...
            ]);
            
            foreach ($data['options'] as $optionData) {
                $calculation = $this->calculator->calculate(/* ... */);
                $quote->options()->create([/* ... */]);
            }
            
            return $quote;
        });
    }
}

// En el controlador
class QuoteController extends Controller
{
    public function store(StoreQuoteRequest $request, QuoteService $service)
    {
        $quote = $service->create($request->validated());
        
        return redirect()->route('quotes.show', $quote);
    }
}
```

### Repository Pattern (Opcional)

```php
// Interface
interface QuoteRepositoryInterface
{
    public function findById(int $id): ?Quote;
    public function findByFolio(string $folio): ?Quote;
    public function getByCustomer(int $customerId): Collection;
    public function create(array $data): Quote;
}

// Implementación
class EloquentQuoteRepository implements QuoteRepositoryInterface
{
    public function findById(int $id): ?Quote
    {
        return Quote::with(['customer', 'options'])->find($id);
    }
    
    public function getByCustomer(int $customerId): Collection
    {
        return Quote::where('customer_id', $customerId)
            ->with('options')
            ->orderByDesc('created_at')
            ->get();
    }
}
```---

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

## 📝 ESTÁNDAR DE COMMITS (Conventional Commits)

> **Formato:** `tipo(scope): descripción breve en español`

### Tipos de Commit (en inglés)

| Tipo | Uso |
|------|-----|
| `feat` | Nueva funcionalidad |
| `fix` | Corrección de errores |
| `docs` | Cambios en documentación |
| `style` | Formato, espacios, puntos y comas (sin cambio de código) |
| `refactor` | Refactorización de código existente |
| `perf` | Mejoras de rendimiento |
| `test` | Agregar o corregir tests |
| `chore` | Tareas de mantenimiento, dependencias |
| `ci` | Cambios en CI/CD |
| `build` | Cambios en sistema de build |

### Ejemplos

```bash
# ✅ CORRECTO
feat(auth): implementar login con 2FA
fix(quotes): corregir cálculo de prima con recargos
docs(readme): actualizar instrucciones de instalación
refactor(models): extraer lógica de cálculo a servicio
chore(deps): actualizar Laravel a versión 12.47

# ❌ INCORRECTO
arreglé el bug del login
cambios varios
WIP
.
```

### Cuerpo del Commit (opcional)

Para commits importantes, agregar cuerpo explicativo en español:

```bash
feat(dashboard): agregar widget de estadísticas mensuales

- Se agregó card con gráfica de cotizaciones por mes
- Integración con Chart.js para visualización
- Filtros por rango de fechas
- Responsive para móviles

Closes #123
```

---

## 📚 REFERENCIAS

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [Spatie Activity Log](https://spatie.be/docs/laravel-activitylog)
- [Vue 3 Documentation](https://vuejs.org/)

---

> **Última actualización:** 2026-01-15 10:13 CST  
> **Mantenido por:** Equipo de Desarrollo CONOCE
