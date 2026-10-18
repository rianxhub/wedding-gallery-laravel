<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add is_published column to weddings
        if (Schema::hasTable('weddings') && !Schema::hasColumn('weddings', 'is_published')) {
            Schema::table('weddings', function (Blueprint $table) {
                $table->boolean('is_published')->default(false)->after('pin_enabled');
            });
        }

        // Create client_visitors table — stores name & email of clients who accessed via PIN
        if (!Schema::hasTable('client_visitors')) {
            Schema::create('client_visitors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wedding_id')->constrained('weddings')->cascadeOnDelete();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->string('session_token', 64)->nullable()->index(); // for session persistence
                $table->timestamp('visited_at')->useCurrent();
                $table->timestamp('last_activity_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('client_visitors');

        if (Schema::hasTable('weddings') && Schema::hasColumn('weddings', 'is_published')) {
            Schema::table('weddings', function (Blueprint $table) {
                $table->dropColumn('is_published');
            });
        }
    }
};
