## Objetivo
Comparar el estado local con el remoto, verificar el código (build, pruebas), resolver diferencias con merge limpio y actualizar el repositorio de GitHub sin introducir errores.

## Preparar entorno
- Confirmar rama activa: `git branch --show-current` debe ser `main`.
- Guardar cambios locales si hubiera sin rastreo: `git add -A && git stash -u` (opcional si hay trabajo no listo para commit).

## Sincronización y comparación
- Obtener remoto: `git fetch origin main`.
- Estado rápido: `git status`.
- Diffs contra remoto:
  - Cambios locales vs remoto: `git diff origin/main`.
  - Cambios locales staged: `git diff --cached`.
- Historial y divergencia:
  - `git log --oneline --decorate --graph --all -n 20`.

## Estrategia de integración
- Si no hay divergencia (FF posible): `git merge --ff-only origin/main` y continuar.
- Si hay commits en ambos lados:
  - Preferir rebase limpio: `git rebase origin/main`.
  - Resolver conflictos siguiendo prioridades del proyecto (mantener nuevas rutas, migraciones, policies y vistas admin); usar `git status` y `git diff` en cada conflicto.
  - Tras rebase: `git rebase --continue`.
- Si rebase es complejo, fallback: `git merge origin/main` y resolver conflictos manuales.

## Verificación de funcionalidad
- Backend:
  - Migraciones y seed: `php backend-app/artisan migrate --force` y si aplica `php backend-app/artisan db:seed --force`.
  - Salud API: `GET /api/health`.
- Frontend:
  - Build: `npm run build -C frontend`.
  - Vista de salud: `http://localhost:5173/health` (en preview) o servir `dist/`.
- Pruebas:
  - Unit: `npm run test:run -C frontend`.
  - E2E (si server activo): `npm run cy:run -C frontend`.

## Auditoría previa al push
- Comprobar archivos sensibles:
  - Evitar que `.env`, claves o credenciales estén en seguimiento: `git ls-files | findstr ".env"`.
  - Revisar `.gitignore` correcto.
- Revisar que los nuevos archivos (Policies, QrService, controladores y vistas admin) estén incluidos.
- Confirmar que `openapi.yaml`, `proposal.md`, `tasks.md` están actualizados si aplica.

## Commit y push
- Crear commit:
  - `git add -A`
  - `git commit -m "feat: roles/policies, QR endpoints, admin UI, seeds demos, tests"`
- Push:
  - `git push -u origin main`

## Post-push
- Verificar en GitHub: cambios visibles en rutas clave y archivos nuevos.
- Abrir PR si se desea revisión (si working branch distinta de main).

## Contingencias
- Si fallan pruebas/compilación, revertir rebase/merge: `git rebase --abort` o `git merge --abort` y reevaluar.
- Si el remoto requiere resolución distinta, crear rama de integración: `git checkout -b integration/main` y abrir PR.

## Entregables
- Repositorio remoto actualizado con cambios verificados.
- Pruebas unitarias/E2E ejecutadas y reporte de resultados.
- Documentación (README/OpenSpec) ajustada si hubo modificaciones en endpoints o flujos.

## Siguiente paso
Tras tu confirmación, ejecutaré los comandos anteriores, resolveré cualquier conflicto, correré las verificaciones y haré el push a `origin/main`. 