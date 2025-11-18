<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 10px; }
        .section { margin-bottom: 12px; }
        .label { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #ddd; padding: 6px; }
        th { background: #f5f5f5; }
    </style>
    <title>Reporte de Tubería</title>
    </head>
<body>
    <h1>Reporte de Tubería: {{ $pipeline->name }}</h1>
    <div class="section">
        <div><span class="label">QR:</span> {{ $pipeline->qr_code }}</div>
        <div><span class="label">Ubicación:</span> {{ $pipeline->address }} ({{ $pipeline->lat }}, {{ $pipeline->lng }})</div>
        <div><span class="label">Diámetro:</span> {{ $pipeline->diameter }} | <span class="label">Material:</span> {{ $pipeline->material }}</div>
        <div><span class="label">Instalación:</span> {{ $pipeline->installation_date }}</div>
        <div><span class="label">Estado:</span> {{ $pipeline->status }}</div>
    </div>

    <div class="section">
        <h3>Empresas</h3>
        <table>
            <thead><tr><th>Rol</th><th>Nombre</th><th>Inicio</th></tr></thead>
            <tbody>
                @foreach($pipeline->companies as $company)
                    <tr>
                        <td>{{ $company->pivot->role }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->pivot->start_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Licencia de Operación</h3>
        @if($pipeline->operatingLicense)
            <div>N° {{ $pipeline->operatingLicense->license_number }} — Vigente hasta {{ $pipeline->operatingLicense->expiry_date }}</div>
        @else
            <div>Sin licencia</div>
        @endif
    </div>

    <div class="section">
        <h3>Certificaciones</h3>
        <table>
            <thead><tr><th>Tipo</th><th>Número</th><th>Emitida</th><th>Vence</th><th>Estado</th></tr></thead>
            <tbody>
                @foreach($pipeline->certifications as $c)
                    <tr>
                        <td>{{ $c->type }}</td>
                        <td>{{ $c->certification_number }}</td>
                        <td>{{ $c->issued_date }}</td>
                        <td>{{ $c->expiry_date }}</td>
                        <td>{{ $c->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Planos</h3>
        <table>
            <thead><tr><th>Título</th><th>Tipo</th><th>Versión</th><th>Fecha</th></tr></thead>
            <tbody>
                @foreach($pipeline->blueprints as $b)
                    <tr>
                        <td>{{ $b->title }}</td>
                        <td>{{ $b->file_type }}</td>
                        <td>{{ $b->version }}</td>
                        <td>{{ $b->upload_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="section">
        <small>Generado: {{ now()->toIso8601String() }}</small>
    </div>
</body>
</html>