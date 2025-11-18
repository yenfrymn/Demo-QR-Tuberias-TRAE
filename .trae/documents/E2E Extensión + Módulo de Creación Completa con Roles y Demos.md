## Objetivos
- Extender E2E para flujo real: login admin → scan (cámara/imagen) → detalle → descarga.
- Planificar y crear el módulo de creación/edición completa de una tubería: generar QR exportable, información técnica, certificaciones, planos y datos de empresas operadoras.
- Sembrar tres demos completas con sus QR para pruebas.
- Implementar control de acceso por roles: admin y editor (crear/editar), invitado (lectura sin login). Ajustar BD y arquitectura para escalabilidad.

## Cambios de Backend
### Roles y Autorización
- Agregar `users.role` (enum: admin, editor, viewer) con migración y seed inicial.
- Policies/Gates:
  - admin/editor: crear/editar/eliminar `pipelines`, `certifications`, `blueprints`, `operating_licenses`, `companies` y `pipeline_companies`.
  - invitado: lectura pública (sin login) de detalle por `qr_code` y descargas de documentos asociados.
- Rutas públicas: `GET /api/pipelines?qr_code={code}`, `GET /api/pipelines/{id}`, `GET /api/certifications/{id}/download`, `GET /api/blueprints/{id}/download`, `GET /api/pipelines/{id}/license`.
- Rutas protegidas: POST/PUT/DELETE de todas las entidades.

### Generación/Exportación de QR
- Servicio `QrService` con formato `PIPE-{YEAR}-{SEQUENTIAL}` + checksum.
- Librería QR (p.ej. endroid/qr-code v5) para generar PNG; fallback: render SVG basado en data URL si se prefiere sin dependencia.
- Endpoints:
  - `POST /api/pipelines/{id}/generate-qr` (admin/editor): genera y guarda PNG, devuelve `download_url`.
  - `GET /api/pipelines/{id}/qr` (público): descarga del QR asociado.
- Al crear pipeline (`POST /api/pipelines`), soportar bandera `auto_generate_qr` para generar QR inmediato.

### CRUD Completo y Validaciones
- `PipelineRequest` y controladores para crear/actualizar datos técnicos.
- Endpoints para empresas:
  - `POST /api/pipeline-companies` (admin/editor): vincular empresa con rol `initiator` o `current_operator` y fechas.
- Certificaciones y planos:
  - Subida múltiple (50MB, PDF/DWG/DXF); versionado auto y estados.
- Licencias:
  - Validación de vigencia y alertas a 60 días; `GET /api/licenses/expiring`.
- Integridad:
  - Garantizar único `current_operator` por tubería (validación y/o trigger).

### Semillas (3 demos)
- Crear tres tuberías con QR generados:
  - `PIPE-2024-001`, `PIPE-2024-002`, `PIPE-2024-003` (con checksum en DB).
- Asociar empresas (iniciadora y operador actual), licencias vigentes y próximas a vencer.
- Subir certificaciones ejemplo (ISO 9001/14001) y planos (PDF/DWG) de muestra (placeholders en entorno dev).

### Escalabilidad y Seguridad
- Índices: `pipelines.qr_code`, `certifications.expiry_date`, `operating_licenses.expiry_date`.
- Caching (Redis) para lecturas por `qr_code`.
- Storage S3-compatible en producción; CDN para estáticos.
- Rate limiting y auditoría; logs de Jobs.
- Posible índice espacial para lat/lng en búsquedas geográficas (futuro).

## Cambios de Frontend
### Módulo Admin/Editor
- Vistas y rutas bajo `/admin`:
  - `AdminPipelinesNew`: crear tubería; botón “Generar QR” y “Exportar QR”.
  - `AdminPipelinesEdit/:id`: formulario completo (datos técnicos, empresas, licencias, certificaciones, planos).
  - Listas y filtros.
- UI condicional por rol: mostrar módulo admin/editor sólo si `user.role` es `admin` o `editor`.
- Lectura pública (invitado): rutas `scan` y `pipeline-detail` accesibles sin login.

### Lectura QR (cámara/imagen)
- Extender `ScanView`:
  - Botón “Usar cámara” (lector en vivo) y “Desde imagen” (decodifica archivo y navega al detalle).

### E2E y Unit Tests
- Cypress E2E escenarios:
  1. Login admin (admin@example.com) → visita `/admin` → crear pipeline demo → generar QR → subir certificación/blueprint → éxito.
  2. Scan por imagen usando QR demo → navegación al detalle → descarga documentos (certificación/blueprint).
  3. Invitado: acceso a `scan` y `pipeline-detail` sin login; no ve controles de edición.
- Fixtures: imágenes PNG de QR de los demo.
- Vitest unit:
  - Interceptores Axios (auth), utils de formateo, visibilidad por rol, validación de payloads.

## Actualizaciones de OpenSpec
- Añadir endpoints:
  - `POST /api/pipelines/{id}/generate-qr`
  - `GET /api/pipelines/{id}/qr`
  - `POST /api/pipeline-companies`
- Definir seguridad y visibilidad por rol en cada endpoint.
- Contratos JSON: incluir `qr_image_url`, `share_url` en detalle.

## Migraciones
- `users.role` enum `admin|editor|viewer`, default `viewer`.
- Índices adicionales; migración de `pipelines.qr_checksum` (string) para verificación.

## Tareas (Task-Master-AI)
- Backend:
  - Migración roles, checksum.
  - Policies/Gates por rol.
  - Servicio QR + endpoints.
  - Seed tres demos (datos y archivos).
- Frontend:
  - Rutas `/admin` y formularios.
  - Botón “Generar/Exportar QR”.
  - Permisos de UI.
- QA:
  - E2E Cypress con fixtures.
  - Unit tests Vitest.

## Verificación
- Ejecutar E2E suite: login admin → crear → generar QR → scan por imagen → detalle → descargas.
- Verificar invitado accede a lectura sin login y no ve edición.

## Entregables
- Código backend y frontend actualizados.
- 3 QR demo y datos completos.
- Suite E2E y unit tests listas.
- OpenSpec y README actualizados.

## Próximo Paso
Tras tu confirmación, implemento las migraciones y policies, los endpoints de QR y compañías, las vistas admin y los seeds de los tres demos, y adiciono los tests E2E/Unit con fixtures para validar el flujo end-to-end. 