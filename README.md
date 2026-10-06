# 📦 Sistema de Inventario

Aplicación web construida con **Laravel 13** (PHP 8.3+), **Tailwind CSS v4** y **Vite 8**. Este documento describe en detalle la estructura de carpetas, el rol de cada archivo y cómo los distintos componentes del framework interactúan entre sí.

---

## 🗂️ Índice

1. [Resumen del Stack Tecnológico](#resumen-del-stack-tecnológico)
2. [Estructura General del Proyecto](#estructura-general-del-proyecto)
3. [Análisis Detallado por Carpeta](#análisis-detallado-por-carpeta)
   - [app/](#app)
   - [bootstrap/](#bootstrap)
   - [config/](#config)
   - [database/](#database)
   - [public/](#public)
   - [resources/](#resources)
   - [routes/](#routes)
   - [storage/](#storage)
   - [tests/](#tests)
   - [vendor/](#vendor)
4. [Archivos Raíz](#archivos-raíz)
5. [Flujo de una Petición HTTP](#flujo-de-una-petición-http)
6. [Base de Datos](#base-de-datos)
7. [Frontend y Assets](#frontend-y-assets)
8. [Comandos Útiles](#comandos-útiles)
9. [Instalación y Puesta en Marcha](#instalación-y-puesta-en-marcha)

---

## Resumen del Stack Tecnológico

| Capa | Tecnología | Versión |
|---|---|---|
| Backend | Laravel Framework | ^13.17 |
| Lenguaje | PHP | ^8.3 |
| Frontend CSS | Tailwind CSS | ^4.0 |
| Bundler | Vite | ^8.0 |
| Plugin Laravel/Vite | laravel-vite-plugin | ^3.1 |
| Base de datos (dev) | SQLite | — |
| Testing | PHPUnit | ^12.5 |
| Fábrica de datos | FakerPHP | ^1.23 |
| REPL interactivo | Laravel Tinker | ^3.0 |

---

## Estructura General del Proyecto

```
sistema-inventario/
├── app/                    # Lógica de negocio (Controllers, Models, Providers)
├── bootstrap/              # Arranque de la aplicación
├── config/                 # Archivos de configuración
├── database/               # Migraciones, factories y seeders
├── public/                 # Punto de entrada HTTP (index.php) y assets compilados
├── resources/              # Vistas Blade, CSS y JS sin compilar
├── routes/                 # Definición de rutas web y consola
├── storage/                # Logs, caché de vistas, sesiones y archivos generados
├── tests/                  # Pruebas unitarias y de integración (Feature)
├── vendor/                 # Dependencias instaladas por Composer (no editar)
├── .env                    # Variables de entorno locales (no versionar)
├── .env.example            # Plantilla de variables de entorno
├── artisan                 # CLI de Laravel
├── composer.json           # Dependencias PHP y scripts de Composer
├── package.json            # Dependencias JS y scripts de npm/Node
├── phpunit.xml             # Configuración de PHPUnit
└── vite.config.js          # Configuración del bundler Vite
```

---

## Análisis Detallado por Carpeta

### `app/`

Corazón de la aplicación. Contiene toda la lógica PHP propia del sistema. Sigue el estándar PSR-4 con el namespace raíz `App\`.

```
app/
├── Http/
│   └── Controllers/
│       └── Controller.php       # Clase base abstracta para todos los controladores
├── Models/
│   └── User.php                 # Modelo Eloquent del usuario autenticable
└── Providers/
    └── AppServiceProvider.php   # Service Provider principal de la aplicación
```

#### `app/Http/Controllers/Controller.php`
Clase abstracta que sirve de base para todos los controladores del proyecto. En este momento está vacía, pero cualquier método o trait compartido entre controladores se añade aquí (por ejemplo, autorización, respuestas estandarizadas).

#### `app/Models/User.php`
Modelo Eloquent que representa a un usuario del sistema. Características clave:
- Extiende `Authenticatable`, habilitando el sistema de autenticación de Laravel.
- Usa los traits `HasFactory` (para factories de prueba) y `Notifiable` (para notificaciones por email/SMS).
- Los atributos rellenables (`name`, `email`, `password`) se declaran con el atributo PHP 8 `#[Fillable]`.
- Los campos ocultos en serialización (`password`, `remember_token`) usan `#[Hidden]`.
- El campo `password` se castea automáticamente a hash con `'password' => 'hashed'`.

#### `app/Providers/AppServiceProvider.php`
Service Provider que se ejecuta durante el arranque de la aplicación. Tiene dos métodos:
- `register()`: registra bindings en el contenedor de servicios (IoC).
- `boot()`: ejecuta código tras registrar todos los providers (ideal para observers, macros, validaciones globales).

> **Cómo escalar esta carpeta:** a medida que el sistema crezca, se añadirán subcarpetas como `app/Http/Requests/`, `app/Http/Middleware/`, `app/Policies/`, `app/Services/`, `app/Repositories/`, etc.

---

### `bootstrap/`

Responsable de inicializar el framework antes de procesar cualquier petición.

```
bootstrap/
├── app.php          # Crea y configura la instancia principal de la aplicación
├── providers.php    # Lista de Service Providers a registrar automáticamente
└── cache/
    ├── packages.php # Caché de paquetes descubiertos automáticamente (generado)
    └── services.php # Caché del mapa de servicios (generado)
```

#### `bootstrap/app.php`
Punto de configuración de la aplicación mediante la API fluida de Laravel 13:
- Define los archivos de rutas (`web.php`, `console.php`).
- Expone el endpoint de salud `/up` (usado por orquestadores y balanceadores de carga).
- Configura el manejo de excepciones: las rutas `api/*` y peticiones que esperan JSON reciben respuestas en formato JSON automáticamente.

#### `bootstrap/providers.php`
Array con los Service Providers que Laravel registra al iniciar. Por defecto incluye `AppServiceProvider`.

#### `bootstrap/cache/`
Archivos PHP generados por `php artisan optimize` o `php artisan package:discover`. Aceleran el arranque al evitar leer `composer.json` en cada petición. **No editar a mano.**

---

### `config/`

Archivos de configuración independientes del entorno. Cada archivo devuelve un array con claves de configuración que se leen con el helper `config('archivo.clave')`.

```
config/
├── app.php          # Nombre de la app, entorno, locale, timezone, providers
├── auth.php         # Guards de autenticación y proveedores de usuarios
├── cache.php        # Almacenes de caché (Redis, Memcached, file, array…)
├── database.php     # Conexiones de base de datos (SQLite, MySQL, PostgreSQL…)
├── filesystems.php  # Discos de almacenamiento de archivos (local, S3…)
├── logging.php      # Canales de logging (stack, single, daily, slack…)
├── mail.php         # Configuración de envío de correos
├── queue.php        # Conexiones y colas de trabajos asíncronos
├── services.php     # Credenciales de servicios externos (Stripe, AWS…)
└── session.php      # Driver y configuración de sesiones
```

Los valores sensibles (contraseñas, keys, hosts) se leen desde el archivo `.env` mediante el helper `env('CLAVE', 'valor_por_defecto')`, manteniendo la configuración separada del código.

---

### `database/`

Todo lo relacionado con la definición y población de la base de datos.

```
database/
├── database.sqlite          # Archivo de base de datos SQLite (entorno local/dev)
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php    # Tablas: users, password_reset_tokens, sessions
│   ├── 0001_01_01_000001_create_cache_table.php    # Tablas para caché en BD
│   └── 0001_01_01_000002_create_jobs_table.php     # Tablas para colas de trabajos
├── factories/
│   └── UserFactory.php      # Genera usuarios falsos con Faker para pruebas
└── seeders/
    └── DatabaseSeeder.php   # Orquesta la inserción de datos iniciales
```

#### `database/migrations/`
Las migraciones son la versión de control de la estructura de la BD. Se ejecutan con `php artisan migrate` y se revierten con `php artisan migrate:rollback`.

La migración `000000_create_users_table.php` crea tres tablas fundamentales:
- `users`: id, name, email (único), email_verified_at, password, remember_token, timestamps.
- `password_reset_tokens`: para el flujo de recuperación de contraseña.
- `sessions`: para almacenar sesiones en BD cuando el driver es `database`.

#### `database/factories/UserFactory.php`
Factory que usa **FakerPHP** para generar datos realistas de prueba:
- `name`: nombre aleatorio.
- `email`: email único y seguro.
- `password`: hasheado de la string `'password'` (se cachea para mayor velocidad).
- Estado `unverified()`: devuelve un usuario con `email_verified_at = null`.

#### `database/seeders/DatabaseSeeder.php`
Por defecto crea un usuario de prueba: `Test User / test@example.com`. Se ejecuta con `php artisan db:seed`.

---

### `public/`

El único directorio que debe ser accesible desde el servidor web (document root). Ningún archivo PHP de lógica de negocio vive aquí.

```
public/
├── index.php    # Punto de entrada HTTP: carga el autoloader y despacha la petición
├── .htaccess    # Reglas de reescritura para Apache (redirige todo a index.php)
├── favicon.ico  # Ícono del navegador
└── robots.txt   # Instrucciones para motores de búsqueda
```

**Flujo:** el servidor web (Nginx/Apache) dirige todas las peticiones a `public/index.php`, que carga el framework y devuelve la respuesta.

Durante el desarrollo con Vite, los assets compilados se sirven directamente por el servidor de Vite. En producción, `npm run build` genera la carpeta `public/build/` con los archivos estáticos finales.

---

### `resources/`

Recursos sin compilar que forman la capa de presentación.

```
resources/
├── css/
│   └── app.css          # Hoja de estilos principal con directivas de Tailwind CSS v4
├── js/
│   └── app.js           # Punto de entrada JavaScript
└── views/
    └── welcome.blade.php # Vista de bienvenida (página de inicio por defecto)
```

#### `resources/css/app.css`
Importa Tailwind CSS v4 y configura fuentes personalizadas:
- Fuente principal: `Instrument Sans` (cargada vía Bunny Fonts).
- Incluye como fuentes de escaneo de clases las vistas de paginación de Laravel y las vistas compiladas en caché.

#### `resources/js/app.js`
Punto de entrada JavaScript. Aquí se importarán librerías JS (Alpine.js, Axios, etc.) a medida que el proyecto lo requiera.

#### `resources/views/`
Contiene las plantillas **Blade** (motor de plantillas de Laravel). Blade permite:
- Herencia de layouts con `@extends` y `@section`.
- Directivas como `@if`, `@foreach`, `@auth`, `@guest`.
- Componentes reutilizables con `<x-component-name />`.
- Inyección de assets con la directiva `@vite`.

La vista `welcome.blade.php` es la página de inicio por defecto, ya estilizada con Tailwind CSS.

---

### `routes/`

Define los puntos de acceso de la aplicación.

```
routes/
├── web.php       # Rutas HTTP para el navegador (con sesión, CSRF, cookies)
└── console.php   # Comandos Artisan personalizados vía closure
```

#### `routes/web.php`
Actualmente define una sola ruta:
```php
Route::get('/', fn() => view('welcome'));
```
Aquí se irán agregando todas las rutas del sistema de inventario: CRUD de productos, categorías, proveedores, movimientos de stock, etc.

#### `routes/console.php`
Permite definir comandos de consola como closures. Por defecto incluye el comando `inspire` que muestra una cita motivacional.

---

### `storage/`

Almacenamiento interno generado por la aplicación en tiempo de ejecución. **No se versiona** (excepto los `.gitignore` de cada subcarpeta).

```
storage/
├── app/
│   └── private/         # Archivos subidos o generados por la aplicación
├── framework/
│   ├── cache/           # Caché de la aplicación (drivers file/array)
│   ├── sessions/        # Sesiones cuando el driver es 'file'
│   ├── testing/         # Almacenamiento aislado para pruebas
│   └── views/           # Vistas Blade compiladas a PHP puro (caché de Blade)
└── logs/
    └── laravel.log      # Log principal de la aplicación
```

- **`framework/views/`**: cuando Blade compila una vista, guarda el PHP resultante aquí. En peticiones posteriores usa este caché en lugar de recompilar.
- **`logs/`**: se configura en `config/logging.php`. Por defecto usa el driver `stack` que escribe en `laravel.log`.

Para regenerar los enlaces simbólicos de storage: `php artisan storage:link`.

---

### `tests/`

Suite de pruebas automatizadas con **PHPUnit 12**.

```
tests/
├── TestCase.php          # Clase base que extiende todos los tests del proyecto
├── Feature/
│   └── ExampleTest.php   # Test de integración de ejemplo
└── Unit/
    └── (vacío por defecto)
```

- **Unit**: pruebas aisladas de clases/métodos individuales, sin levantar el framework completo.
- **Feature**: pruebas de integración que simulan peticiones HTTP reales, verifican respuestas, base de datos, etc.

Configuración en `phpunit.xml`:
- Entorno `testing` con SQLite en memoria (`:memory:`), lo que hace las pruebas muy rápidas.
- Caché, sesiones, colas y correo en modo `array`/`sync` para no afectar servicios externos.

Ejecutar pruebas:
```bash
php artisan test
# o directamente
vendor/bin/phpunit
```

---

### `vendor/`

Dependencias de PHP instaladas por **Composer**. **Nunca se edita manualmente ni se versiona** (está en `.gitignore`). Se regenera con `composer install`.

Paquetes principales presentes:

| Paquete | Rol |
|---|---|
| `laravel/framework` | El framework completo (routing, Eloquent, Blade, Auth…) |
| `laravel/tinker` | REPL interactivo para explorar la app |
| `symfony/*` | Componentes HTTP, Console, Finder usados internamente por Laravel |
| `fakerphp/faker` | Generación de datos falsos para testing |
| `phpunit/phpunit` | Framework de pruebas |
| `mockery/mockery` | Mocking de objetos en pruebas |
| `nunomaduro/collision` | Reportes de errores mejorados en consola |
| `laravel/pint` | Formateador de código PHP (estilo PSR-12) |
| `laravel/pail` | Visor de logs en tiempo real en terminal |
| `monolog/monolog` | Sistema de logging que usa Laravel internamente |
| `guzzlehttp/guzzle` | Cliente HTTP para llamadas a APIs externas |

---

## Archivos Raíz

| Archivo | Función |
|---|---|
| `artisan` | CLI de Laravel. Punto de entrada para `php artisan <comando>` |
| `composer.json` | Declara dependencias PHP, namespaces PSR-4 y scripts de Composer |
| `composer.lock` | Versiones exactas instaladas. Garantiza reproducibilidad del entorno |
| `package.json` | Dependencias JS (Tailwind, Vite, laravel-vite-plugin) y scripts npm |
| `vite.config.js` | Configura Vite: entradas CSS/JS, plugin de Laravel, Tailwind y fuentes Bunny |
| `phpunit.xml` | Configura PHPUnit: suites, variables de entorno de prueba |
| `.env` | Variables de entorno locales (DB, APP_KEY, MAIL…). **No versionar** |
| `.env.example` | Plantilla documentada de variables de entorno |
| `.editorconfig` | Estilo de indentación y encoding para editores |
| `.gitignore` | Archivos y carpetas excluidos del control de versiones |
| `.gitattributes` | Normalización de fin de línea en Git |
| `.npmrc` | Configuración de npm (en este caso deshabilita la generación de `package-lock.json`) |
| `CLAUDE.md` / `AGENTS.md` | Instrucciones de configuración para agentes de IA (Laravel Boost) |

---

## Flujo de una Petición HTTP

```
Navegador
   │
   ▼
public/index.php          ← único archivo expuesto al servidor web
   │  carga autoloader de Composer y bootstrap/app.php
   ▼
bootstrap/app.php         ← construye la instancia Application
   │  registra rutas, middleware y manejo de excepciones
   ▼
routes/web.php            ← coincide la URL con una ruta definida
   │
   ▼
Controller (app/Http/Controllers/)
   │  puede interactuar con Models (Eloquent) y vistas
   ▼
Model (app/Models/)       ← consulta la base de datos via Eloquent ORM
   │
   ▼
View (resources/views/)   ← Blade compila la plantilla
   │  la vista compilada se cachea en storage/framework/views/
   ▼
Respuesta HTTP al navegador
```

---

## Base de Datos

### Tablas creadas por las migraciones base

| Tabla | Descripción |
|---|---|
| `users` | Usuarios del sistema (autenticación) |
| `password_reset_tokens` | Tokens para recuperación de contraseña |
| `sessions` | Sesiones almacenadas en BD |
| `cache` / `cache_locks` | Caché en base de datos |
| `jobs` / `job_batches` / `failed_jobs` | Cola de trabajos asíncronos |

### Comandos de base de datos

```bash
# Ejecutar todas las migraciones pendientes
php artisan migrate

# Revertir la última tanda de migraciones
php artisan migrate:rollback

# Resetear y volver a migrar + sembrar
php artisan migrate:fresh --seed

# Poblar la base de datos con el seeder
php artisan db:seed
```

---

## Frontend y Assets

El proyecto usa **Vite 8** como bundler con el **laravel-vite-plugin** para la integración con Blade.

### Entradas configuradas en `vite.config.js`

| Archivo de entrada | Resultado |
|---|---|
| `resources/css/app.css` | CSS compilado con Tailwind CSS v4 |
| `resources/js/app.js` | JavaScript del frontend |

### Fuentes

La fuente **Instrument Sans** (pesos 400, 500, 600) se carga desde **Bunny Fonts** (alternativa a Google Fonts respetuosa con la privacidad GDPR) configurada directamente en `vite.config.js`.

### Scripts disponibles

```bash
# Servidor de desarrollo con HMR (Hot Module Replacement)
npm run dev

# Build de producción (genera public/build/)
npm run build
```

En las vistas Blade, los assets se inyectan con:
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

---

## Comandos Útiles

```bash
# Iniciar el servidor de desarrollo (PHP + Vite en paralelo)
composer dev
# equivalente a:
php artisan dev

# Setup completo desde cero
composer setup

# Ejecutar la suite de tests
composer test

# Limpiar y regenerar caché de configuración
php artisan config:clear
php artisan config:cache

# Abrir el REPL interactivo
php artisan tinker

# Ver todas las rutas registradas
php artisan route:list

# Crear un nuevo controlador
php artisan make:controller NombreController

# Crear un modelo con migración y factory
php artisan make:model Producto -mf

# Formatear el código con Pint
vendor/bin/pint

# Ver logs en tiempo real
php artisan pail
```

---

## Instalación y Puesta en Marcha

### Requisitos

- PHP >= 8.3
- Composer >= 2.x
- Node.js >= 20.x con npm

### Pasos

```bash
# 1. Clonar el repositorio
git clone <url-del-repo> sistema-inventario
cd sistema-inventario

# 2. Setup automático (instala dependencias, genera clave, migra BD y compila assets)
composer setup

# 3. Iniciar el servidor de desarrollo
composer dev
```

El servidor estará disponible en `http://localhost:8000`.

### Setup manual (alternativa)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run dev
```

---

## Estado Actual del Proyecto

El proyecto se encuentra en su **fase inicial (scaffold)**. La estructura base de Laravel 13 está lista con:

- ✅ Autenticación de usuarios (modelo `User` con Eloquent)
- ✅ Migraciones base (users, sessions, cache, jobs)
- ✅ Frontend integrado con Tailwind CSS v4 y Vite
- ✅ Suite de pruebas configurada con PHPUnit
- ⬜ CRUD de productos / inventario (pendiente de implementar)
- ⬜ Gestión de categorías y proveedores (pendiente)
- ⬜ Movimientos de stock (entradas/salidas) (pendiente)
- ⬜ Reportes y dashboards (pendiente)
- ⬜ Autenticación completa con login/registro (pendiente)

---

*Documentación generada automáticamente para el proyecto `sistema-inventario` — Laravel 13 · PHP 8.3 · Tailwind CSS 4 · Vite 8*
