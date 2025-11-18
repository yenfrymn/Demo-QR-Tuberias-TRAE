# QR Tuberia - Pipeline Management System

A comprehensive industrial pipeline management system that leverages QR codes for tracking, managing, and accessing technical information about pipelines, their certifications, operating licenses, and associated companies.

## 🚀 Features

### Core Functionality
- **QR Code Generation**: Automatic QR code generation with checksum validation for pipeline identification
- **Pipeline Management**: Complete CRUD operations for pipeline data including technical specifications
- **Company Management**: Track operators, contractors, and initiators with detailed contact information
- **Document Management**: Upload and manage certifications, operating licenses, and technical blueprints
- **Role-Based Access Control**: Three-tier permission system (Admin, Editor, Viewer)
- **Health Monitoring**: Real-time system health checks for frontend and backend

### Security Features
- **Authentication**: Laravel Sanctum token-based authentication
- **Authorization**: Laravel Policies and Gates for fine-grained permission control
- **Data Validation**: Comprehensive form request validation
- **File Security**: Secure document upload with type and size restrictions
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Protection**: Laravel's built-in security features

### Demo Data
The system includes 3 complete demo pipelines with realistic data:

1. **Gasoducto Norte - Tramo A** (GAS-NOR-A-001)
   - 24" diameter, 150.5km length
   - API 5L X65 steel material
   - Complete certifications and licenses

2. **Oleoducto Patagónico - Tramo Central** (OLEO-PAT-CT-002)
   - 36" diameter, 285.7km length
   - API 5L X70 steel material
   - Environmental monitoring systems

3. **Gasoducto del Atlántico - Rama Este** (GAS-ATL-RE-003)
   - 30" diameter, 98.3km length
   - API 5L X60 steel material
   - Modern telemetry systems

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 12.x
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **File Storage**: Laravel Storage with public disk
- **QR Generation**: Custom SVG-based QR service
- **Testing**: PHPUnit for unit tests

### Frontend
- **Framework**: Vue 3 with Composition API + Alpine.js
- **Build Tool**: Vite
- **Styling**: Tailwind CSS
- **State Management**: Pinia
- **Routing**: Vue Router 4
- **HTTP Client**: Axios
- **Testing**: Vitest (Unit), Cypress (E2E)

### Development Environment
- **Local Server**: XAMPP with PHP 8.2
- **Package Manager**: npm/pnpm
- **Version Control**: Git with GitHub integration

### Production Infrastructure
- **Server**: VPS (Virtual Private Server)
- **Control Panel**: cPanel/WHM for cost-effective server management
- **Web Server**: Apache/Nginx
- **SSL**: Let's Encrypt SSL certificates
- **Backup**: Automated daily backups
- **Monitoring**: Server health and uptime monitoring

## 📋 API Documentation

Complete API documentation available in [OpenSpec.md](OpenSpec.md) including:
- Authentication endpoints
- CRUD operations for all resources
- File upload endpoints
- QR code generation and validation
- Error response formats
- Security specifications

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Node.js 18+
- XAMPP (for Windows development)

### Backend Setup
```bash
cd backend-app
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### Frontend Setup
```bash
cd frontend
npm install
cp .env.example .env
npm run dev
```

### Run Tests
```bash
# Backend tests
cd backend-app
php artisan test

# Frontend tests
cd frontend
npm run test:unit
npm run test:e2e
```

## 🔐 Role-Based Access Control

### Admin Role
- Full access to all resources
- Can create, read, update, and delete any resource
- User management capabilities
- System configuration access

### Editor Role
- Can create and edit pipelines, companies, certifications, licenses, and blueprints
- Cannot delete resources
- Can upload and manage documents
- Read access to all system data

### Viewer Role (Guest)
- Read-only access to all pipelines and their information
- Can view QR codes and technical specifications
- Cannot access admin interfaces
- No authentication required for basic pipeline viewing

## 📊 Database Schema

### Core Tables
- **users**: User accounts with role-based permissions
- **pipelines**: Pipeline technical specifications with QR codes
- **companies**: Company information with contact details
- **pipeline_companies**: Many-to-many relationships with roles
- **certifications**: Pipeline certifications with document paths
- **operating_licenses**: Operating permits and licenses
- **blueprints**: Technical drawings and specifications

### Key Features
- JSON fields for flexible data storage
- Proper foreign key constraints
- Indexed columns for performance
- Timestamp tracking for all records
- Soft delete capability ready

## 🔍 QR Code System

### QR Code Format
- **Code Structure**: `{PIPELINE_CODE}`
- **Checksum**: 12-character SHA1 hash
- **Image Format**: Scalable Vector Graphics (SVG)
- **Storage**: `public/qr/{code}.svg`

### Validation Process
1. QR code scanned and code extracted
2. Checksum validated against code
3. Database query for pipeline information
4. Complete technical data displayed

## 🧪 Testing

### Unit Tests
- Policy authorization tests
- QR service functionality
- Form request validation
- API endpoint responses

### E2E Tests
- Complete user workflows
- QR code generation and scanning
- File upload processes
- Role-based access verification

### Test Data
- 3 complete demo pipelines
- Realistic company relationships
- Valid certifications and licenses
- QR codes with proper checksums

## 🚀 Deployment

### VPS cPanel/WHM Deployment Guide

#### Prerequisites
- VPS con cPanel/WHM instalado
- Acceso root al servidor
- Dominio apuntando al servidor

#### Configuración del Servidor
1. **Configurar cuenta en cPanel**
   - Crear cuenta de hosting para el dominio
   - Configurar PHP 8.2+ en MultiPHP Manager
   - Habilitar extensiones necesarias: `pdo_mysql`, `openssl`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `curl`

2. **Base de datos MySQL 8.0**
   - Crear base de datos y usuario en MySQL® Databases
   - Asignar todos los privilegios al usuario
   - Configurar `utf8mb4` como charset por defecto

3. **Despliegue de archivos**
   - Subir archivos del backend a `public_html/backend-app/`
   - Subir archivos del frontend compilados a `public_html/`
   - Configurar `.env` con credenciales de producción

4. **Configuración de Laravel**
   ```bash
   # En el directorio del backend
   composer install --no-dev --optimize-autoloader
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan migrate --force
   php artisan storage:link
   ```

5. **Configuración de seguridad**
   - Instalar certificado SSL en cPanel
   - Configurar `.htaccess` para redirección HTTPS
   - Establecer permisos correctos: `755` carpetas, `644` archivos
   - Configurar `APP_ENV=production` y `APP_DEBUG=false`

#### Optimización de Rendimiento
- **Compilar assets de producción**: `npm run build`
- **Habilitar compresión Gzip** en cPanel
- **Configurar caché de navegador** en `.htaccess`
- **Optimizar base de datos** con índices adicionales
- **Habilitar OPcache** para PHP

### Production Checklist
- [ ] Environment variables configured
- [ ] Database migrated and seeded
- [ ] File storage permissions set
- [ ] SSL certificates installed
- [ ] Rate limiting configured
- [ ] Error monitoring enabled
- [ ] Backup strategy implemented
- [ ] cPanel cron jobs configured
- [ ] File upload limits configured
- [ ] PHP memory limits optimized
- [ ] Security headers configured
- [ ] Maintenance mode configured

### Performance Optimization
- Database indexing optimized
- Query performance tuned
- Caching strategy implemented
- CDN for static assets
- Image optimization for QR codes

## 📞 Support

For technical support and questions:
- **Documentation**: See [OpenSpec.md](OpenSpec.md) for complete API reference
- **Issues**: Report bugs through GitHub issues
- **Email**: support@qrtuberia.com

## 📄 License

This project is proprietary software. All rights reserved.

## 🏗️ Architecture

### System Design
- **Monolithic Architecture**: Laravel backend with Vue.js SPA frontend
- **RESTful API**: Standard HTTP methods and status codes
- **File Storage**: Local disk storage with public access
- **Authentication**: Token-based with Sanctum
- **Authorization**: Policy-based access control

### Security Architecture
- **Input Validation**: Server-side validation for all inputs
- **File Upload Security**: Type and size validation
- **SQL Injection Prevention**: ORM-based queries
- **XSS Protection**: Automatic escaping and sanitization
- **CSRF Protection**: Built-in Laravel protection
- **Rate Limiting**: API endpoint protection

---

**QR Tuberia** - Industrial Pipeline Management Made Simple