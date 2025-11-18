# Task Breakdown - Sistema de Tuberías QR

## SPRINT 1: Backend Foundation (Semana 1-2)

### Task 1.1: Configuración del Proyecto
- [ ] Instalar Laravel 12 con PHP 8.3
- [ ] Configurar MySQL 8 y crear base de datos
- [ ] Configurar Redis para caché/colas
- [ ] Instalar dependencias: Sanctum, Spatie Media, DomPDF, Laravel Excel
- [ ] Configurar variables de entorno
- Entregables: proyecto base, .env, conexión BD, dependencias instaladas
- Criterios: comandos y endpoints mínimos funcionando (`/api/health`)
- Estimación: 4 horas — Responsable: Backend

### Task 1.2: Migraciones de Base de Datos
- [ ] Crear migración: pipelines
- [ ] Crear migración: companies
- [ ] Crear migración: pipeline_companies
- [ ] Crear migración: certifications
- [ ] Crear migración: operating_licenses
- [ ] Crear migración: blueprints
- [ ] Crear migración: users (extendido)
- [ ] Crear seeders con datos de prueba
- Entregables: migraciones y seeders
- Criterios: `php artisan migrate --seed` exitoso
- Estimación: 8 horas — Dependencia: 1.1 — Responsable: Backend

### Task 1.3: Modelos Eloquent y Relaciones
- [ ] Pipeline, Company, Certification, OperatingLicense, Blueprint
- [ ] Relaciones y scopes de búsqueda (por QR, vencimientos)
- [ ] Soft deletes (donde aplique)
- [ ] Observers (versionado de planos, unicidad QR)
- Entregables: modelos con pruebas unitarias básicas
- Criterios: relaciones funcionan con datos seed
- Estimación: 6 horas — Dependencia: 1.2 — Responsable: Backend

### Task 1.4: Sistema de Autenticación
- [ ] Configurar Laravel Sanctum
- [ ] AuthController: login, logout, refresh
- [ ] Middleware de autenticación y Policies/Gates
- [ ] Roles (admin, inspector, viewer)
- [ ] Pruebas unitarias de autenticación
- Entregables: endpoints auth operativos
- Criterios: protección de rutas y permisos verificados
- Estimación: 6 horas — Dependencia: 1.3 — Responsable: Backend

### Task 1.5: API - Pipelines CRUD
- [ ] PipelineController: index, show, store, update, destroy
- [ ] PipelineRequest: validaciones
- [ ] PipelineResource: transformación de respuestas
- [ ] Búsqueda por `qr_code`
- [ ] Filtros y paginación
- [ ] Pruebas unitarias/feature
- Entregables: endpoints CRUD operativos
- Criterios: OpenAPI alineado, tests verdes
- Estimación: 8 horas — Dependencia: 1.4 — Responsable: Backend

### Task 1.6: API - Certificaciones
- [ ] CertificationController
- [ ] Upload y descarga de documentos (autorización)
- [ ] Alertas de vencimiento (Job diario)
- Entregables: endpoints y job programado
- Criterios: notificaciones generadas a 90 días
- Estimación: 6 horas — Dependencia: 1.5 — Responsable: Backend

### Task 1.7: API - Planos y Blueprints
- [ ] BlueprintController
- [ ] Integración Spatie Media Library
- [ ] Upload múltiple y versionado automático
- [ ] Preview de PDFs
- Entregables: endpoints y almacenamiento S3/local según entorno
- Criterios: archivos almacenados y versionados correctamente
- Estimación: 6 horas — Dependencia: 1.5 — Responsable: Backend

### Task 1.8: API - Licencias de Operación
- [ ] LicenseController
- [ ] Validación de vigencia y estado
- [ ] Notificaciones (60 días antes)
- [ ] Historial de licencias
- Entregables: endpoints y lógica de negocio
- Criterios: reglas aplicadas y listados de próximas a vencer
- Estimación: 4 horas — Dependencia: 1.5 — Responsable: Backend

## SPRINT 2: Frontend Core (Semana 3)

### Task 2.1: Setup Vue.js 3
- [ ] Proyecto con Vite, Router, Pinia, Tailwind, Axios
- Entregables: scaffolding con layout base
- Criterios: build y navegación básica ok
- Estimación: 4 horas — Responsable: Frontend

### Task 2.2: Autenticación Frontend
- [ ] Login page, store de auth, guards de rutas
- [ ] Persistencia y auto-refresh de token
- Entregables: flujo de acceso completo
- Criterios: rutas protegidas y expiración manejada
- Estimación: 6 horas — Dependencia: 2.1, 1.4 — Responsable: Frontend

### Task 2.3: Layout Principal
- [ ] AppLayout mobile-first, navegación, header, footer, loaders
- Entregables: UI base
- Criterios: accesibilidad y responsividad verificadas
- Estimación: 6 horas — Dependencia: 2.2 — Responsable: Frontend

### Task 2.4: Scanner QR
- [ ] Integración `html5-qrcode`
- [ ] QRScanner component, permisos, feedback visual
- [ ] Redirección a detalle de tubería
- Entregables: escáner funcional
- Criterios: lectura QR estable en distintos dispositivos
- Estimación: 8 horas — Dependencia: 2.3 — Responsable: Frontend

### Task 2.5: Vista de Detalle de Tubería
- [ ] Información general, empresas, certificaciones con badges, planos con preview, licencia
- Entregables: pantalla completa de detalle
- Criterios: datos coherentes con API; estados claros
- Estimación: 10 horas — Dependencia: 2.4 — Responsable: Frontend

### Task 2.6: Descargas de Documentos
- [ ] Botón/servicio de descarga, manejo de errores, modal preview PDFs
- Entregables: UX de descarga
- Criterios: permisos respetados y feedback adecuado
- Estimación: 4 horas — Dependencia: 2.5 — Responsable: Frontend

## SPRINT 3: Features Avanzadas (Semana 4)

### Task 3.1: Generación de Reportes
- [ ] Servicio PDF (backend) + plantilla Blade
- [ ] Endpoint `/api/reports/generate` y descarga
- Entregables: reporte con datos clave
- Criterios: consistencia y legibilidad del PDF
- Estimación: 8 horas — Responsable: Backend/Frontend

### Task 3.2: Sistema de Notificaciones
- [ ] Vencimientos, bell icon, lista y lectura
- [ ] Email opcional
- Entregables: módulo notificaciones
- Criterios: conteo correcto y estado leído
- Estimación: 6 horas — Responsable: Fullstack

### Task 3.3: Panel de Administración
- [ ] Dashboard, CRUD de empresas/usuarios, roles, logs
- Entregables: panel admin
- Criterios: permisos estrictos y auditoría básica
- Estimación: 12 horas — Responsable: Fullstack

### Task 3.4: Búsqueda y Filtros
- [ ] Barra global, filtros por estado/ubicación/empresa, paginación
- Entregables: búsqueda eficiente
- Criterios: rendimiento y relevancia en resultados
- Estimación: 6 horas — Responsable: Frontend/Backend

### Task 3.5: Modo Offline (PWA)
- [ ] Service worker, caché de datos, sincronización, indicador offline
- Entregables: PWA operativa en campo
- Criterios: resiliencia sin conectividad
- Estimación: 8 horas — Responsable: Frontend

## SPRINT 4: Testing y Deploy (Semana 5)

### Task 4.1: Testing Backend
- [ ] Unit y feature tests (models, API, auth, permisos)
- [ ] Coverage > 80%
- Entregables: suite de pruebas
- Criterios: cobertura y estabilidad
- Estimación: 10 horas — Responsable: Backend

### Task 4.2: Testing Frontend
- [ ] Unit y E2E con Cypress; flujo completo
- Entregables: pruebas UI
- Criterios: rutas y escáner QR verificados
- Estimación: 8 horas — Responsable: Frontend

### Task 4.3: Optimización
- [ ] Evitar N+1; eager loading; compresión imágenes; minificación assets; CDN
- Entregables: mejoras de performance
- Criterios: métricas aceptables (Lighthouse/PWA)
- Estimación: 6 horas — Responsable: Fullstack

### Task 4.4: Documentación
- [ ] API (Swagger/OpenAPI), manual de usuario, guía de despliegue
- Entregables: documentación completa
- Criterios: claridad y actualización continua
- Estimación: 6 horas — Responsable: Todos

### Task 4.5: Configuración VPS/cPanel
- [ ] Servidor, dominio, SSL, deploy Laravel/Vue, backups, monitoreo
- Entregables: entorno productivo
- Criterios: alta disponibilidad y seguridad
- Estimación: 8 horas — Responsable: DevOps

### Task 4.6: UAT y Launch
- [ ] Pruebas con usuarios, correcciones, training, go-live
- Entregables: lanzamiento exitoso
- Criterios: aceptación del negocio
- Estimación: 6 horas — Responsable: PM/Equipo