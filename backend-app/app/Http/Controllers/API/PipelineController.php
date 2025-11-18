<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PipelineRequest;
use App\Http\Resources\PipelineResource;
use App\Models\Pipeline;
use Illuminate\Http\Request;

class PipelineController extends Controller
{
    public function index(Request $request)
    {
        $query = Pipeline::query()->with(['certifications', 'blueprints', 'operatingLicense', 'companies']);
        if ($code = $request->get('qr_code')) {
            $query->qr($code);
        }
        $pipelines = $query->paginate($request->integer('per_page', 15));
        return response()->json([
            'data' => PipelineResource::collection($pipelines)->items(),
            'meta' => [
                'timestamp' => now()->toIso8601String(),
                'version' => '1.0',
                'links' => [
                    'next' => $pipelines->nextPageUrl(),
                    'prev' => $pipelines->previousPageUrl(),
                ],
            ],
        ]);
    }

    public function show(Pipeline $pipeline)
    {
        $pipeline->load(['certifications', 'blueprints', 'operatingLicense', 'companies']);
        return response()->json([
            'data' => new PipelineResource($pipeline),
            'meta' => [
                'timestamp' => now()->toIso8601String(),
                'version' => '1.0',
            ],
        ]);
    }

    public function store(PipelineRequest $request)
    {
        $pipeline = Pipeline::create($request->validated());
        return response()->json(['data' => new PipelineResource($pipeline)], 201);
    }

    public function update(PipelineRequest $request, Pipeline $pipeline)
    {
        $pipeline->update($request->validated());
        return response()->json(['data' => new PipelineResource($pipeline)]);
    }

    public function destroy(Pipeline $pipeline)
    {
        $pipeline->delete();
        return response()->noContent();
    }
}