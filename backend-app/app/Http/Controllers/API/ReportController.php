<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pipeline;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function generate(Request $request)
    {
        $data = $request->validate([
            'pipeline_id' => ['required', 'integer', 'exists:pipelines,id'],
        ]);

        $pipeline = Pipeline::with(['certifications', 'blueprints', 'operatingLicense', 'companies'])->findOrFail($data['pipeline_id']);

        $html = view('reports.pipeline', compact('pipeline'))->render();

        $dompdf = new Dompdf([ 'isHtml5ParserEnabled' => true ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $filename = 'private/reports/report-'.$pipeline->id.'-'.now()->format('YmdHis').'.pdf';
        Storage::put($filename, $output);

        return response()->json([
            'data' => [
                'id' => $pipeline->id,
                'title' => 'Reporte de Tubería',
                'document_url' => url('api/reports/download?file='.urlencode($filename)),
            ],
            'meta' => [ 'timestamp' => now()->toIso8601String(), 'version' => '1.0' ],
        ], 201);
    }

    public function download(Request $request)
    {
        $file = $request->query('file');
        if (! $file || ! Storage::exists($file)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        return Storage::download($file);
    }
}