<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Pipeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificationController extends Controller
{
    public function listByPipeline(Pipeline $pipeline)
    {
        $certs = $pipeline->certifications()->orderByDesc('expiry_date')->get();
        return response()->json(['data' => $certs, 'meta' => ['timestamp' => now()->toIso8601String(), 'version' => '1.0']]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pipeline_id' => ['required', 'integer', 'exists:pipelines,id'],
            'type' => ['required', 'string'],
            'certification_number' => ['required', 'string'],
            'issued_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'issuing_body' => ['nullable', 'string'],
            'document' => ['nullable', 'file', 'max:51200'],
        ]);

        $path = null;
        if ($request->file('document')) {
            $path = $request->file('document')->store('private/certifications');
        }

        $cert = Certification::create([
            'pipeline_id' => $data['pipeline_id'],
            'type' => $data['type'],
            'certification_number' => $data['certification_number'],
            'issued_date' => $data['issued_date'] ?? null,
            'expiry_date' => $data['expiry_date'] ?? null,
            'issuing_body' => $data['issuing_body'] ?? null,
            'document_path' => $path,
            'status' => 'valid',
        ]);

        return response()->json(['data' => $cert], 201);
    }

    public function download(Certification $certification)
    {
        if (! $certification->document_path || ! Storage::exists($certification->document_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        return Storage::download($certification->document_path);
    }
}