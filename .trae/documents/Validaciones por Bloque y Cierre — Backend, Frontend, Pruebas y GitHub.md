## Backend — Validación
- Verificar rutas y autorización:
  - Ejecutar `php backend-app/artisan route:list` y confirmar `auth:sanctum` + `can:*` en `POST/PUT/DELETE`.
  - Rutas clave: Auth, Pipelines CRUD, Certifications, Blueprints, Licenses, Reports, QR (`generate-qr`/`qr`), Pipeline Companies.
- Migraciones:
  - Ejecutar `php backend-app/artisan migrate --force` (sin errores).
- Probar flujos:
  - `POST /api/pipelines` crea tubería; `POST /api/pipelines/{id}/generate-qr` devuelve `download_url`; `GET /api/pipelines/{id}/qr` descarga.
  - `POST/PUT/DELETE /api/pipeline-companies` respeta unicidad de operador actual.
- Salud API: `GET /api/health` debe responder `{status:'ok'}`.

## Frontend — Validación
- Iniciar dev server: `npm run dev -C frontend` y abrir `http://localhost:5173/`.
- Navegación Admin:
  - `/admin/pipelines` listado; `/admin/pipelines/new` creación; `/admin/pipelines/:id/edit` edición.
  - Acciones: “Generar/Exportar QR”, CRUD de empresas/licencias/certificaciones/planos.
- Guards por rol:
  - Usuario `admin|editor` accede; invitado/viewer sólo lectura (`scan` y detalle sin edición).

## Pruebas — Unit y E2E
- Unit (Vitest): `npm run test:run -C frontend` — Policies por rol, `QrService`, Requests y visibilidad UI.
- E2E (Cypress): `npm run cy:run -C frontend` con server activo
  - Caso 1: login admin → crear → generar/descargar QR → editar.
  - Caso 2: invitado → `scan` imagen → detalle → descargas.
  - Caso 3: permisos: bloqueo `/admin/*` y `403` en edición sin rol.

## GitHub — Verificación y Push
- `git fetch origin main` y `git diff origin/main` para revisar diferencias.
- Integración segura:
  - Preferir `git rebase origin/main`; si complejo, `git merge origin/main`.
- Validar antes del push:
  - `npm run build -C frontend`, `npm run test:run -C frontend`, `npm run cy:run -C frontend`.
  - Auditoría `.env` y `.gitignore` (no subir secretos; excluir `vendor/`, `node_modules/`, `dist/`).
- Publicar:
  - `git add -A` → `git commit -m "feat: policies, pipeline-companies PUT/DELETE, admin CRUD, QR, tests"` → `git push -u origin main`.

## Documentación
- OpenSpec: incluir endpoints QR y pipeline-companies con seguridad por rol y contratos.
- README: roles, rutas Admin, pasos de pruebas y demos QR.

## Criterios de Cierre
- Backend y Frontend validados sin errores.
- Pruebas unitarias y E2E en verde.
- Repositorio GitHub actualizado y documentación al día.

## Próximo Paso
Procedo a ejecutar las validaciones del Backend de acuerdo a este plan; al finalizar el bloque, informaré resultados y continuaré con Frontend, Pruebas y GitHub hasta el cierre total.