## Objetivo
Completar las tareas pendientes: autorización por roles, generación/descarga de QR, enlaces compañía↔tubería, vistas admin para creación/edición, semillas de tres demos y suite de pruebas E2E/Unit.

## Backend — Autorización
### Policies/Gates
- Definir Gates en `AuthServiceProvider`:
  - admin/editor → permisos `create`, `update`, `delete` en modelos: Pipeline, Company, Certification, Blueprint, OperatingLicense, PipelineCompany.
  - viewer/invitado → sólo lectura (`view`, `list`).
- Aplicar middleware: rutas POST/PUT/DELETE con `auth:sanctum` y `can:{ability},{model}`.

## Backend — QR
### Servicio QR
- Clase `App\Services\QrService`:
  - Método `generateCode(Pipeline $p)`: asegura formato `PIPE-{YEAR}-{SEQUENTIAL}` si no existe.
  - Método `checksum(string $code)`: SHA1 truncado.
  - Método `generateImage(string $code)`: genera PNG (endroid/qr-code) o SVG; devuelve `path`.
- Persistencia: guarda `qr_checksum` y `qr_image_path` en `pipelines`.

### Endpoints
- `POST /api/pipelines/{pipeline}/generate-qr` (admin/editor): genera y almacena QR, retorna `download_url`.
- `GET /api/pipelines/{pipeline}/qr` (público): descarga imagen.

## Backend — Compañías
### Endpoints
- `POST /api/pipeline-companies` (admin/editor): body `{pipeline_id, company_id, role, start_date, end_date?, is_current}`.
  - Si `role=current_operator && is_current=true`: setear `is_current=false` en vínculo previo de ese pipeline.
- `PUT /api/pipeline-companies/{id}` y `DELETE /api/pipeline-companies/{id}` (admin/editor): actualizar/remover vínculo con reglas.

## Backend — Seeds (3 demos)
- Pipelines: `PIPE-2024-001/002/003` con `qr_checksum` y `qr_image_path` generados.
- Empresas iniciadoras y operadores actuales; licencias: activa, próxima (≤60 días), expirada.
- Certificaciones ISO 9001/14001 con estados distintos; planos PDF/DWG placeholders.
- Usuarios: admin, editor, viewer; roles asignados.

## Frontend — Admin UI
### Rutas y Vistas
- `/admin/pipelines`: listado con filtros y acciones.
- `/admin/pipelines/new`: formulario creación (datos técnicos, empresas, licencias, certificaciones, planos); botones “Generar QR” y “Exportar QR”.
- `/admin/pipelines/:id/edit`: edición completa de los mismos elementos.
- Guards por rol: acceso sólo para `admin|editor` vía Router + Pinia store.
- Lectura invitado: `/scan` (cámara/imagen) y `/pipelines/:id` sin login; ocultar controles de edición.

## Pruebas — E2E y Unit
### E2E (Cypress)
- Caso 1: login admin → `/admin/pipelines/new` → crear pipeline → generar QR → descargar QR.
- Caso 2: invitado → `/scan` → cargar imagen QR fixture → navegar a detalle → descargar certificación/blueprint.
- Caso 3: permisos: viewer/invitado no acceden a `/admin/*`; POST/PUT/DELETE devuelven `403`.
- Fixtures: `cypress/fixtures/qr-demo-1.png`, `qr-demo-2.png`, `qr-demo-3.png`.

### Unit (Vitest)
- Policies: pruebas de `can('update', Pipeline)` por rol.
- QrService: checksum, generación y persistencia de imagen.
- Requests: validaciones en `PipelineRequest` y nuevas requests para compañías.
- UI: visibilidad de componentes admin por rol.

## OpenSpec/README
- Añadir seguridad por roles en endpoints.
- Incluir endpoints QR y `pipeline-companies` con contratos.
- Documentar flujo de creación y exportación de QR.

## Pasos de Implementación
1. Crear y registrar Policies/Gates; proteger rutas con middleware.
2. Implementar `QrService`, controlador QR y rutas.
3. Implementar endpoints de `pipeline-companies` (crear/actualizar/eliminar) con reglas de operador actual.
4. Sembrar 3 demos con datos completos y QR.
5. Crear vistas admin (listado, nuevo, editar) y guards de Router.
6. Implementar E2E con fixtures; ampliar unit tests.
7. Actualizar OpenSpec y README.

## Verificación
- Ejecutar `npm run cy:run -C frontend` y `npm run test:run -C frontend`.
- Validar manualmente `/scan` (imagen y cámara), `/pipelines/:id` público, descargas y reportes.

## Entregables
- Código backend/frontend completo con roles, QR, compañías y UI admin.
- 3 demos con QR.
- Suite E2E/Unit y documentación actualizada.

## Confirmación
¿Confirmas para ejecutar todos los cambios según este plan y entregar el proyecto final con pruebas y datos de demostración? 