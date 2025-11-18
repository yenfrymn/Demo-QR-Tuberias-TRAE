<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pipeline;
use App\Services\QrService;
use Illuminate\Support\Facades\Storage;

class QrController extends Controller
{
    public function generate(Pipeline $pipeline, QrService $qr)
    {
        $this->authorize('manage-pipelines');
        $code = $qr->ensureCode($pipeline);
        $pipeline->qr_checksum = $qr->checksum($code);
        $pipeline->qr_image_path = $qr->generateImage($code);
        $pipeline->save();
        return response()->json(['data' => [
            'code' => $code,
            'checksum' => $pipeline->qr_checksum,
            'download_url' => Storage::url($pipeline->qr_image_path),
        ]]);
    }

    public function download(Pipeline $pipeline)
    {
        if (! $pipeline->qr_image_path || ! Storage::exists($pipeline->qr_image_path)) {
            return response()->json(['message' => 'QR not found'], 404);
        }
        return Storage::download($pipeline->qr_image_path);
    }
}