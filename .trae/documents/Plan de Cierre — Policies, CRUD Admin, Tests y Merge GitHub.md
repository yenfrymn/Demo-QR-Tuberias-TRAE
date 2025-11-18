## Objetivo
Completar todas las tareas pendientes de la planeación: autorización por roles (Policies), endpoints restantes de compañías, formularios Admin completos (empresas, licencias, certificaciones, planos), pruebas E2E/Unit con fixtures y sincronización con GitHub tras verificación.

## Backend
- Policies por modelo: Pipeline, Company, Certification, Blueprint, OperatingLicense y PipelineCompany con abilities `view|list` público y `create|update|delete` para `admin|editor`.
- Endpoints compañías: `PUT /api/pipeline-companies/{id}` y `DELETE /api/pipeline-companies/{id}` con regla de unicidad del operador actual (`is_current=true` ⇒ desactivar anterior).
- Requests de validación: inputs de compañías, licencias, certificaciones y planos; respuestas JSON consistentes.
- Rendimiento: eager loading y cache Redis en `GET /api/pipelines?qr_code={code}`.

## Frontend (Admin)
- Completar `/admin/pipelines/:id/edit` con CRUD:
  - Empresas: crear/actualizar/eliminar vínculos (rol, fechas, `is_current`).
  - Licencias: crear/actualizar con validación de vigencia.
  - Certificaciones: subida/estado/descarga.
  - Planos: subida múltiple, versionado y descarga.
- Control por rol: guards y ocultar componentes si `role` no es `admin|editor`.

## Pruebas
- E2E (Cypress):
  - Login admin → crear pipeline → generar/descargar QR → editar datos.
  - Invitado → `scan` con imagen fixture → detalle → descargas.
  - Permisos: viewer/invitado bloqueados en `/admin/*` y `403` en edición.
- Unit (Vitest): Policies por rol, QrService (checksum/imagen), Requests de validación y visibilidad UI por rol.
- Fixtures: `qr-demo-1.png`, `qr-demo-2.png`, `qr-demo-3.png` generadas desde códigos seed.

## Documentación
- OpenSpec: añadir endpoints QR y `pipeline-companies` con seguridad por rol y contratos.
- README: pasos para E2E/Unit, roles, rutas Admin y uso de demos.

## Verificación y GitHub
- Verificar estado con remoto: `git fetch origin main`, `git diff origin/main`.
- Integrar con `git rebase origin/main` (o `git merge origin/main` si corresponde) y resolver conflictos.
- Ejecutar `npm run build -C frontend`, `npm run test:run -C frontend`, `npm run cy:run -C frontend`.
- Auditoría `.env` y `.gitignore`; commit y `git push -u origin main`.

## Entrega
- Proyecto funcional end-to-end con 3 demos QR, pruebas aprobadas y repositorio actualizado con documentación.

## Próximo paso
Tras esta confirmación, ejecutaré cada bloque (backend, frontend, pruebas y GitHub) en orden, validando en cada etapa antes de avanzar al siguiente.