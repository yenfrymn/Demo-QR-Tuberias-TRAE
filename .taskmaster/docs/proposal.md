# Propuesta: Sistema de Gestión de Tuberías con QR

## Resumen Ejecutivo
Sistema web/móvil para gestionar y consultar información de tuberías petroleras mediante códigos QR, entregando reportes técnicos, planos, certificaciones, datos de empresas y licencias vigentes. Arquitectura mobile-first con backend Laravel 12 + MySQL 8 y frontend Vue.js 3 + Alpine.js, despliegue en VPS con cPanel/WHM.

## Arquitectura Técnica
- Backend: Laravel 12 (PHP 8.3), Sanctum (auth), Eloquent ORM, Jobs/Queues, Policies/Gates, Spatie Media Library (archivos), Barryvdh DomPDF (PDF), Laravel Excel (exports), Redis (caché/colas).
- Frontend: Vue 3 (Composition API), Pinia, Vue Router, Tailwind CSS, Alpine.js (micro-interacciones), html5-qrcode (escáner QR), Axios.
- Datos: MySQL 8 (índices y constraints), S3-compatible (documentos), CDN para estáticos, Redis.
- Seguridad: HTTPS, CORS, rate limiting, cifrado de documentos sensibles, logs de auditoría, backups diarios.
- Offline: PWA (Service Worker + Workbox), caché GET, cola de sincronización para acciones en campo.

## Modelo de Datos
### Tablas
- pipelines: id, qr_code (unique), name, location (lat, lng, address), diameter, material, installation_date, status, description, timestamps.
- companies: id, name, tax_id, type (initiator/operator), contact_info (JSON), timestamps.
- pipeline_companies: id, pipeline_id FK, company_id FK, role (initiator/current_operator), start_date, end_date (nullable), is_current (bool). Invariante: solo un operador actual por tubería (validación de aplicación y/o trigger).
- certifications: id, pipeline_id FK, type, certification_number, issued_date, expiry_date, issuing_body, document_path, status.
- operating_licenses: id, pipeline_id FK, license_number, issued_by, issue_date, expiry_date, status, document_path.
- blueprints: id, pipeline_id FK, title, file_path, file_type (PDF/DWG/DXF), version, upload_date, uploaded_by (user_id).
- inspection_reports: id, pipeline_id FK, inspector_id FK, report_date, report_type, findings (JSON), document_path.

## Endpoints (Resumen)
### Autenticación
- POST /api/login
- POST /api/logout
- POST /api/refresh

### Tuberías
- GET /api/pipelines?qr_code={code}
- GET /api/pipelines/{id}
- POST /api/pipelines
- PUT /api/pipelines/{id}
- DELETE /api/pipelines/{id}

### Certificaciones
- GET /api/pipelines/{id}/certifications
- POST /api/certifications
- GET /api/certifications/{id}/download

### Planos
- GET /api/pipelines/{id}/blueprints
- POST /api/blueprints/upload
- GET /api/blueprints/{id}/download

### Licencias
- GET /api/pipelines/{id}/license
- POST /api/licenses
- GET /api/licenses/expiring

### Reportes
- POST /api/reports/generate
- GET /api/reports/{id}/download

## Contratos JSON (Ejemplo)
```json
{
  "data": {
    "pipeline": {
      "id": 1,
      "qr_code": "PIPE-2024-001",
      "name": "Pipeline Norte Sección A",
      "location": {
        "lat": 4.7110,
        "lng": -74.0721,
        "address": "Sector Industrial Norte"
      },
      "specifications": {
        "diameter": "24 pulgadas",
        "material": "ASTM A53 Grade B",
        "length": "15.5 km",
        "pressure_rating": "150 PSI"
      },
      "companies": {
        "initiator": {
          "id": 1,
          "name": "PetroStart Inc.",
          "start_date": "2020-01-15"
        },
        "current_operator": {
          "id": 2,
          "name": "OilFlow Corporation",
          "start_date": "2023-06-01",
          "license": {
            "number": "LIC-2024-001",
            "issued_by": "ANH",
            "expiry_date": "2026-12-15",
            "status": "active",
            "document_url": "/api/licenses/1/download"
          }
        }
      },
      "certifications": [
        {
          "id": 1,
          "type": "ISO 9001",
          "number": "ISO9001-2024-COL-001",
          "issued_date": "2024-01-10",
          "expiry_date": "2026-01-10",
          "status": "valid",
          "document_url": "/api/certifications/1/download"
        }
      ],
      "blueprints": [
        {
          "id": 1,
          "title": "Diseño Estructural Principal",
          "file_type": "PDF",
          "version": "v2.1",
          "upload_date": "2024-03-20",
          "download_url": "/api/blueprints/1/download"
        }
      ]
    }
  },
  "meta": {
    "timestamp": "2024-11-18T10:30:00Z",
    "version": "1.0"
  }
}
```

## Reglas de Negocio
1. Alertas de vencimiento:
   - Certificaciones: notificar 90 días antes, estado `expired` automático al vencer.
   - Licencias: notificar 60 días antes, estado `expired` automático al vencer.
2. Gestión de empresas:
   - Un único `current_operator` por tubería; historial de operadores anterior preservado.
   - Operador actual debe tener licencia vigente.
3. Documentos:
   - Tamaño máximo: 50MB; formatos: PDF, DWG, DXF, PNG, JPG.
   - Versionado automático en planos; borrado físico solo por admin.
4. Códigos QR:
   - Generación: `PIPE-{YEAR}-{SEQUENTIAL}` con checksum.
   - Unicidad garantizada en BD y validador de servicio.

## Seguridad y Cumplimiento
- Autenticación: Laravel Sanctum (JWT-like) para API.
- Autorización: Policies/Gates por rol (admin, inspector, viewer).
- Transporte: HTTPS y HSTS; CORS controlado.
- Protección: rate limiting, validación de entrada, sanitización.
- Auditoría: logs de acciones críticas.
- Backups: diarios y probados.

## Rendimiento y Escalabilidad
- Índices en campos de búsqueda (qr_code, pipeline_id, expiry_date).
- Eager loading y evitar N+1; caching selectivo con Redis.
- CDN para archivos estáticos; S3 para documentos.

## Entregables
- OpenAPI (`openapi.yaml`) con contratos de API.
- Plantilla de reporte PDF (Blade) y endpoints de generación.
- PWA habilitada con caché y sincronización.

## Aceptación
- 95% de consultas <2 min verificadas en pruebas de campo.
- 100% documentación requerida digitalizada y accesible por QR.
- Alertas de vencimiento verificadas por jobs diarios.