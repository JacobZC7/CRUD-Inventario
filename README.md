# Gestor de Inventario con Laravel

Versión pública y construida desde cero de un sistema de gestión de inventario: autenticación, roles (`admin` y `vendedor`), categorías, productos, stock y movimientos, con datos de ejemplo y una interfaz limpia y fluida inspirada en el lenguaje visual de Apple.

## Estado actual

- **Etapa 1**: Proyecto base en Laravel 13 + PHP 8.3 + Tailwind CSS + Vite.
- **Etapa 2**: Autenticación con Laravel Breeze (Blade + Alpine.js), roles (`admin` y `vendedor`), middleware/gates de autorización, gestión de usuarios/roles para administradores y diseño fluido.
- **Etapa 3**: CRUD completo de categorías y productos, control de stock con trazabilidad (kardex de entradas, salidas y ajustes), alertas de stock bajo/agotado, panel con métricas reales y datos de ejemplo (`InventorySeeder`).

## Roles del sistema

| Rol | Clave | Alcance |
| --- | --- | --- |
| Administrador | `admin` | Gestión de usuarios y roles, categorías, catálogo completo de productos (crear, editar, eliminar), stock y movimientos. |
| Vendedor | `vendedor` | Consulta de panel, catálogo/detalle de productos, stock y registro de movimientos de inventario (rol por defecto al registrarse). |

## Instalación local

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

### Usuarios de ejemplo (solo desarrollo)

El seeder `DatabaseSeeder` crea dos cuentas verificadas con contraseña `password` junto con categorías, productos y movimientos de ejemplo:

- **Administrador**: `admin@inventario.test`
- **Vendedor**: `vendedor@inventario.test`

> **Nota de seguridad**: En producción establece `APP_DEBUG=false`, `APP_ENV=production`, apunta el DocumentRoot del servidor web únicamente a `public/` y cambia o elimina las cuentas de ejemplo.

## Pruebas

```bash
php artisan test
```
