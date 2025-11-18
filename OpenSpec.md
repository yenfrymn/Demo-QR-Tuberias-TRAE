# QR Tuberia API - OpenAPI Specification

## Overview
QR Tuberia is a comprehensive pipeline management system that uses QR codes for tracking and managing industrial pipelines, their technical specifications, certifications, and company relationships.

## Base URL
- Development: `http://localhost:8000`
- Production: `https://api.qrtuberia.com`

## Authentication
This API uses Laravel Sanctum for token-based authentication.

### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

### Get Current User
```http
GET /api/user
Authorization: Bearer {token}
```

## Role-Based Access Control
The system implements three user roles with different permissions:

- **admin**: Full access to all resources and operations
- **editor**: Can create and edit resources, but cannot delete
- **viewer**: Read-only access to all resources

## API Endpoints

### Health Check
```http
GET /api/health
```

Response:
```json
{
  "status": "healthy",
  "timestamp": "2025-11-18T15:52:46.000000Z",
  "database": {
    "status": "healthy",
    "connections": 3
  },
  "version": "1.0.0"
}
```

### Pipelines

#### List All Pipelines
```http
GET /api/pipelines
```

Response:
```json
{
  "data": [
    {
      "id": 1,
      "qr_code": "GAS-NOR-A-001",
      "qr_checksum": "8eaa57a3fca0",
      "qr_image_path": "public/qr/GAS-NOR-A-001.svg",
      "name": "Gasoducto Norte - Tramo A",
      "lat": -34.134567,
      "lng": -58.245678,
      "address": "Provincia de Buenos Aires - Zona Norte",
      "diameter": "24 pulgadas",
      "material": "Acero API 5L X65",
      "installation_date": "2020-03-15",
      "status": "active",
      "description": "Tramo principal del gasoducto norte",
      "companies": [
        {
          "id": 1,
          "name": "Gas del Norte S.A.",
          "type": "operator",
          "role": "current_operator",
          "pivot": {
            "start_date": "2020-04-01",
            "end_date": null,
            "is_current": true
          }
        }
      ],
      "certifications": [
        {
          "id": 1,
          "type": "material_quality",
          "certification_number": "CERT-2020-001",
          "status": "valid"
        }
      ],
      "operating_licenses": [
        {
          "id": 1,
          "license_number": "OP-LIC-2020-123",
          "status": "active"
        }
      ],
      "blueprints": [
        {
          "id": 1,
          "title": "Plan General de Trazado",
          "file_type": "PDF"
        }
      ]
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/pipelines?page=1",
    "last": "http://localhost:8000/api/pipelines?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "http://localhost:8000/api/pipelines",
    "per_page": 15,
    "to": 1,
    "total": 1
  }
}
```

#### Get Single Pipeline
```http
GET /api/pipelines/{id}
```

#### Create Pipeline (Admin/Editor only)
```http
POST /api/pipelines
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Nuevo Gasoducto",
  "lat": -34.123456,
  "lng": -58.234567,
  "address": "Provincia de Buenos Aires",
  "diameter": "30 pulgadas",
  "material": "Acero API 5L X60",
  "installation_date": "2023-01-15",
  "status": "active",
  "description": "Nuevo gasoducto de distribución"
}
```

#### Update Pipeline (Admin/Editor only)
```http
PUT /api/pipelines/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Gasoducto Actualizado",
  "status": "maintenance"
}
```

#### Delete Pipeline (Admin only)
```http
DELETE /api/pipelines/{id}
Authorization: Bearer {token}
```

#### Generate QR Code
```http
POST /api/pipelines/{id}/generate-qr
Authorization: Bearer {token}
```

#### Download QR Code Image
```http
GET /api/pipelines/{id}/download-qr
Authorization: Bearer {token}
```

### Companies

#### List All Companies
```http
GET /api/companies
```

#### Get Single Company
```http
GET /api/companies/{id}
```

#### Create Company (Admin/Editor only)
```http
POST /api/companies
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Nueva Empresa S.A.",
  "type": "operator",
  "tax_id": "30-12345678-9",
  "contact_info": {
    "address": "Av. Principal 1234",
    "contact_person": "Juan Pérez",
    "contact_email": "juan@empresa.com",
    "contact_phone": "+54 11 1234-5678"
  }
}
```

#### Update Company (Admin/Editor only)
```http
PUT /api/companies/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Empresa Actualizada S.A.",
  "contact_info": {
    "contact_person": "María González"
  }
}
```

#### Delete Company (Admin only)
```http
DELETE /api/companies/{id}
Authorization: Bearer {token}
```

### Pipeline-Company Relationships

#### Attach Company to Pipeline (Admin/Editor only)
```http
POST /api/pipelines/{pipelineId}/companies
Authorization: Bearer {token}
Content-Type: application/json

{
  "company_id": 1,
  "role": "current_operator",
  "start_date": "2023-01-01",
  "is_current": true
}
```

#### Update Pipeline-Company Relationship (Admin/Editor only)
```http
PUT /api/pipelines/{pipelineId}/companies/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "role": "initiator",
  "end_date": "2023-12-31",
  "is_current": false
}
```

#### Detach Company from Pipeline (Admin/Editor only)
```http
DELETE /api/pipelines/{pipelineId}/companies/{id}
Authorization: Bearer {token}
```

### Certifications

#### List Certifications for Pipeline
```http
GET /api/pipelines/{pipelineId}/certifications
```

#### Create Certification (Admin/Editor only)
```http
POST /api/pipelines/{pipelineId}/certifications
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
  "type": "material_quality",
  "certification_number": "CERT-2023-001",
  "issuing_body": "IRAM",
  "issued_date": "2023-01-15",
  "expiry_date": "2028-01-15",
  "document": [file],
  "status": "valid"
}
```

#### Update Certification (Admin/Editor only)
```http
PUT /api/certifications/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "expired"
}
```

#### Delete Certification (Admin only)
```http
DELETE /api/certifications/{id}
Authorization: Bearer {token}
```

### Operating Licenses

#### List Licenses for Pipeline
```http
GET /api/pipelines/{pipelineId}/licenses
```

#### Create Operating License (Admin/Editor only)
```http
POST /api/pipelines/{pipelineId}/licenses
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
  "license_number": "OP-LIC-2023-001",
  "issued_by": "Ministerio de Energía",
  "issue_date": "2023-01-01",
  "expiry_date": "2033-01-01",
  "status": "active",
  "document": [file]
}
```

#### Update Operating License (Admin/Editor only)
```http
PUT /api/licenses/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "suspended"
}
```

#### Delete Operating License (Admin only)
```http
DELETE /api/licenses/{id}
Authorization: Bearer {token}
```

### Blueprints

#### List Blueprints for Pipeline
```http
GET /api/pipelines/{pipelineId}/blueprints
```

#### Create Blueprint (Admin/Editor only)
```http
POST /api/pipelines/{pipelineId}/blueprints
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
  "title": "Plan de Trazado",
  "file": [file],
  "file_type": "PDF",
  "version": "1.0",
  "upload_date": "2023-01-15"
}
```

#### Update Blueprint (Admin/Editor only)
```http
PUT /api/blueprints/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "version": "2.0"
}
```

#### Delete Blueprint (Admin only)
```http
DELETE /api/blueprints/{id}
Authorization: Bearer {token}
```

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
  "message": "Resource not found."
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."],
    "email": ["The email must be a valid email address."]
  }
}
```

## QR Code Specifications

### QR Code Format
- **Prefix**: Pipeline code (e.g., GAS-NOR-A-001)
- **Checksum**: 12-character SHA1 hash of the code
- **Image Format**: SVG vector graphics
- **Storage Path**: `public/qr/{code}.svg`

### QR Code Validation
```javascript
// Example validation logic
const isValidQr = (qrCode, checksum) => {
  const expectedChecksum = substr(sha1(qrCode), 0, 12);
  return checksum === expectedChecksum;
};
```

## Security Features

### Authentication
- Token-based authentication using Laravel Sanctum
- CSRF protection for web routes
- Rate limiting on authentication endpoints

### Authorization
- Role-based access control (RBAC) with three user levels
- Laravel Policies for resource-level permissions
- Gate definitions for action-based permissions

### Data Validation
- Form request validation for all endpoints
- File upload restrictions (type, size)
- SQL injection prevention through Eloquent ORM
- XSS protection through Laravel's built-in security features

### File Security
- Secure file storage in `storage/app/public`
- File type validation for uploads
- Path traversal prevention
- Document access control through authorization policies

## Demo Data

The system includes comprehensive demo data with 3 complete pipelines:

1. **Gasoducto Norte - Tramo A** (GAS-NOR-A-001)
   - Operator: Gas del Norte S.A.
   - Contractor: Tuberías y Construcciones SRL
   - Certifications: Material Quality, Welding
   - License: OP-LIC-2020-123

2. **Oleoducto Patagónico - Tramo Central** (OLEO-PAT-CT-002)
   - Operator: PetroSur Operaciones  
   - Contractor: Tuberías y Construcciones SRL
   - Certifications: Corrosion Resistance
   - License: OP-LIC-2018-456

3. **Gasoducto del Atlántico - Rama Este** (GAS-ATL-RE-003)
   - Operator: PetroSur Operaciones
   - Initiator: Gas del Norte S.A.
   - Certifications: Hydraulic Test
   - License: OP-LIC-2022-789

## Testing

### Unit Tests
```bash
cd backend-app
php artisan test
```

### Frontend Tests
```bash
cd frontend
npm run test:unit
npm run test:e2e
```

## Deployment

### Backend Deployment
```bash
cd backend-app
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --class=DemoDataSeeder
```

### Frontend Deployment
```bash
cd frontend
npm run build
```

## Support

For support and questions:
- Email: support@qrtuberia.com
- Documentation: https://docs.qrtuberia.com
- Issues: https://github.com/qrtuberia/issues