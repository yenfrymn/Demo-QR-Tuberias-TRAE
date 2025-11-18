## Backend
- Implementar Policies por modelo (Pipeline, Company, Certification, Blueprint, OperatingLicense, PipelineCompany).
- Registrar Policies y aplicar `can:*` en controladores para `POST/PUT/DELETE`.
- Añadir endpoints compañías:
  - `PUT /api/pipeline-companies/{id}`: actualizar vínculo con validaciones y unicidad de operador actual (`is_current=true` desactiva anteriores).
  - `DELETE /api/pipeline-companies/{id}`: eliminar vínculo con reglas.
- Validación:
  - `php backend-app/artisan route:list` (rutas y middleware).
  - `php backend-app/artisan migrate --force`.
  - Flujos API: crear pipeline, `POST /api/pipelines/{id}/generate-qr`, `GET /api/pipelines/{id}/qr`, `POST/PUT/DELETE /api/pipeline-companies`.

## Frontend
- Completar CRUD en `/admin/pipelines/:id/edit`:
  - Empresas: crear/editar/eliminar vínculos (rol, fechas, `is_current`).
  - Licencias: alta/edición con vigencia.
  - Certificaciones: subida/estado/descarga.
  - Planos: subida múltiple, versionado y descarga.
- Mantener guards por rol en Router y ocultar edición para `viewer`/invitado.
- Validación:
  - `npm run dev -C frontend` → `http://localhost:5173/`.
  - Navegación admin (listado/nuevo/editar) y acciones (generar/exportar QR, CRUD).

## Pruebas
- Unit (Vitest):
  - Policies por rol, `QrService` (checksum/imagen), Requests y visibilidad UI.
  - Ejecutar: `npm run test:run -C frontend`.
- E2E (Cypress):
  - Casos:
    1) Login admin → crear → generar/descargar QR → editar.
    2) Invitado → `scan` imagen → detalle → descargas.
    3) Permisos: bloqueo `/admin/*` y `403` en edición sin rol.
  - Ejecutar: `npm run cy:run -C frontend` (con dev server activo).

## GitHub
- Verificar diffs:
  - `git fetch origin main`
  - `git diff origin/main`
- Integrar con `rebase` o `merge`:
  - Preferido: `git rebase origin/main`; si complejo, `git merge origin/main`.
- Validar antes de push:
  - `npm run build -C frontend`
  - `npm run test:run -C frontend`
  - `npm run cy:run -C frontend`
  - Auditoría `.env` y `.gitignore` (no secretos, excluir `vendor/`, `node_modules/`, `dist/`).
- Publicar:
  - `git add -A`
  - `git commit -m "feat: policies, pipeline-companies PUT/DELETE, admin CRUD, QR, tests"`
  - `git push -u origin main`

## Entrega y Validación Final
- Proyecto end-to-end con 3 demos QR.
- Pruebas unitarias y E2E aprobadas.
- Repositorio actualizado y documentación al día (OpenSpec/README).