## Objetivo
Implementar Policies por modelo, completar endpoints de compañías, terminar formularios Admin, y escribir/ejecutar suites E2E/Unit con fixtures para cerrar el proyecto.

## Policies y Autorización
- Crear Policies: `PipelinePolicy`, `CompanyPolicy`, `CertificationPolicy`, `BlueprintPolicy`, `OperatingLicensePolicy`, `PipelineCompanyPolicy` con `view|create|update|delete`.
- Registrar Policies y aplicar `can:*` en controladores (POST/PUT/DELETE restringidos a `admin|editor`; `GET` abierto).

## Endpoints Compañías
- Añadir `PUT /api/pipeline-companies/{id}` y `DELETE /api/pipeline-companies/{id}`.
- Regla: al setear `current_operator` con `is_current=true`, desactivar el anterior para la misma tubería.
- Validaciones de request y respuestas JSON consistentes.

## Admin Formularios
- Completar `/admin/pipelines/:id/edit` con secciones:
  - Empresas: crear/actualizar/eliminar vínculos (rol, fechas, `is_current`).
  - Licencias: CRUD con vigencia y validaciones.
  - Certificaciones: subida/estado/descarga.
  - Planos: subida múltiple, versionado y descarga.
- Controles visibles solo para `admin|editor` (guards + UI).

## Pruebas E2E y Unit
- E2E (Cypress):
  - Login admin → crear pipeline → generar/descargar QR → editar datos.
  - Invitado → `/scan` con imagen fixture → detalle → descargas.
  - Permisos: viewer/invitado bloqueados en `/admin/*` y `403` en edición.
- Unit (Vitest): Policies por rol; QrService (checksum/imagen); Requests y visibilidad UI.
- Fixtures: `cypress/fixtures/qr-demo-{1,2,3}.png` generadas desde códigos seed.

## Documentación
- Actualizar OpenSpec con Policies y endpoints nuevos; contratos y seguridad por rol.
- Actualizar README con pasos de E2E/Unit y demos.

## Verificación y Entrega
- Ejecutar `npm run cy:run -C frontend` y `npm run test:run -C frontend`.
- Validar manualmente el flujo Admin y lectura invitado (cámara/imagen).