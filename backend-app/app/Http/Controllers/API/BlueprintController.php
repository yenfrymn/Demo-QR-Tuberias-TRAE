<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blueprint;
use App\Models\Pipeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlueprintController extends Controller
{
    public function listByPipeline(Pipeline $pipeline)
    {
        $items = $pipeline->blueprints()->orderByDesc('upload_date')->get();
        return response()->json(['data' => $items, 'meta' => ['timestamp' => now()->toIso8601String(), 'version' => '1.0']]);
    }

    public function upload(Request $request)
    {
        $data = $request->validate([
            'pipeline_id' => ['required', 'integer', 'exists:pipelines,id'],
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:51200'],
        ]);

        $result = [];
        foreach ($request->file('files', []) as $file) {
            $path = $file->store('private/blueprints');
            $type = strtoupper($file->getClientOriginalExtension());
            $bp = Blueprint::create([
                'pipeline_id' => $data['pipeline_id'],
                'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_path' => $path,
                'file_type' => in_array($type, ['PDF', 'DWG', 'DXF']) ? $type : 'PDF',
                'version' => null,
                'upload_date' => now()->toDateString(),
                'uploaded_by' => $request->user()?->id,
            ]);
            $result[] = $bp;
        }

        return response()->json(['data' => $result], 201);
    }

    public function download(Blueprint $blueprint)
    {
        if (! $blueprint->file_path || ! Storage::exists($blueprint->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        return Storage::download($blueprint->file_path);
    }
}