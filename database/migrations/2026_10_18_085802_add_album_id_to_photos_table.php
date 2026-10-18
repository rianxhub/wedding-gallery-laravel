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
        Schema::table('photos', function (Blueprint $table) {
            if (!Schema::hasColumn('photos', 'album_id')) {
                $table->foreignId('album_id')
                    ->nullable()
                    ->after('wedding_id')
                    ->constrained('albums')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            if (Schema::hasColumn('photos', 'album_id')) {
                $table->dropForeign(['album_id']);
                $table->dropColumn('album_id');
            }
        });
    }
};
