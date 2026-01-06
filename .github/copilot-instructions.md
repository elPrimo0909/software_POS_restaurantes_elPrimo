<!-- Copilot / AI agent instructions for working in this repository -->
# Project snapshot

This repository is a PHP-based point-of-sale (POS) for restaurants. Key features: product/inventory management, sales (POS), client management and simple reports. The codebase mixes PHP templates, procedural mysqli calls, and small JS/AJAX endpoints.

# Quick start (developer environment)
- Requirements: PHP (7.x/8.x), MySQL/MariaDB, a webserver (Apache or PHP built-in `php -S`).
- Import the demo user DB used by the login module: `mysql -u root -p < login/registration_login_db.sql`.
- Run locally with the built-in server (for quick checks):

```bash
cd /path/to/project
php -S localhost:8000 -t .
# Open http://localhost:8000/pos/ or http://localhost:8000/index.html
```

# Where to look first (high-value files)
- DB configuration examples: clientes/config/db.php and configuracion/config/db.php define `DB_HOST/DB_NAME/DB_USER/DB_PASS`.
- Connection wrapper: `*/config/conexion.php` and `*/config/conexion2.php` (many modules duplicate these files).
- POS entrypoint: pos/index.php (session checks, modal includes, DB defines).
- AJAX endpoints: each module exposes endpoints under `*/ajax/` (for example, `clientes/ajax/agregar_pedido.php`) — these are small procedural scripts that read `$_POST`/`$_GET` and `require_once ../config/db.php`.
- UI fragments: modal_*.php files are included across pages to render forms and dialogs (e.g., `pos/modal_nuevo_cliente.php`, `clientes/modal_nuevo_cliente.php`).

# Project-specific conventions & patterns
- Multiple modules (clientes, configuracion, informes, inventario_f, pos) each contain their own copies of `config/` (db.php, conexion.php). When changing DB constants, update the specific module's `config/db.php` used by that module.
- AJAX handlers live in the module `ajax/` folder and are typically included by client-side scripts via relative paths. They commonly `require_once` the module `config/db.php` and `config/conexion.php`.
- UI composition is done via PHP `include`/`require` of modal fragments rather than a centralized template system.
- Naming patterns: `modal_*` for modal UI, `agregar_*` / `eliminar.php` / `modificar.php` for CRUD endpoints, and `updatecliente.php` used repeatedly across modules.

# Integration points & external dependencies
- Relies on MySQL/MariaDB and mysqli extension (procedural). No package manager or build step is required for PHP assets.
- Some pages reference static assets under module `img/` and `inventario_f/imagenes/` (favicon, logos).

# Safety and refactor notes (important for AI agents)
- The codebase contains many direct SQL strings and suppressed errors (see `error_reporting` usage and `@mysqli_connect`). Avoid automated, wide-scope refactors that change DB access patterns without human review.
- Prefer small, module-local changes and keep backups before changing `config/db.php` files.
- If implementing parameterized queries or a DB abstraction, do it incrementally and run manual smoke tests (POS transaction flows) after each change.

# Example tasks and where to implement them
- Add a new AJAX endpoint to add items: create `clientes/ajax/new_endpoint.php`, follow the pattern in `clientes/ajax/agregar_pedido.php` (session_id usage, require `../config/db.php`).
- Update a modal form: edit `pos/modal_nuevo_cliente.php` or `clientes/modal_nuevo_cliente.php` depending on scope.
- Fix DB constants for local dev: update `clientes/config/db.php` and the same file in `configuracion/config/` and `pos/` if present.

# Tests / debugging
- No automated tests are present. Use browser + network inspector to debug AJAX requests and `error_log()` / `var_dump()` for server-side quick debugging.
- To reproduce common flows: 1) start server, 2) import SQL from `login/registration_login_db.sql`, 3) open `pos/index.php` and use UI to create items and orders.

# If you need more context
- I focused on discoverable patterns in `clientes/`, `pos/`, `configuracion/`, `inventario_f/` and `login/`. Tell me which module you want deeper guidance on (I can add examples or cautious refactor suggestions).

---
Please review this draft and tell me any missing commands, common developer pitfalls, or workflows to include. I'll iterate quickly.
