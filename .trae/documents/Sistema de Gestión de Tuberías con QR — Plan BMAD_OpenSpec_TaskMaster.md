## Objetivo
Implementar un sistema web y móvil que, mediante lectura de códigos QR, entregue información técnica y legal de tuberías petroleras (reportes, planos, certificaciones, empresas, licencias) con experiencia mobile-first y operación segura sobre Laravel 12 + MySQL 8, frontend en Vue.js 3 + Alpine.js y despliegue en VPS con cPanel/WHM.

## Fase 1: Planning (BMAD-METHOD)
### Problema, Objetivos y Métricas
- Problema: información crítica dispersa, bajo tiempo de respuesta y nula trazabilidad en campo.
- Objetivos: consulta <2 min; centralización documental; trazabilidad de certificaciones/licencias; operación offline-first.
- Métricas: 95% consultas <2 min; 100% documentación digital; -80% solicitudes manuales; 0 pérdida de datos.

### Stakeholders y Alcance (MVP)
- Stakeholders: Inspectores, Operaciones, Legal, Empresas operadoras.
- MVP: escáner QR; base de datos georreferenciada de tuberías; repositorio de planos; certificaciones con adjuntos; registro de empresas y operador actual; licencias con alertas; reportes PDF.

### Riesgos y Mitigación
- Tamaño de planos: compresión, previsualización, CDN/S3.
- Conectividad en campo: PWA con caché y cola de sincronización.
- Seguridad: HTTPS, roles, auditoría, cifrado.

### Cronograma (Sprints)
- Sprint 1 (Semana 1-2): Backend y BD.
- Sprint 2 (Semana 3): Frontend core y QR.
- Sprint 3 (Semana 4): Reportes, notificaciones, administración, búsqueda, offline.
- Sprint 4 (Semana 5): Testing, optimización, deploy y UAT.

## Fase 2: Specification (OpenSpec)
### Artefactos a generar tras aprobación
- `proposal.md`: alcance técnico, arquitectura, endpoints, contratos JSON, reglas de negocio, esquema de BD, seguridad, rendimiento.
- OpenAPI/Swagger: especificación de API con ejemplos de request/response y errores estándar.

### Endpoints (resumen)
- Auth: `POST /api/login`, `POST /api/logout`, `POST /api/refresh`.
- Pipelines: `GET /api/pipelines?qr_code={code}`, `GET/POST/PUT/DELETE /api/pipelines/{id}`.
- Certificaciones: `GET /api/pipelines/{id}/certifications`, `POST /api/certifications`, `GET /api/certifications/{id}/download`.
- Planos: `GET /api/pipelines/{id}/blueprints`, `POST /api/blueprints/upload`, `GET /api/blueprints/{id}/download`.
- Licencias: `GET /api/pipelines/{id}/license`, `POST /api/licenses`, `GET /api/licenses/expiring`.
- Reportes: `POST /api/reports/generate`, `GET /api/reports/{id}/download`.

### Contratos JSON y Reglas
- Contrato de respuesta incluye `data.pipeline {...}` y `meta { timestamp, version }`.
- Reglas de negocio:
  - Alertas de vencimiento: 90/60 días; cambio automático a `expired`.
  - Operador actual único por tubería; historial mantenido.
  - Documentos: ≤50MB; formatos PDF/DWG/DXF/PNG/JPG; versionado; borrado físico solo admin.
  - QR: formato `PIPE-{YEAR}-{SEQUENTIAL}` con checksum; unicidad a nivel BD.

## Fase 3: Execution (Task-Master-AI)
### Artefacto a generar tras aprobación
- `tasks.md`: desglose por sprints y tareas con estimaciones, dependencias y responsables (Backend/Frontend/DevOps), criterios de aceptación y entregables.

### Resumen de tareas clave
- Backend foundation: instalación, migraciones, modelos/relaciones, autenticación Sanctum, CRUD + búsqueda por QR, certificaciones/planos/licencias, jobs de vencimiento, generación de PDFs.
- Frontend core: proyecto Vue 3 (Vite), Router, Pinia, Tailwind, Axios; login; layout mobile-first; escáner `html5-qrcode`; vista de detalle; descargas.
- Avanzadas: reportes; notificaciones; panel admin; búsqueda y filtros; PWA offline.
- Testing/Deploy: unit y feature tests; E2E con Cypress; optimización; documentación; VPS/cPanel; SSL y backups.

## Arquitectura Técnica
- Backend: Laravel 12 (PHP 8.3), Sanctum, Eloquent, Jobs (queue), Policies/Gates, Events/Observers.
- Frontend: Vue 3 (Composition API), Pinia, Router, Tailwind, Alpine.js para micro-interacciones, `html5-qrcode` para cámara.
- Datos: MySQL 8 (índices y constraints), Redis (caché/notificaciones), S3-compatible (documentos), CDN para estáticos.
- Seguridad: HTTPS, CORS, rate limiting, cifrado de documentos sensibles, logs de auditoría y monitoreo.
- Offline: Service Worker + Workbox; caché selectiva de endpoints GET; cola de requests y sincronización cuando retorna conectividad.

## Modelo de Datos (normalizado)
- `pipelines`: `id`, `qr_code` (único/indexado), `name`, `location` (lat/lng/address), `diameter`, `material`, `installation_date`, `status`, `description`, `created_at`, `updated_at`.
- `companies`: `id`, `name`, `tax_id`, `type`, `contact_info` (JSON), timestamps.
- `pipeline_companies`: `id`, `pipeline_id` FK, `company_id` FK, `role`, `start_date`, `end_date` (nullable), `is_current` (bool). Invariante: solo un `current_operator` por tubería (validación en aplicación y trigger opcional).
- `certifications`: `id`, `pipeline_id` FK, `type`, `certification_number`, `issued_date`, `expiry_date`, `issuing_body`, `document_path`, `status`.
- `operating_licenses`: `id`, `pipeline_id` FK, `license_number`, `issued_by`, `issue_date`, `expiry_date`, `status`, `document_path`.
- `blueprints`: `id`, `pipeline_id` FK, `title`, `file_path`, `file_type`, `version`, `upload_date`, `uploaded_by`.
- `inspection_reports`: `id`, `pipeline_id` FK, `inspector_id` FK, `report_date`, `report_type`, `findings` (JSON), `document_path`.

## API y Contratos (detalle a formalizar en OpenAPI)
- Errores estándar: `400` validación, `401` auth, `403` permisos, `404` no encontrado, `409` conflicto (unicidad/estado), `422` validación fina, `500` interno.
- Paginación: parámetros `page`, `per_page`; metadatos `links` y `meta`.
- Filtros: estado, ubicación (bounding box), empresa, vencimientos.

## UX (mobile-first)
- Flujo: login → dashboard → escanear QR → detalle de tubería → acciones (descargar, ver plano, reporte, compartir).
- Principios: alto contraste, botones ≥48px, feedback inmediato, indicadores offline/online, accesibilidad.

## Deploy y Operación
- VPS con cPanel/WHM: dominio, SSL Let’s Encrypt, PHP 8.3, extensión Redis, programar backups diarios.
- CI/CD: build de frontend (Vite), assets minificados, variables de entorno seguras.
- Storage: S3/MinIO; firma de URLs de descarga; antivirus opcional al subir.
- Monitoreo: logs centralizados, alertas de jobs fallidos, métricas de rendimiento.

## Testing y Calidad
- Backend: unit y feature tests (Sanctum, Policies, CRUD, Jobs); cobertura ≥80%.
- Frontend: unit de components, integración y E2E con Cypress.
- Performance: evitar N+1, eager loading, índices; Lighthouse para PWA.

## Entregables tras aprobación
- `proposal.md` con OpenSpec completo.
- `openapi.yaml/json` de la API.
- `tasks.md` con Task-Master-AI (estimaciones, dependencias, responsables, criterios de aceptación).
- Plantillas iniciales de reportes PDF (Blade) y estructura de proyecto.

## Próximos pasos
- Confirmar el plan para generar los artefactos (`proposal.md`, `openapi`, `tasks.md`) y preparar la inicialización del proyecto en el repositorio (`Laravel 12` + `Vue 3`) y configuración de entorno. 