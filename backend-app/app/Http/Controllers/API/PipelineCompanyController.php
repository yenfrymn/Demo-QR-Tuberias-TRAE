<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PipelineCompanyController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('manage-pipeline-companies');
        $data = $request->validate([
            'pipeline_id' => ['required', 'exists:pipelines,id'],
            'company_id' => ['required', 'exists:companies,id'],
            'role' => ['required', 'in:initiator,current_operator'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'is_current' => ['boolean'],
        ]);

        if (($data['role'] === 'current_operator') && ($data['is_current'] ?? false)) {
            DB::table('pipeline_companies')
                ->where('pipeline_id', $data['pipeline_id'])
                ->where('role', 'current_operator')
                ->update(['is_current' => false]);
        }

        DB::table('pipeline_companies')->insert([
            'pipeline_id' => $data['pipeline_id'],
            'company_id' => $data['company_id'],
            'role' => $data['role'],
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'is_current' => (bool)($data['is_current'] ?? false),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Linked'], 201);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('manage-pipeline-companies');
        
        $data = $request->validate([
            'pipeline_id' => ['sometimes', 'exists:pipelines,id'],
            'company_id' => ['sometimes', 'exists:companies,id'],
            'role' => ['sometimes', 'in:initiator,current_operator'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'is_current' => ['boolean'],
        ]);

        $existing = DB::table('pipeline_companies')->where('id', $id)->first();
        if (!$existing) {
            return response()->json(['message' => 'Not found'], 404);
        }

        if (isset($data['role']) && $data['role'] === 'current_operator' && ($data['is_current'] ?? false)) {
            DB::table('pipeline_companies')
                ->where('pipeline_id', $data['pipeline_id'] ?? $existing->pipeline_id)
                ->where('role', 'current_operator')
                ->where('id', '!=', $id)
                ->update(['is_current' => false]);
        }

        DB::table('pipeline_companies')
            ->where('id', $id)
            ->update(array_merge($data, ['updated_at' => now()]));

        return response()->json(['message' => 'Updated']);
    }

    public function destroy($id)
    {
        $this->authorize('manage-pipeline-companies');
        
        $deleted = DB::table('pipeline_companies')->where('id', $id)->delete();
        
        if (!$deleted) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Deleted']);
    }
}