<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pipeline_id')->constrained('pipelines')->cascadeOnDelete();
            $table->string('type');
            $table->string('certification_number');
            $table->date('issued_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('issuing_body')->nullable();
            $table->string('document_path')->nullable();
            $table->enum('status', ['valid', 'expired', 'pending'])->default('valid');
            $table->timestamps();
            $table->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};