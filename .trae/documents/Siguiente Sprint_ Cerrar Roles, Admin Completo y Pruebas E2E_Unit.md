## Objetivo
Cerrar el desarrollo conforme a la planificación: terminar módulo Admin completo (empresas, licencias, certificaciones, planos), robustecer autorización, completar E2E/Unit y actualizar documentación.

## Backend
1) Autorización específica por modelo
- Crear Policies para: Pipeline, Company, Certification, Blueprint, OperatingLicense y PipelineCompany (create/update/delete solo admin/editor; view para todos).
- Aplicar `Gate::authorize`/`can:` en cada acción de los controladores.

2) Endpoints restantes
- `PUT/DELETE /api/pipeline-companies/{id}` con regla de unicidad `is_current=true`.
- Mejorar descarga de QR: validar headers, content-type y expiración.

3) Validaciones y rendimiento
- Validaciones en `PipelineRequest` y nuevas requests (Company, License, Certification, Blueprint, PipelineCompany).
- Eager loading en listados y detalle; cache Redis en búsqueda por `qr_code`.

## Frontend
1) Admin UI — completar formularios
- En `/admin/pipelines/:id/edit`: secciones para:
  - Empresas: añadir/remover vínculos (rol, fechas, `is_current`).
  - Licencias: crear/actualizar con vigencia.
  - Certificaciones: subir/editar estado y descarga.
  - Planos: subida múltiple, versionado y descarga.
- Botones de “Generar QR” y “Exportar QR” ya integrados; añadir “Compartir” (copiar link).

2) Control por rol
- Mostrar/ocultar secciones de edición según `role` del store.
- Redirigir a Login si usuario intenta `/admin/*` sin rol adecuado.

## Pruebas
1) E2E (Cypress)
- Caso 1: login admin → crear pipeline → generar y descargar QR → editar datos.
- Caso 2: invitado → `/scan` con imagen fixture → navegar a detalle → descargar certificación/blueprint.
- Caso 3: viewer/invitado → bloqueo de rutas `/admin/*` y `403` en POST.
- Fixtures: `qr-demo-1.png`, `qr-demo-2.png`, `qr-demo-3.png` (generadas a partir de los códigos seed).

2) Unit (Vitest)
- Policies: `can('update', Pipeline)` por roles.
- QrService: checksum y archivo generado.
- Requests: reglas de validación principales.
- UI: visibilidad por rol en componentes admin.

## Documentación
- Actualizar OpenSpec (endpoints de QR y pipeline-companies, seguridad por rol, contratos).
- Actualizar README (roles, rutas Admin, cómo correr E2E y Unit, y demos con QR).

## Entregables y verificación
- Código backend/frontend completo con roles y edición de todos los elementos.
- 3 demos funcionales con QR.
- Suite E2E/Unit pasando en local.
- Documentación (OpenSpec/README) actualizada.

## Próximo paso
Tras esta confirmación, implementaré las Policies, endpoints restantes de compañías, completaré los formularios admin y escribiré/ejecutaré las suites E2E/Unit con fixtures, cerrando el proyecto según la planificación.