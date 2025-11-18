<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->string('qr_checksum')->nullable()->after('qr_code');
            $table->string('qr_image_path')->nullable()->after('qr_checksum');
            $table->index('qr_checksum');
        });
    }

    public function down(): void
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropIndex(['qr_checksum']);
            $table->dropColumn(['qr_checksum', 'qr_image_path']);
        });
    }
};