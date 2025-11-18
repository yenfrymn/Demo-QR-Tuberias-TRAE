# Configuración de cPanel para QR Tuberia

## MultiPHP Manager Configuration

### PHP Version
- PHP 8.2 (recomendado)
- PHP 8.3 (compatible)

### PHP Extensions Required
```
pdo_mysql
openssl
mbstring
tokenizer
xml
ctype
json
bcmath
fileinfo
curl
zip
mbstring
gd
```

## MySQL Database Configuration

### Database Settings
- **Charset**: utf8mb4
- **Collation**: utf8mb4_unicode_ci
- **Engine**: InnoDB

### User Privileges
- ALL PRIVILEGES on application database
- FILE privilege for imports/exports (opcional)

## Cron Jobs Configuration

### Laravel Schedule (cada minuto)
```
* * * * * cd /home/username/public_html/backend-app && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
```

### Queue Worker (opcional, si se usa colas)
```
* * * * * cd /home/username/public_html/backend-app && /usr/local/bin/php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

### Backup Database (diario a las 2 AM)
```
0 2 * * * /usr/bin/mysqldump -u username_dbuser -p'password' username_dbname > /home/username/backups/db_backup_$(date +\%Y\%m\%d).sql
```

## File Permissions

### Laravel Storage
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Public Files
```bash
chmod -R 755 public/qr/
chmod -R 755 public/storage/
```

## .htaccess Configuration

### Backend (.htaccess en backend-app/)
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Gzip Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>
```

### Frontend (.htaccess en public_html/)
```apache
# SPA Routing
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteRule ^index\.html$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.html [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Gzip Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>

# Cache Control
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access 1 year"
    ExpiresByType image/jpeg "access 1 year"
    ExpiresByType image/gif "access 1 year"
    ExpiresByType image/png "access 1 year"
    ExpiresByType text/css "access 1 month"
    ExpiresByType application/pdf "access 1 month"
    ExpiresByType text/javascript "access 1 month"
    ExpiresByType application/javascript "access 1 month"
</IfModule>
```

## SSL/TLS Configuration

### Let's Encrypt SSL
1. Acceder a "Let's Encrypt SSL" en cPanel
2. Instalar certificado para el dominio principal
3. Incluir www subdomain
4. Habilitar HTTPS redirect automático

### Security Headers
```apache
# Additional security headers
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:;"
```

## Email Configuration (opcional)

### SMTP Settings for Laravel
```env
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=587
MAIL_USERNAME=username@domain.com
MAIL_PASSWORD=email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@domain.com
MAIL_FROM_NAME="QR Tuberia"
```

## Backup Configuration

### Full Account Backup
- Habilitar backups semanales en cPanel
- Configurar destino remoto (opcional)
- Incluir bases de datos y archivos de correo

### Database Only Backup
```bash
# Manual backup command
mysqldump -u username_dbuser -p'password' username_dbname | gzip > backup_$(date +%Y%m%d_%H%M%S).sql.gz
```

## Performance Optimization

### PHP Settings
```ini
memory_limit = 256M
max_execution_time = 300
max_input_vars = 3000
upload_max_filesize = 64M
post_max_size = 64M
```

### MySQL Optimization
```ini
innodb_buffer_pool_size = 256M
max_connections = 100
query_cache_size = 16M
query_cache_limit = 2M
```

## Monitoring

### Server Resources
- Habilitar estadísticas de cPanel
- Configurar alertas de uso de recursos
- Monitorear logs de errores

### Application Monitoring
```bash
# Ver últimos errores de Laravel
tail -f storage/logs/laravel.log

# Ver logs de Apache
tail -f /usr/local/apache/logs/error_log
```