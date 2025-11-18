## Objetivos
- Completar las tareas pendientes: migraciones/roles, policies/Gates, servicio y endpoints de QR, endpoints de compañía↔tubería, vistas admin para creación/edición, seeds con 3 demos y pruebas E2E/Unit con fixtures.
- Mantener lectura pública sin login; edición restringida a admin/editor.

## Backend — Cambios
### Migraciones
1) `users`:
- Añadir `role` enum: `admin|editor|viewer` (default `viewer`), índice en `role`.
2) `pipelines`:
- Añadir `qr_checksum` (string indexed) y `qr_image_path` (string nullable) para imagen QR generada.
3) `pipeline_companies`:
- Índice compuesto `pipeline_id, role, is_current` para integridad del operador.

### Policies/Gates y Rutas
- Definir Gates: admin/editor → CRUD; viewer/invitado → GET.
- Proteger POST/PUT/DELETE con `auth:sanctum` y `can:*` por recurso.
- Mantener GET públicos para: detalle por `qr_code` y descargas.

### Servicio y Endpoints QR
- Servicio `QrService`:
  - Generar código `PIPE-{YEAR}-{SEQUENTIAL}` y `qr_checksum` (p. ej. SHA1 truncado).
  - Crear imagen QR (PNG o SVG) y guardar `qr_image_path`.
- Endpoints:
  - `POST /api/pipelines/{id}/generate-qr` (admin/editor): genera/almacena QR.
  - `GET /api/pipelines/{id}/qr` (público): descarga QR.

### Endpoints Compañías↔Tubería
- `POST /api/pipeline-companies` (admin/editor): vincular empresa a tubería con `role`, `start_date`, `is_current`.
- Reglas: si `current_operator` `is_current=true`, desactivar el anterior.
- Opcional: `PUT/DELETE /api/pipeline-companies/{id}` para actualización/remoción.

### Seeds (3 demos)
- Crear 3 tuberías con `qr_code` y `qr_checksum` (2024-001/002/003).
- Empresas iniciadoras y operadores actuales; licencias (activa, próxima a vencer, expirada).
- Certificaciones (ISO 9001/14001) con estados variados.
- Blueprints (PDF/DWG placeholders) y documentos descargables.
- Asignar roles: uno admin, uno editor, uno viewer.

## Frontend — Admin/Editor y Invitado
### Admin UI (/admin)
- Rutas:
  - `/admin/pipelines` (listado y filtros)
  - `/admin/pipelines/new` (creación completa) con botón “Generar QR” y “Exportar QR”.
  - `/admin/pipelines/:id/edit` (edición completa): datos técnicos, empresas, licencias, certificaciones y planos.
- Guards de Router: acceso solo si `role ∈ {admin, editor}`.
- Ocultar controles de edición si `role ≠ admin|editor`.

### Invitado
- Lectura sin login: `/scan`, `/pipelines/:id` (por `qr_code`), descargas.
- UI ya soporta cámara e imagen en `ScanView`.

## Pruebas — E2E y Unit
### E2E (Cypress)
- Caso 1: login admin → `/admin/pipelines/new` → crear pipeline demo → generar QR → descargar QR.
- Caso 2: invitado → `/scan` → carga imagen QR fixture → navegar a detalle → descargar certificación/blueprint.
- Caso 3: permisos: viewer/invitado no puede acceder a `/admin/*` y al editar recibe 403.
- Fixtures: `cypress/fixtures/qr-demo-1.png`, `qr-demo-2.png`, `qr-demo-3.png`.

### Unit (Vitest)
- Policies/Gates: permisos por rol.
- QrService: checksum y generación imagen.
- Requests: validaciones de `PipelineRequest` y nuevas requests.
- UI: visibilidad por rol en componentes admin.

## Actualización OpenSpec/README
- Documentar roles y seguridad en cada endpoint.
- Añadir endpoints QR y `pipeline-companies` con contratos.
- Describir flujo de creación y exportación de QR.

## Pasos de Implementación
1) Migraciones y actualización de modelos.
2) Policies/Gates y protección de rutas.
3) Servicio QR + endpoints.
4) Endpoints `pipeline-companies`.
5) Seeds 3 demos con datos completos.
6) Frontend admin: vistas y guards.
7) E2E con fixtures, y unit tests.
8) Actualizar OpenSpec y README.

## Verificación
- Ejecutar `npm run cy:run -C frontend` y `npm run test:run -C frontend`.
- Probar manualmente `/scan` y `/pipelines/:id` (imagen y cámara).

## Entregables
- Código backend y frontend con roles, módulos y QR.
- 3 demos con QR.
- Suite E2E/Unit y documentación.

## Solicitud
Si confirmas, procedo a implementar los cambios descritos en el repositorio y ejecutar las suites de pruebas para entregar el flujo end-to-end verificado.