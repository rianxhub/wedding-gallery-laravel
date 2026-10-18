<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('download_logs')) {
            Schema::create('download_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wedding_id')
                    ->constrained('weddings')
                    ->cascadeOnDelete();
                $table->string('drive_file_id');
                $table->string('file_name');
                $table->string('ip_address', 45)->nullable();
                $table->timestamp('downloaded_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('download_logs');
    }
};
