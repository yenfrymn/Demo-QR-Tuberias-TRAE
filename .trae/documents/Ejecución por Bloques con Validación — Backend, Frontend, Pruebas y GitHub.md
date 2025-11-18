## Objetivo

Ejecutar cada bloque (backend, frontend, pruebas y GitHub) en orden, validando en cada etapa antes de avanzar, hasta finalizar el proyecto.

## Bloque 1: Backend (Policies y Endpoints)

* Implementar/ajustar Policies por modelo y aplicar `can:*` en POST/PUT/DELETE.

* Completar endpoints compañías `PUT/DELETE /api/pipeline-companies/{id}` con regla de operador actual único.

* Validar:

  * `php backend-app/artisan route:list` (rutas visibles).

  * `php backend-app/artisan migrate --force` (sin errores).

  * `GET http://localhost/qrtuberiaTrae/backend-app/public/index.php/api/health` → `{status:'ok'}`.

  * Flujos API:

    * `POST /api/pipelines` crea tubería.

    * `POST /api/pipelines/{id}/generate-qr` genera y devuelve `download_url`.

    * `GET /api/pipelines/{id}/qr` descarga QR.

    * `POST/PUT/DELETE /api/pipeline-companies` aplican reglas de `is_current`.

* Criterio de éxito: sin errores en rutas/migraciones; respuestas JSON correctas.

## Bloque 2: Frontend (Admin UI)

* Completar formularios en `/admin/pipelines/:id/edit` para empresas, licencias, certificaciones y planos.

* Mantener guards por rol (`admin|editor`); ocultar edición a invitado/viewer.

* Validar:

  * `npm run dev -C frontend` y abrir `http://localhost:5173/`.

  * Acceso admin: `/admin/pipelines`, `/admin/pipelines/new`, `/admin/pipelines/:id/edit`.

  * Botones “Generar QR” y “Exportar QR” operativos.

  * Invitado: acceso a `/scan` (cámara/imagen) y `/pipelines/:id` sin login; edición oculta.

* Criterio de éxito: navegación y acciones correctas; guards funcionando.

## Bloque 3: Pruebas (Unit y E2E)

* Unit (Vitest):

  * `npm run test:run -C frontend`.

  * Verifica Policies (roles), QrService (checksum/imagen), Requests y visibilidad UI.

* E2E (Cypress):

  * `npm run cy:run -C frontend` (con dev server activo).

  * Casos:

    1. Login admin → crear → generar/descargar QR → editar.
    2. Invitado → `/scan` con fixture imagen → detalle → descargas.
    3. Permisos: viewer/invitado bloqueados en `/admin/*` y `403` en edición.

* Criterio de éxito: todas las pruebas pasan.

## Bloque 4: GitHub (Verificación, Merge y Push)

* Preparar:

  * `git fetch origin main`

  * `git diff origin/main` (revisar diferencias).

  * Integrar: `git rebase origin/main` (preferido) o `git merge origin/main` si aplica.

* Verificar antes de push:

  * `npm run build -C frontend`.

  * `npm run test:run -C frontend`.

  * `npm run cy:run -C frontend`.

  * Auditoría `.env` y `.gitignore` (no subir credenciales).

* Publicar:

  * `git add -A`

  * `git commit -m "feat: roles/policies, QR endpoints, admin UI, seeds demos, tests"`

  * `git push -u origin main`

* Criterio de éxito: cambios reflejados en GitHub; sin conflictos; pruebas y build ok.

## Contingencias

* Si rebase/merge falla: `git rebase --abort` o `git merge --abort`, crear rama `integration/main` y PR.

* Si pruebas fallan: corregir y re-ejecutar hasta verde.

## Entrega Final

* Proyecto funcional end-to-end con 3 demos QR.

* Pruebas unitarias y E2E aprobadas.

* Repositorio GitHub actualizado con documentación (OpenSpec/README) al día.

¿Procedo con la ejecución por bloques y validación en cada etapa tal como se detalla arriba?
