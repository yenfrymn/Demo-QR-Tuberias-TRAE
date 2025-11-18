## Bloque 1: Backend
- Policies: crear y registrar Policies por modelo; aplicar `can:*` en `POST/PUT/DELETE`.
- Endpoints compañías: añadir `PUT/DELETE /api/pipeline-companies/{id}` con unicidad del operador actual.
- Validación:
  - `php backend-app/artisan route:list` y `php backend-app/artisan migrate --force`.
  - Flujos: `POST /api/pipelines`, `POST /api/pipelines/{id}/generate-qr`, `GET /api/pipelines/{id}/qr`, `POST/PUT/DELETE /api/pipeline-companies`.
  - Salud: `GET /api/health` ok.

## Bloque 2: Frontend
- Completar CRUD Admin en `/admin/pipelines/:id/edit` (empresas/licencias/certificaciones/planos).
- Guards por rol: edición sólo `admin|editor`; invitado/viewer sólo lectura.
- Validación:
  - `npm run dev -C frontend`, navegación admin y operaciones (generar/exportar QR, CRUD) sin errores.

## Bloque 3: Pruebas
- Unit (Vitest): Policies por rol, `QrService`, Requests, visibilidad UI → `npm run test:run -C frontend`.
- E2E (Cypress):
  - Login admin → crear → generar/descargar QR → editar.
  - Invitado → scan imagen → detalle → descargas.
  - Bloqueo `/admin/*` y `403` sin rol → `npm run cy:run -C frontend`.

## Bloque 4: GitHub
- Verificación: `git fetch origin main`, `git diff origin/main`.
- Integración: `git rebase origin/main` (o `git merge origin/main`).
- Validación previa: `npm run build -C frontend`, `npm run test:run -C frontend`, `npm run cy:run -C frontend`.
- Auditoría `.env`/`.gitignore`.
- Push: `git add -A`, commit y `git push -u origin main`.

## Bloque 5: Documentación
- OpenSpec: endpoints QR y compañías con seguridad por rol.
- README: roles, rutas Admin, pasos de pruebas y demos.

## Criterios de Cierre
- Backend/Frontend validados sin errores; Unit/E2E en verde; repositorio actualizado y documentación al día.

## Ejecución
Procedo bloque por bloque y reporto validaciones al terminar cada uno antes de pasar al siguiente, hasta completar el 100%.