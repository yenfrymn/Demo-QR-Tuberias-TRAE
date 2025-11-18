## Objetivo
Finalizar tareas pendientes según planeación: autorización por roles, CRUD Admin completo (empresas, licencias, certificaciones, planos), pruebas E2E/Unit y sincronización con GitHub tras verificación.

## Backend
- Implementar Policies por modelo (Pipeline, Company, Certification, Blueprint, OperatingLicense, PipelineCompany) con `view|list` público y `create|update|delete` para `admin|editor`.
- Añadir endpoints compañías: `PUT /api/pipeline-companies/{id}` y `DELETE /api/pipeline-companies/{id}` con unicidad del operador actual (`is_current=true` desactiva anterior).
- Requests de validación y eager loading + cache por `qr_code`.
- Validación: listar rutas, migrar en limpio, probar creación de pipeline, generación/descarga QR y vínculos de compañías.

## Frontend (Admin)
- Completar `/admin/pipelines/:id/edit` con CRUD de empresas, licencias, certificaciones y planos.
- Guards y visibilidad por rol (solo `admin|editor` pueden editar; invitado/viewer solo leen).
- Validación: navegación en `/admin`, acciones CRUD y generación/exportación de QR.

## Pruebas
- Unit (Vitest): Policies por rol, QrService (checksum/imagen), Requests y visibilidad UI.
- E2E (Cypress):
  1. Login admin → crear → generar/descargar QR → editar.
  2. Invitado → `scan` con fixture → detalle → descargas.
  3. Permisos: bloqueo `/admin/*` y `403` al editar sin rol.
- Validación: todas las pruebas en verde.

## Documentación
- OpenSpec: endpoints QR y `pipeline-companies` con seguridad por rol y contratos.
- README: pasos para E2E/Unit, roles, rutas Admin y uso de demos.

## GitHub
- Verificación: `git fetch origin main`, `git diff origin/main`.
- Integración: `git rebase origin/main` (o merge) resolviendo conflictos.
- Validación previa al push: build y suites de pruebas.
- Auditoría `.env`/`.gitignore` y push a `origin/main`.

## Entrega
- Proyecto end-to-end con 3 demos QR, pruebas aprobadas y repositorio actualizado con documentación.