<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OperatingLicense;
use App\Models\Pipeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LicenseController extends Controller
{
    public function getByPipeline(Pipeline $pipeline)
    {
        $license = $pipeline->operatingLicense;
        return response()->json(['data' => $license, 'meta' => ['timestamp' => now()->toIso8601String(), 'version' => '1.0']]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pipeline_id' => ['required', 'integer', 'exists:pipelines,id'],
            'license_number' => ['required', 'string'],
            'issued_by' => ['required', 'string'],
            'issue_date' => ['required', 'date'],
            'expiry_date' => ['required', 'date'],
            'status' => ['required', 'in:active,expired,suspended'],
            'document' => ['nullable', 'file', 'max:51200'],
        ]);

        $path = null;
        if ($request->file('document')) {
            $path = $request->file('document')->store('private/licenses');
        }

        $lic = OperatingLicense::create([
            'pipeline_id' => $data['pipeline_id'],
            'license_number' => $data['license_number'],
            'issued_by' => $data['issued_by'],
            'issue_date' => $data['issue_date'],
            'expiry_date' => $data['expiry_date'],
            'status' => $data['status'],
            'document_path' => $path,
        ]);

        return response()->json(['data' => $lic], 201);
    }

    public function expiring(Request $request)
    {
        $days = (int)($request->query('within_days', 60));
        $date = now()->addDays($days)->toDateString();
        $items = OperatingLicense::query()
            ->whereDate('expiry_date', '<=', $date)
            ->where('status', 'active')
            ->orderBy('expiry_date')
            ->get();
        return response()->json(['data' => $items, 'meta' => ['timestamp' => now()->toIso8601String(), 'version' => '1.0']]);
    }
}