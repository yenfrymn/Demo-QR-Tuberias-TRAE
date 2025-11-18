<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PipelineController;
use App\Http\Controllers\API\CertificationController;
use App\Http\Controllers\API\BlueprintController;
use App\Http\Controllers\API\LicenseController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\QrController;
use App\Http\Controllers\API\PipelineCompanyController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'app' => config('app.name'),
        'version' => app()->version(),
    ]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/user', function () { return auth()->user(); });

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pipelines', [PipelineController::class, 'index']);
    Route::get('/pipelines/{pipeline}', [PipelineController::class, 'show']);
    Route::post('/pipelines', [PipelineController::class, 'store']);
    Route::put('/pipelines/{pipeline}', [PipelineController::class, 'update']);
    Route::delete('/pipelines/{pipeline}', [PipelineController::class, 'destroy']);

    Route::get('/pipelines/{pipeline}/certifications', [CertificationController::class, 'listByPipeline']);
    Route::post('/certifications', [CertificationController::class, 'store']);
    Route::get('/certifications/{certification}/download', [CertificationController::class, 'download']);

    Route::get('/pipelines/{pipeline}/blueprints', [BlueprintController::class, 'listByPipeline']);
    Route::post('/blueprints/upload', [BlueprintController::class, 'upload']);
    Route::get('/blueprints/{blueprint}/download', [BlueprintController::class, 'download']);

    Route::get('/pipelines/{pipeline}/license', [LicenseController::class, 'getByPipeline']);
    Route::post('/licenses', [LicenseController::class, 'store']);
    Route::get('/licenses/expiring', [LicenseController::class, 'expiring']);

    Route::post('/reports/generate', [ReportController::class, 'generate']);
    Route::get('/reports/download', [ReportController::class, 'download']);
    Route::post('/pipelines/{pipeline}/generate-qr', [QrController::class, 'generate'])->middleware('can:manage-pipelines');
    Route::post('/pipeline-companies', [PipelineCompanyController::class, 'store'])->middleware('can:manage-pipeline-companies');
    Route::put('/pipeline-companies/{id}', [PipelineCompanyController::class, 'update'])->middleware('can:manage-pipeline-companies');
    Route::delete('/pipeline-companies/{id}', [PipelineCompanyController::class, 'destroy'])->middleware('can:manage-pipeline-companies');
});

Route::get('/pipelines/{pipeline}/qr', [QrController::class, 'download']);