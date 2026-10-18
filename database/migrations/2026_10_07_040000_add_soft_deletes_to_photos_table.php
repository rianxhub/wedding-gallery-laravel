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
        if (Schema::hasTable('photos') && !Schema::hasColumn('photos', 'deleted_at')) {
            Schema::table('photos', function (Blueprint $table) {
                $table->softDeletes()->after('thumbnail_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('photos') && Schema::hasColumn('photos', 'deleted_at')) {
            Schema::table('photos', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
