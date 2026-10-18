<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // upload | edit | delete
            $table->string('subject_type')->nullable(); // Wedding | Photo
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('description')->nullable(); // Human-readable description
            $table->json('meta')->nullable(); // Extra context: couple_name, filename, etc.
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['action', 'created_at']);
            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
