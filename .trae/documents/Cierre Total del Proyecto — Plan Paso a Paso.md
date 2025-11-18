## Objetivo
Completar 100%: autorización por roles (Policies), endpoints restantes de compañías, formularios Admin completos, pruebas E2E/Unit con fixtures y sincronización con GitHub (merge y push verificado).

## Backend
### 1. Policies por modelo
- Crear y registrar: PipelinePolicy, CompanyPolicy, CertificationPolicy, BlueprintPolicy, OperatingLicensePolicy, PipelineCompanyPolicy.
- Habilitar abilities: view/list (todos), create/update/delete (admin/editor).
- Aplicar `can:*` en controladores para POST/PUT/DELETE.

### 2. Endpoints compañías restantes
- `PUT /api/pipeline-companies/{id}`: actualizar vínculo (role, fechas, is_current); si `current_operator` y `is_current=true`, desactivar el previo.
- `DELETE /api/pipeline-companies/{id}`: eliminar vínculo.
- Requests de validación y respuestas JSON consistentes.

### 3. Validaciones y rendimiento
- Requests para companies/licenses/certifications/blueprints/pipeline_companies.
- Eager loading y cache Redis en búsqueda por `qr_code`.

## Frontend (Admin)
### 4. Formularios completos
- `/admin/pipelines/:id/edit`:
  - Empresas: alta/baja/edición de vínculos.
  - Licencias: CRUD con vigencia.
  - Certificaciones: creación/edición/descarga.
  - Planos: subida múltiple, versionado y descarga.
- Controles sólo visibles en `admin|editor`.

## Pruebas
### 5. E2E (Cypress)
- Caso A: login admin → crear pipeline → generar y descargar QR → editar datos.
- Caso B: invitado → `/scan` con imagen fixture → detalle → descargas.
- Caso C: permisos: viewer/invitado bloqueados en `/admin/*` y `403` en edición.
- Fixtures: `qr-demo-1.png`, `qr-demo-2.png`, `qr-demo-3.png`.

### 6. Unit (Vitest)
- Policies (por rol), QrService (checksum/imagen), Requests y visibilidad UI.

## Documentación
### 7. OpenSpec/README
- Añadir endpoints QR y pipeline-companies con seguridad por rol.
- Instrucciones para E2E/Unit y demos con QR.

## GitHub
### 8. Verificación y merge
- `git fetch origin main`, `git diff origin/main`, `git rebase origin/main` (o merge si aplica) y resolver conflictos.
- Verificación: `npm run build -C frontend`, `npm run test:run -C frontend`, `npm run cy:run -C frontend`.
- Auditoría de `.env`/credenciales y `.gitignore`.
- Commit final y `git push -u origin main`.

## Entrega
- Código con roles y Admin completo.
- 3 demos con QR verificados end-to-end.
- Pruebas E2E/Unit pasando y documentación actualizada.

## Confirmación
Al confirmar, implemento estas tareas en orden, ejecuto las suites y actualizo el repositorio tras verificación de merge.