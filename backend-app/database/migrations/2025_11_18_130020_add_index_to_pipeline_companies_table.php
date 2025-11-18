<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pipeline_companies', function (Blueprint $table) {
            $table->index(['pipeline_id', 'role', 'is_current']);
        });
    }

    public function down(): void
    {
        Schema::table('pipeline_companies', function (Blueprint $table) {
            $table->dropIndex(['pipeline_id', 'role', 'is_current']);
        });
    }
};