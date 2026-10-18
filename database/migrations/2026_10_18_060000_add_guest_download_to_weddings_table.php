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
        if (Schema::hasTable('weddings') && !Schema::hasColumn('weddings', 'guest_download')) {
            Schema::table('weddings', function (Blueprint $table) {
                $table->boolean('guest_download')->default(true)->after('pin_enabled');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('weddings') && Schema::hasColumn('weddings', 'guest_download')) {
            Schema::table('weddings', function (Blueprint $table) {
                $table->dropColumn('guest_download');
            });
        }
    }
};
