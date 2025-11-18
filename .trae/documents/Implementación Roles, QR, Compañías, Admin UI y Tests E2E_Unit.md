## Alcance y Objetivos
- Completar tareas pendientes: migraciones y policies de roles, endpoints de QR y compañías, vistas admin para creación/edición, seeds de 3 demos y pruebas E2E/Unit con fixtures.
- Garantizar lectura pública sin login (invitado) y edición restringida a admin/editor.

## Backend — Migraciones y Modelo
### Migraciones nuevas
1. users.role
- Añadir columna `role` enum: `admin|editor|viewer` (default `viewer`).
- Índice sobre `role` para filtros.

2. pipelines.qr_checksum y qr_image_path
- Campos: `qr_checksum` (string, indexed) y `qr_image_path` (string, nullable) para almacenar imagen QR generada.

3. pipeline_companies integridad operador actual
- Añadir índice compuesto: `pipeline_id, role, is_current`.
- Validación de aplicación: sólo un `current_operator` con `is_current=true` por tubería.

### Seeds (3 demos)
- Crear `PIPE-2024-001`, `PIPE-2024-002`, `PIPE-2024-003` con checksum.
- Empresas: una iniciadora y un operador actual por cada demo.
- Licencias: 1 activa, 1 próxima a vencer (<60 días), 1 expirada.
- Certificaciones: ISO 9001/14001; estados variados.
- Blueprints: PDF/DWG placeholders.
- Asignar `role` a usuarios: admin y editor; crear viewer opcional.

## Backend — Policies/Gates y Endpoints
### Roles y autorización
- Policies/Gates:
  - admin/editor: CRUD completo de pipelines, companies, certifications, blueprints, licenses, pipeline_companies.
  - viewer/invitado: sólo GET (lectura), sin POST/PUT/DELETE.
- Middlewares: aplicar `auth:sanctum` + `can:*` por recurso en rutas protegidas.

### Endpoints nuevos/ajustados
1. QR
- `POST /api/pipelines/{id}/generate-qr` (admin/editor): genera PNG/SVG, guarda `qr_image_path`, actualiza `qr_checksum`.
- `GET /api/pipelines/{id}/qr` (público): descarga imagen QR.

2. Compañías
- `POST /api/pipeline-companies` (admin/editor): vincular empresa a tubería con `role` (`initiator|current_operator`), `start_date`, `is_current`; si `current_operator` `is_current=true`, colocar `is_current=false` en el anterior.
- Opcional: `PUT/DELETE /api/pipeline-companies/{id}` para actualizar/remover vínculos (con reglas).

3. Lectura pública
- Confirmar GET públicos: `/api/pipelines?qr_code={code}`, `/api/pipelines/{id}`, `/api/blueprints/{id}/download`, `/api/certifications/{id}/download`, `/api/pipelines/{id}/license`, `/api/pipelines/{id}/qr`.

### Servicios
- `QrService`: formato `PIPE-{YEAR}-{SEQUENTIAL}` + cálculo checksum (p.ej. CRC32/sha1 truncado) y generación de imagen (endroid/qr-code o SVG render).
- Validadores: garantizar unicidad `qr_code`

## Frontend — Admin/Editor y Invitado
### Estructura UI/Admin
- Rutas `admin`:
  - `/admin/pipelines/new`: formulario creación (datos técnicos, empresas, licencia, certificaciones, planos). Botón “Generar QR” y “Exportar QR”.
  - `/admin/pipelines/:id/edit`: edición completa; gestión de vínculos de empresas; carga múltiples archivos; estados.
  - `/admin/pipelines`: listado con filtros y acciones.
- Roles en UI: Pinia store de auth con `role` (admin/editor/viewer); guards en Router para `/admin/*`.
- Invitado: acceso a `/scan` y `/pipelines/:id` sin login; ocultar controles de edición.

### Scan mejorado
- Ya soporta cámara e imagen. Añadir helper para subir fixture QR en E2E.

## Pruebas — E2E y Unit
### E2E (Cypress)
1. Login y creación
- Caso: login admin → visita `/admin/pipelines/new` → llena datos → crea pipeline → genera QR → descarga QR.
2. Scan por imagen → detalle → descarga
- Caso: en invitado, visita `/scan`, carga fixture QR (PNG) → navega a detalle → descarga certificación/blueprint.
3. Permisos
- Caso: viewer (o invitado) accede a detalle pero no ve edición; intenta POST y recibe 403.

- Fixtures: `cypress/fixtures/qr-demo-1.png`, `qr-demo-2.png`, `qr-demo-3.png`.

### Unit (Vitest)
- Policies: pruebas unitarias de `can('update', Pipeline)` por roles.
- QrService: validación de checksum y generación de imagen.
- Requests: validación de `PipelineRequest`.
- UI: visibilidad por rol en admin components.

## OpenSpec/README Actualización
- Documentar roles y seguridad en endpoints.
- Añadir endpoints QR y pipeline-companies.
- Describir flujo de creación completa y exportación del QR.

## Implementación por Pasos
1. Migraciones: `users.role`, `pipelines.qr_checksum/qr_image_path`.
2. Policies/Gates y protección de rutas.
3. QrService + endpoints `generate-qr` y `qr`.
4. Endpoints `pipeline-companies` (crear/actualizar/remover).
5. Seeds 3 demos con QR y datos completos.
6. Frontend: módulo admin con new/edit/list y acciones QR; guards por rol.
7. E2E con fixtures (login admin, creación QR, scan imagen, detalle, descargas, permisos).
8. Unit tests (Policies, QrService, Requests, UI).
9. Actualizar OpenSpec y README.

## Verificación Final
- Ejecutar `npm run cy:run -C frontend` y `npm run test:run -C frontend` para validar.
- Prueba manual de `/scan` (imagen y cámara) y `/pipelines/:id` en invitado.

## Entregables
- Código backend y frontend con roles, QR endpoints, compañías, admin UI, seeds de 3 demos.
- Suite E2E y Unit con fixtures.
- OpenSpec + README actualizados.

## Próximo Paso
Tras confirmar, procedo a implementar migraciones/policies, servicios y endpoints, UI admin y seeds, y a crear las pruebas E2E/Unit con fixtures para validar todo el flujo end-to-end. 