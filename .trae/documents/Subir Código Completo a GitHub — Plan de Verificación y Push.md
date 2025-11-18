## Objetivo
Subir todo el código del proyecto (backend-app, frontend, configs) al repositorio GitHub, verificando diffs y evitando subir archivos sensibles o dependencias pesadas.

## Verificación Local y Remoto
- Comprobar estado actual:
  - `git status`, `git branch --show-current` (debe ser `main`).
  - `git fetch origin main`, `git diff origin/main` para ver diferencias.
- Revisar que todos los directorios estén versionados:
  - `backend-app/**`, `frontend/**`, `.taskmaster/**`, `.trae/**`, `README.md`, `openapi.yaml`, `proposal.md`, `tasks.md`.

## Auditoría .gitignore y Sensibles
- Confirmar que `.gitignore` excluye:
  - `backend-app/vendor/`, `frontend/node_modules/`, `dist/`, `coverage/`, `.taskmaster/state.json`.
  - `.env` y archivos con credenciales (`backend-app/.env`, `frontend/.env`).
- Asegurar que no haya secretos en el repositorio.

## Build y Pruebas (previas al push)
- Frontend: `npm run build -C frontend` para asegurar compilación.
- Unit tests: `npm run test:run -C frontend`.
- (Opcional) E2E: `npm run cy:run -C frontend` si servidor local activo.

## Integración con Remoto
- Si no hay divergencia: `git merge --ff-only origin/main`.
- Si hay commits en ambos lados:
  - `git rebase origin/main` y resolver conflictos; si complejo, fallback `git merge origin/main`.

## Commit y Push
- `git add -A` (incluyendo backend-app y frontend).
- `git commit -m "feat: subir código completo (backend, frontend, admin UI, QR, seeds, tests)"`.
- `git push -u origin main`.

## Post-push
- Revisar el repositorio en GitHub: confirmar carpetas y archivos subidos.
- (Opcional) Crear release o tag.

## Contingencias
- Si `rebase/merge` falla: `git rebase --abort` o `git merge --abort`; crear rama `integration/main` y abrir PR.
- Si pruebas fallan: corregir y reintentar antes del push.

## Entrega
- Código completo en GitHub, pruebas verdes, sin secretos y documentación visible.

¿Procedo con esta verificación y subida del código ahora?