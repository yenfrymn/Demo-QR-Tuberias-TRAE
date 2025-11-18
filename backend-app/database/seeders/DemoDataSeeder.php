<?php

namespace Database\Seeders;

use App\Models\Pipeline;
use App\Models\Company;
use App\Models\Certification;
use App\Models\OperatingLicense;
use App\Models\Blueprint;
use App\Models\User;
use App\Services\QrService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing admin user
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            $this->command->info('Admin user not found. Please run the main seeder first.');
            return;
        }

        // Create demo companies
        $operator1 = Company::firstOrCreate(
            ['name' => 'Gas del Norte S.A.'],
            [
                'type' => 'operator',
                'tax_id' => '30-12345678-9',
                'contact_info' => json_encode([
                    'address' => 'Av. Principal 1234, Ciudad Industrial',
                    'contact_person' => 'Carlos Rodríguez',
                    'contact_email' => 'carlos.rodriguez@gasnorte.com',
                    'contact_phone' => '+54 11 4321-5678'
                ])
            ]
        );

        $operator2 = Company::firstOrCreate(
            ['name' => 'PetroSur Operaciones'],
            [
                'type' => 'operator',
                'tax_id' => '30-87654321-2',
                'contact_info' => json_encode([
                    'address' => 'Calle Técnica 567, Polo Petrolero',
                    'contact_person' => 'María González',
                    'contact_email' => 'maria.gonzalez@petrosur.com',
                    'contact_phone' => '+54 11 8765-4321'
                ])
            ]
        );

        $contractor = Company::firstOrCreate(
            ['name' => 'Tuberías y Construcciones SRL'],
            [
                'type' => 'initiator',
                'tax_id' => '30-98765432-1',
                'contact_info' => json_encode([
                    'address' => 'Bv. de los Industriales 890',
                    'contact_person' => 'Roberto Méndez',
                    'contact_email' => 'roberto.mendez@tyc.com',
                    'contact_phone' => '+54 11 2345-6789'
                ])
            ]
        );

        // Create demo pipelines with complete data
        $this->createDemoPipeline1($operator1, $contractor);
        $this->createDemoPipeline2($operator2, $contractor);
        $this->createDemoPipeline3($operator1, $operator2);
    }

    private function createDemoPipeline1($operator, $contractor): void
    {
        $qrService = app(QrService::class);
        
        $pipeline = Pipeline::firstOrCreate(
            ['qr_code' => 'GAS-NOR-A-001'],
            [
                'name' => 'Gasoducto Norte - Tramo A',
                'lat' => -34.134567,
                'lng' => -58.245678,
                'address' => 'Provincia de Buenos Aires - Zona Norte',
                'diameter' => '24 pulgadas',
                'material' => 'Acero API 5L X65',
                'installation_date' => '2020-03-15',
                'status' => 'active',
                'description' => 'Tramo principal del gasoducto norte, construido con materiales de alta resistencia',
                'qr_checksum' => $qrService->checksum('GAS-NOR-A-001')
            ]
        );

        // Generate QR code image
        $qrImagePath = $qrService->generateImage($pipeline->qr_code);
        $pipeline->update(['qr_image_path' => $qrImagePath]);

        // Create company relationships
        DB::table('pipeline_companies')->updateOrInsert(
            ['pipeline_id' => $pipeline->id, 'company_id' => $operator->id, 'role' => 'current_operator'],
            [
                'start_date' => '2020-04-01',
                'end_date' => null,
                'is_current' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        DB::table('pipeline_companies')->updateOrInsert(
            ['pipeline_id' => $pipeline->id, 'company_id' => $contractor->id, 'role' => 'initiator'],
            [
                'start_date' => '2019-01-15',
                'end_date' => '2020-03-31',
                'is_current' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Create certifications
        Certification::create([
            'pipeline_id' => $pipeline->id,
            'type' => 'material_quality',
            'certification_number' => 'CERT-2020-001',
            'issuing_body' => 'IRAM - Instituto Argentino de Normalización',
            'issued_date' => '2020-03-01',
            'expiry_date' => '2025-03-01',
            'document_path' => 'certifications/cert-2020-001.pdf',
            'status' => 'valid'
        ]);

        Certification::create([
            'pipeline_id' => $pipeline->id,
            'type' => 'welding',
            'certification_number' => 'SOLD-2020-045',
            'issuing_body' => 'ASME - American Society of Mechanical Engineers',
            'issued_date' => '2020-03-15',
            'expiry_date' => '2025-03-15',
            'document_path' => 'certifications/sold-2020-045.pdf',
            'status' => 'valid'
        ]);

        // Create operating license
        OperatingLicense::create([
            'pipeline_id' => $pipeline->id,
            'license_number' => 'OP-LIC-2020-123',
            'issued_by' => 'Ministerio de Energía - Argentina',
            'issue_date' => '2020-04-01',
            'expiry_date' => '2030-04-01',
            'status' => 'active',
            'document_path' => 'licenses/op-lic-2020-123.pdf'
        ]);

        // Create blueprints
        Blueprint::create([
            'pipeline_id' => $pipeline->id,
            'title' => 'Plan General de Trazado',
            'file_path' => 'blueprints/gas-nor-a-trazado.pdf',
            'file_type' => 'PDF',
            'version' => '2.1',
            'upload_date' => '2020-03-20',
            'uploaded_by' => 1
        ]);

        Blueprint::create([
            'pipeline_id' => $pipeline->id,
            'title' => 'Detalle de Válvulas y Accesorios',
            'file_path' => 'blueprints/gas-nor-a-valvulas.dwg',
            'file_type' => 'DWG',
            'version' => '1.5',
            'upload_date' => '2020-03-25',
            'uploaded_by' => 1
        ]);
    }

    private function createDemoPipeline2($operator, $contractor): void
    {
        $qrService = app(QrService::class);
        
        $pipeline = Pipeline::firstOrCreate(
            ['qr_code' => 'OLEO-PAT-CT-002'],
            [
                'name' => 'Oleoducto Patagónico - Tramo Central',
                'lat' => -38.999999,
                'lng' => -69.111111,
                'address' => 'Provincia de Neuquén - Cuenca Neuquina',
                'diameter' => '36 pulgadas',
                'material' => 'Acero API 5L X70',
                'installation_date' => '2018-08-20',
                'status' => 'active',
                'description' => 'Oleoducto principal de transporte de crudo desde la Patagonia',
                'qr_checksum' => $qrService->checksum('OLEO-PAT-CT-002')
        ]);

        // Generate QR code image
        $qrImagePath = $qrService->generateImage($pipeline->qr_code);
        $pipeline->update(['qr_image_path' => $qrImagePath]);

        // Create company relationships
        DB::table('pipeline_companies')->updateOrInsert(
            ['pipeline_id' => $pipeline->id, 'company_id' => $operator->id, 'role' => 'current_operator'],
            [
                'start_date' => '2018-09-01',
                'end_date' => null,
                'is_current' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        DB::table('pipeline_companies')->updateOrInsert(
            ['pipeline_id' => $pipeline->id, 'company_id' => $contractor->id, 'role' => 'initiator'],
            [
                'start_date' => '2017-05-10',
                'end_date' => '2018-08-31',
                'is_current' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Create certifications
        Certification::create([
            'pipeline_id' => $pipeline->id,
            'type' => 'corrosion_resistance',
            'certification_number' => 'CORR-2018-078',
            'issuing_body' => 'DNV GL - Det Norske Veritas',
            'issued_date' => '2018-08-01',
            'expiry_date' => '2023-08-01',
            'document_path' => 'certifications/corr-2018-078.pdf',
            'status' => 'expired'
        ]);

        // Create operating license
        OperatingLicense::create([
            'pipeline_id' => $pipeline->id,
            'license_number' => 'OP-LIC-2018-456',
            'issued_by' => 'Secretaría de Energía - Argentina',
            'issue_date' => '2018-09-01',
            'expiry_date' => '2028-09-01',
            'status' => 'active',
            'document_path' => 'licenses/op-lic-2018-456.pdf'
        ]);

        // Create blueprints
        Blueprint::create([
            'pipeline_id' => $pipeline->id,
            'title' => 'Perfil Longitudinal del Oleoducto',
            'file_path' => 'blueprints/oleo-pat-perfil.pdf',
            'file_type' => 'PDF',
            'version' => '3.2',
            'upload_date' => '2018-09-15',
            'uploaded_by' => 1
        ]);
    }

    private function createDemoPipeline3($operator1, $operator2): void
    {
        $qrService = app(QrService::class);
        
        $pipeline = Pipeline::firstOrCreate(
            ['qr_code' => 'GAS-ATL-RE-003'],
            [
                'name' => 'Gasoducto del Atlántico - Rama Este',
                'lat' => -37.055555,
                'lng' => -57.932098,
                'address' => 'Provincia de Buenos Aires - Costa Atlántica',
                'diameter' => '30 pulgadas',
                'material' => 'Acero API 5L X60',
                'installation_date' => '2022-01-10',
                'status' => 'active',
                'description' => 'Gasoducto de distribución para la zona costera, construido con tecnología moderna',
                'qr_checksum' => $qrService->checksum('GAS-ATL-RE-003')
        ]);

        // Generate QR code image
        $qrImagePath = $qrService->generateImage($pipeline->qr_code);
        $pipeline->update(['qr_image_path' => $qrImagePath]);

        // Create company relationships
        DB::table('pipeline_companies')->updateOrInsert(
            ['pipeline_id' => $pipeline->id, 'company_id' => $operator2->id, 'role' => 'current_operator'],
            [
                'start_date' => '2022-02-01',
                'end_date' => null,
                'is_current' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        DB::table('pipeline_companies')->updateOrInsert(
            ['pipeline_id' => $pipeline->id, 'company_id' => $operator1->id, 'role' => 'initiator'],
            [
                'start_date' => '2021-08-15',
                'end_date' => '2022-01-31',
                'is_current' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Create certifications
        Certification::create([
            'pipeline_id' => $pipeline->id,
            'type' => 'hydraulic_test',
            'certification_number' => 'HID-2022-023',
            'issuing_body' => 'Bureau Veritas',
            'issued_date' => '2022-01-05',
            'expiry_date' => '2027-01-05',
            'document_path' => 'certifications/hid-2022-023.pdf',
            'status' => 'valid'
        ]);

        // Create operating license
        OperatingLicense::create([
            'pipeline_id' => $pipeline->id,
            'license_number' => 'OP-LIC-2022-789',
            'issued_by' => 'Ente Nacional de Gas - ENARGAS',
            'issue_date' => '2022-02-01',
            'expiry_date' => '2032-02-01',
            'status' => 'active',
            'document_path' => 'licenses/op-lic-2022-789.pdf'
        ]);

        // Create blueprints
        Blueprint::create([
            'pipeline_id' => $pipeline->id,
            'title' => 'Plan de Zonificación',
            'file_path' => 'blueprints/gas-atl-zonificacion.pdf',
            'file_type' => 'PDF',
            'version' => '1.8',
            'upload_date' => '2022-02-15',
            'uploaded_by' => 1
        ]);
    }
}