## Bloque 1: Backend
- Revisar rutas y Policies: listar rutas y confirmar middleware/can.* en POST/PUT/DELETE
- Verificar migraciones: ejecutar migraciones en limpio y salud API
- Probar endpoints críticos: creación de pipeline, generación/descarga de QR, vínculos de compañías con regla de operador actual único
- Criterio de éxito: respuestas JSON correctas, sin errores y reglas aplicadas

## Bloque 2: Frontend (Admin)
- Completar formularios en /admin (empresas, licencias, certificaciones, planos)
- Activar guards por rol en Router y visibilidad de controles
- Validar navegación: listado, nuevo y edición; generar/exportar QR y acciones CRUD
- Criterio de éxito: UI consistente, permisos correctos y operaciones funcionales

## Bloque 3: Pruebas (Unit y E2E)
- Unit (Vitest): Policies por rol, QrService (checksum/imagen), Requests y visibilidad UI
- E2E (Cypress):
  - Login admin → crear → generar/descargar QR → editar datos
  - Invitado → scan con imagen fixture → detalle → descargas
  - Permisos: bloqueo /admin para viewer/invitado y 403 en edición
- Criterio de éxito: todas las pruebas en verde

## Bloque 4: GitHub (Verificación y Push)
- Verificar diferencias: fetch/diff y decidir rebase/merge
- Integración segura: rebase o merge resolviendo conflictos
- Validar antes de push: build y suites de pruebas
- Auditoría: asegurar que .env/credenciales no se suben y .gitignore está correcto
- Commit y push: publicar en origin/main

## Entrega Final
- Proyecto completo end-to-end con 3 demos QR, pruebas aprobadas y repositorio actualizado con documentación (OpenSpec/README)