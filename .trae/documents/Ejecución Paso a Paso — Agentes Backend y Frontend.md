## Objetivo
Realizar el proyecto end-to-end con dos agentes especializados: uno para Backend (Laravel 12 + MySQL 8) y otro para Frontend (Vue 3 + Alpine.js, mobile-first), integrando QR, documentación técnica, certificaciones, planos, licencias y reportes.

## Asignación de Agentes
### Agente Backend (Laravel 12)
- Responsable de API REST, modelo de datos, autenticación/roles, almacenamiento de documentos, alertas de vencimiento, generación de reportes PDF y desempeño.
- Entregables: proyecto Laravel, migraciones, modelos/relaciones, controladores/requests/resources, jobs/queues, políticas, endpoints, pruebas backend, OpenAPI alineado.

### Agente Frontend (Vue 3 + Alpine.js)
- Responsable de SPA mobile-first, autenticación cliente, escaneo QR, vistas de detalle, descargas/preview, búsqueda/filtros, notificaciones, PWA offline.
- Entregables: proyecto Vue, layout, componentes UI, integración API, escáner QR, PWA, pruebas frontend.

## Estructura del Repositorio
- `backend/` (Laravel 12)
- `frontend/` (Vue 3 + Vite)
- `docs/` (OpenAPI, manuales, guías)
- `deploy/` (scripts y notas para VPS/cPanel)

## Paso a Paso (Sprints)
### Sprint 1 — Backend Foundation (Semana 1-2)
1. Inicialización
- Comandos sugeridos (no ejecutar aún):
  - `composer create-project laravel/laravel backend`
  - `cd backend && composer require laravel/sanctum spatie/laravel-medialibrary barryvdh/laravel-dompdf maatwebsite/excel predis/predis`
  - Configurar `.env` (MySQL 8, Redis, storage S3/local, CORS, Sanctum).
2. Migraciones y Modelo de Datos
- Tablas: `pipelines`, `companies`, `pipeline_companies`, `certifications`, `operating_licenses`, `blueprints`, `inspection_reports`, `users` extendido.
- Índices: `qr_code`, `expiry_date`, FKs.
3. Modelos y Relaciones
- Eloquent + scopes (por `qr_code`, próximos a vencer), soft-deletes donde aplique.
- Observers: unicidad/generación QR, versionado de planos.
4. Autenticación y Autorización
- Sanctum; roles (admin, inspector, viewer) con Policies/Gates.
5. Endpoints CRUD y Funcionalidades
- Pipelines CRUD + búsqueda por QR; Certificaciones (upload/download) con estado; Blueprints (upload múltiple, versionado, preview); Licencias (vigencia/alertas); Reportes PDF.
6. Jobs y Alertas
- Tareas programadas (certificaciones 90 días, licencias 60 días) y colas.
7. Pruebas y OpenAPI
- PHPUnit/Pest; coverage objetivo ≥80%; alinear contratos a `openapi.yaml`.

### Sprint 2 — Frontend Core (Semana 3)
1. Inicialización
- Comandos sugeridos:
  - `npm create vite@latest frontend -- --template vue`
  - `cd frontend && npm i vue-router pinia tailwindcss axios html5-qrcode vite-plugin-pwa`
  - `npx tailwindcss init -p`
2. Autenticación
- Login + store (Pinia) + interceptores Axios + guards; persistencia y auto-refresh.
3. Layout y UI Base
- `AppLayout` mobile-first, navegación, header, footer, loaders; alto contraste, accesibilidad.
4. Escáner QR
- Integración `html5-qrcode`, permisos de cámara, feedback, redirección a detalle.
5. Vistas de Detalle
- Información pipeline, empresas, certificaciones (badges), planos (preview), licencia; botones de descarga.
6. PWA / Offline
- Service worker (Workbox/Vite PWA), caché GET, cola de requests, indicador offline.
7. Pruebas
- Vitest unit; Cypress E2E para flujo QR → detalle.

### Sprint 3 — Features Avanzadas (Semana 4)
- Generación de reportes (frontend botón, backend endpoint), sistema de notificaciones (UI + email opcional), panel admin (CRUD usuarios/empresas, roles, logs), búsqueda y filtros (estado, ubicación, empresa; paginación y rendimiento).

### Sprint 4 — Testing, Optimización y Deploy (Semana 5)
- Backend: unit/feature tests; evitar N+1; eager loading; caching Redis.
- Frontend: E2E completas; performance (Lighthouse/PWA).
- Documentación: OpenAPI, manual de usuario, guía de despliegue.
- VPS/cPanel/WHM: 
  - Subdominios (`api.` y `app.`), SSL (Let’s Encrypt), PHP 8.3, Redis.
  - Backend: `composer install`, `php artisan key:generate`, `php artisan storage:link`, `php artisan migrate --force`, configurar `QUEUE_WORKER`/cron.
  - Frontend: `npm run build` y subir `dist/` a `public_html`; configurar `.htaccess`/rewrite y CORS.

## Entregables y Criterios de Aceptación
- API funcional con contratos alineados a `openapi.yaml`, autenticación y permisos.
- Escáner QR robusto en móviles, detalle de tubería completo y descargas operativas.
- Alertas de vencimiento activas; reportes PDF generados.
- PWA con modo offline; panel admin y búsqueda avanzada.
- Pruebas con cobertura ≥80% backend y flujo E2E verificado.

## Coordinación y Seguimiento
- Uso de `tasks.md` para orquestar sprints y tareas; sincronización diaria entre agentes.
- Revisiones cruzadas: frontend valida contratos y estados; backend publica mocks cuando sea necesario.

## Próximo Paso
Tras aprobación, ejecuto la inicialización de `backend/` y `frontend/` siguiendo los comandos indicados, creo las migraciones y endpoints base (incluyendo `/api/health`) y activo la PWA y el componente de escáner en el frontend para validar el flujo QR → detalle. 