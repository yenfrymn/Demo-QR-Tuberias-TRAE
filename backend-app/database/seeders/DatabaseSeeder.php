<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pipeline;
use App\Models\Company;
use App\Models\Certification;
use App\Models\OperatingLicense;
use App\Models\Blueprint;
use App\Services\QrService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'Admin123!',
            'role' => 'admin',
        ]);
        $editor = User::factory()->create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'password' => 'Editor123!',
            'role' => 'editor',
        ]);
        $viewer = User::factory()->create([
            'name' => 'Viewer',
            'email' => 'viewer@example.com',
            'password' => 'Viewer123!',
            'role' => 'viewer',
        ]);

        $pipe = Pipeline::create([
            'qr_code' => 'PIPE-2024-001',
            'name' => 'Pipeline Norte Sección A',
            'lat' => 4.7110,
            'lng' => -74.0721,
            'address' => 'Sector Industrial Norte',
            'diameter' => '24 pulgadas',
            'material' => 'ASTM A53 Grade B',
            'installation_date' => '2020-01-15',
            'status' => 'active',
            'description' => 'Tubería principal del sector norte',
        ]);

        $initiator = Company::create([
            'name' => 'PetroStart Inc.',
            'type' => 'initiator',
            'contact_info' => ['phone' => '+1 555 0000'],
        ]);
        $operator = Company::create([
            'name' => 'OilFlow Corporation',
            'type' => 'operator',
            'contact_info' => ['phone' => '+1 555 1111'],
        ]);

        $pipe->companies()->attach($initiator->id, [
            'role' => 'initiator',
            'start_date' => '2020-01-15',
            'is_current' => false,
        ]);
        $pipe->companies()->attach($operator->id, [
            'role' => 'current_operator',
            'start_date' => '2023-06-01',
            'is_current' => true,
        ]);

        $lic = OperatingLicense::create([
            'pipeline_id' => $pipe->id,
            'license_number' => 'LIC-2024-001',
            'issued_by' => 'ANH',
            'issue_date' => '2024-01-01',
            'expiry_date' => '2026-12-15',
            'status' => 'active',
            'document_path' => null,
        ]);

        Certification::create([
            'pipeline_id' => $pipe->id,
            'type' => 'ISO 9001',
            'certification_number' => 'ISO9001-2024-COL-001',
            'issued_date' => '2024-01-10',
            'expiry_date' => '2026-01-10',
            'issuing_body' => 'ISO',
            'document_path' => null,
            'status' => 'valid',
        ]);

        Blueprint::create([
            'pipeline_id' => $pipe->id,
            'title' => 'Diseño Estructural Principal',
            'file_path' => '',
            'file_type' => 'PDF',
            'version' => 'v2.1',
            'upload_date' => '2024-03-20',
            'uploaded_by' => $admin->id,
        ]);

        // Demos adicionales
        $qr = app(QrService::class);
        foreach ([
            ['code' => 'PIPE-2024-002', 'name' => 'Pipeline Centro Sección B'],
            ['code' => 'PIPE-2024-003', 'name' => 'Pipeline Sur Sección C'],
        ] as $demo) {
            $p = Pipeline::create([
                'qr_code' => $demo['code'],
                'name' => $demo['name'],
                'lat' => 4.6,
                'lng' => -74.1,
                'address' => 'Zona Industrial',
                'diameter' => '18 pulgadas',
                'material' => 'ASTM A106',
                'installation_date' => '2021-05-10',
                'status' => 'active',
            ]);
            $p->qr_checksum = $qr->checksum($p->qr_code);
            $p->qr_image_path = $qr->generateImage($p->qr_code);
            $p->save();

            $op = Company::create([
                'name' => 'FlowOps',
                'type' => 'operator',
            ]);
            $p->companies()->attach($op->id, [
                'role' => 'current_operator',
                'start_date' => '2022-02-01',
                'is_current' => true,
            ]);

            OperatingLicense::create([
                'pipeline_id' => $p->id,
                'license_number' => 'LIC-'.$p->id,
                'issued_by' => 'ANH',
                'issue_date' => now()->subYear()->toDateString(),
                'expiry_date' => now()->addDays(45)->toDateString(),
                'status' => 'active',
            ]);

            Certification::create([
                'pipeline_id' => $p->id,
                'type' => 'ISO 14001',
                'certification_number' => 'ISO14001-'.$p->id,
                'issued_date' => now()->subMonths(8)->toDateString(),
                'expiry_date' => now()->addMonths(16)->toDateString(),
                'status' => 'valid',
            ]);
        }
    }
}
