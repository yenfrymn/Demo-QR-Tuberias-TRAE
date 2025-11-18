# Demo-QR-Tuberias-TRAE

Sistema integral para gestión y consulta de tuberías petroleras mediante códigos QR. Incluye backend Laravel 12 + MySQL 8 y frontend Vue 3 mobile-first, con PWA y escáner QR.

## Desarrollo local (XAMPP)
- Crear BD MySQL: `qrtuberia` (utf8mb4).
- Configurar `backend-app/.env` con `DB_CONNECTION=mysql`, `DB_DATABASE=qrtuberia`, `DB_USERNAME=root`.
- Migrar y seed: `php backend-app/artisan migrate:fresh --seed`.
- Frontend dev: `npm run dev -C frontend` → `http://localhost:5173/`.
- API base para frontend: `frontend/.env` → `VITE_API_BASE=http://localhost/qrtuberiaTrae/backend-app/public/index.php`.

## Rutas clave
- `GET /api/health` — salud
- Auth: `POST /api/login`, `POST /api/logout`, `POST /api/refresh`
- Pipelines: CRUD + `GET /api/pipelines?qr_code=...`
- Certificaciones: lista, upload y descarga
- Blueprints: lista, upload múltiple y descarga
- Licencias: obtener por tubería, crear y próximas a vencer
- Reportes: `POST /api/reports/generate` (PDF) y descarga

## Acceso de prueba
- Usuario: `admin@example.com`
- Password: `Admin123!`