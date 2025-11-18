## Objetivo
Ejecutar pruebas end-to-end en local (XAMPP) usando Chrome DevTools MCP y luego continuar con las tareas pendientes.

## Plan de Pruebas (Chrome DevTools)
- Frontend
  - Abrir `http://localhost:5173/` (dev server) y verificar Home y `/health`.
  - Autenticación: ir a `/login`, acceder con `admin@example.com` / `Admin123!`, confirmar redirección y rol.
  - Admin UI: visitar `/admin/pipelines`, crear nueva tubería, generar QR, descargar QR y editar datos.
  - Escaneo QR
    - Modo cámara: `/scan` y verificar lectura de QR real.
    - Modo imagen: subir una imagen con QR (fixture) y confirmar navegación a detalle.
  - Detalle: confirmar la visualización de datos, descargas de certificaciones y planos.
- Backend
  - `GET` `http://localhost/qrtuberiaTrae/backend-app/public/index.php/api/health` → `status: ok`.
  - `POST /api/pipelines` crear; `POST /api/pipelines/{id}/generate-qr` generar; `GET /api/pipelines/{id}/qr` descargar.
  - Compañías: `POST/PUT/DELETE /api/pipeline-companies` validar reglas de operador actual.
  - Licencias/certificaciones/planos: listar y descargar.

## Validación y Reporte
- Confirmar que todas las vistas y endpoints responden correctamente sin errores en consola/red.
- Documentar resultados y capturas si aplica.

## Continuación de Tareas
- Implementar Policies específicas por modelo y Requests de validación restantes.
- Completar CRUD en formularios Admin para empresas, licencias, certificaciones y planos.
- Ejecutar Unit (Vitest) y E2E (Cypress) con fixtures de QR.
- Verificar diferencias con GitHub (`fetch + diff`), integrar (rebase/merge), correr build y suites, auditar `.env`, commit y push.

## Entregables
- Pruebas locales verificadas (frontend y backend), tareas completadas y repositorio remoto actualizado con documentación al día.