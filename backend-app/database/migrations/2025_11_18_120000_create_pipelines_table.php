<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pipelines', function (Blueprint $table) {
            $table->id();
            $table->string('qr_code')->unique();
            $table->string('name');
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lng', 10, 6)->nullable();
            $table->string('address')->nullable();
            $table->string('diameter')->nullable();
            $table->string('material')->nullable();
            $table->date('installation_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::table('pipelines', function (Blueprint $table) {
            $table->index('qr_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipelines');
    }
};