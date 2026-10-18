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
        Schema::create('weddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('couple_name')->nullable();
            $table->string('nama_pasangan')->nullable();
            $table->string('slug')->unique();
            $table->date('wedding_date')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('pin')->default('1234')->nullable();
            $table->string('drive_folder_id');
            $table->string('cover_drive_file_id')->nullable();
            $table->text('quote')->nullable();
            $table->string('theme', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weddings');
    }
};
