## Bloque 1: Backend (Policies y Endpoints)
- Implementar Policies: PipelinePolicy, CompanyPolicy, CertificationPolicy, BlueprintPolicy, OperatingLicensePolicy, PipelineCompanyPolicy (abilities: view/list público; create/update/delete admin|editor).
- Aplicar `can:*` en controladores `POST/PUT/DELETE` y confirmar `auth:sanctum` en rutas protegidas.
- Añadir endpoints compañías:
  - `PUT /api/pipeline-companies/{id}`: actualizar vínculo con validaciones; si `role=current_operator` y `is_current=true`, desactivar anterior.
  - `DELETE /api/pipeline-companies/{id}`: eliminar vínculo con reglas.
- Validaciones:
  - Ejecutar `php backend-app/artisan route:list` y `php backend-app/artisan migrate --force`.
  - Probar flujos: crear pipeline → `generate-qr` → descargar QR; crear/actualizar/eliminar vínculos respetando unicidad de operador actual.
  - Salud: `GET /api/health` responde `{status:'ok'}`.

## Bloque 2: Frontend (Admin CRUD)
- Completar `/admin/pipelines/:id/edit` con CRUD:
  - Empresas: alta/edición/baja de vínculos (rol, fechas, `is_current`).
  - Licencias: alta/edición con vigencia y estado.
  - Certificaciones: alta/edición/descarga (PDF/DWG/DXF, tamaño ≤50MB).
  - Planos: subida múltiple, versionado y descarga.
- Guards por rol: edición sólo para `admin|editor`; invitado/viewer sólo lectura.
- Validaciones:
  - `npm run dev -C frontend` → navegación en `/admin` y ejecución de acciones (generar/exportar QR, CRUD secciones) sin errores.

## Bloque 3: Pruebas (Unit y E2E)
- Unit (Vitest):
  - Tests para Policies por rol, QrService (checksum/imagen), Requests (reglas de validación), visibilidad UI.
  - Ejecutar `npm run test:run -C frontend` y asegurar verde.
- E2E (Cypress):
  - Caso 1: login admin → crear pipeline → generar y descargar QR → editar datos.
  - Caso 2: invitado → `/scan` con imagen fixture (QR) → detalle → descargas.
  - Caso 3: permisos: bloqueo `/admin/*` y `403` en edición sin rol.
  - Ejecutar `npm run cy:run -C frontend` con dev server activo; resultados verdes.

## Bloque 4: GitHub (Verificación y Push)
- Verificación diffs: `git fetch origin main`, `git diff origin/main`.
- Integración segura: preferir `git rebase origin/main`; si complejo, `git merge origin/main` con resolución de conflictos.
- Validación previa al push: `npm run build -C frontend`, `npm run test:run -C frontend`, `npm run cy:run -C frontend`; auditoría `.env` y `.gitignore` (no secretos; excluir `vendor/`, `node_modules/`, `dist/`).
- Publicar: `git add -A` → `git commit -m "feat: policies, pipeline-companies PUT/DELETE, admin CRUD, QR, tests"` → `git push -u origin main`.

## Bloque 5: Documentación
- OpenSpec: añadir endpoints QR y `pipeline-companies` con seguridad por rol y contratos (requests/responses).
- README: roles y permisos, rutas Admin, pasos de pruebas (Unit/E2E) y uso de demos QR; variables de entorno (`backend-app/.env`, `frontend/.env`).

## Criterios de Cierre
- Backend/Frontend validados sin errores; Unit/E2E en verde; repositorio GitHub actualizado; OpenSpec/README al día.

## Nota
Procederé bloque por bloque, validando al terminar cada uno y sólo entonces avanzar al siguiente hasta completar el 100%.