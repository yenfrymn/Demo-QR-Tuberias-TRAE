## Objetivo
Cerrar todas las tareas pendientes: Policies por modelo, endpoints de compañías (PUT/DELETE), formularios Admin completos, pruebas E2E/Unit con fixtures y actualización en GitHub tras verificación.

## Backend
- Implementar Policies para Pipeline, Company, Certification, Blueprint, OperatingLicense y PipelineCompany; aplicar `can:*` en POST/PUT/DELETE.
- Añadir `PUT/DELETE /api/pipeline-companies/{id}` con regla de unicidad del operador actual.
- Completar Requests de validación y optimizar consultas (eager loading, cache por `qr_code`).

## Frontend (Admin)
- Completar `/admin/pipelines/:id/edit` con CRUD de empresas, licencias, certificaciones y planos.
- Mantener guards y visibilidad de controles según rol (`admin|editor`).

## Pruebas
- E2E (Cypress):
  - Login admin → crear pipeline → generar/descargar QR → editar datos.
  - Invitado → `scan` con imagen fixture → detalle → descargas.
  - Permisos: viewer/invitado bloqueados en `/admin/*` y `403` en edición.
- Unit (Vitest): Policies, QrService, Requests y visibilidad de UI.

## Documentación y GitHub
- Actualizar OpenSpec/README con endpoints y seguridad por rol.
- Verificar y sincronizar con remoto: fetch/diff, rebase/merge seguro, build y pruebas, auditoría `.env`; commit y push a `origin/main`.

## Entrega
- Proyecto funcional end-to-end con 3 demos QR, pruebas aprobadas y repositorio actualizado.