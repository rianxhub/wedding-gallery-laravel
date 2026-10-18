<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('weddings')) {
            Schema::table('weddings', function (Blueprint $table) {
                if (!Schema::hasColumn('weddings', 'pin')) {
                    $table->string('pin', 20)->nullable()->default('1234');
                }
                if (!Schema::hasColumn('weddings', 'pin_enabled')) {
                    $table->boolean('pin_enabled')->default(true);
                }
                if (!Schema::hasColumn('weddings', 'cover_image')) {
                    $table->string('cover_image', 500)->nullable();
                }
                if (!Schema::hasColumn('weddings', 'photo_sort_mode')) {
                    $table->string('photo_sort_mode', 50)->default('custom');
                }
            });
        }

        if (Schema::hasTable('photos')) {
            Schema::table('photos', function (Blueprint $table) {
                if (!Schema::hasColumn('photos', 'sort_order')) {
                    $table->integer('sort_order')->default(0);
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('weddings')) {
            Schema::table('weddings', function (Blueprint $table) {
                $cols = [];
                foreach (['pin', 'pin_enabled', 'cover_image', 'photo_sort_mode'] as $c) {
                    if (Schema::hasColumn('weddings', $c)) $cols[] = $c;
                }
                if (!empty($cols)) $table->dropColumn($cols);
            });
        }

        if (Schema::hasTable('photos')) {
            Schema::table('photos', function (Blueprint $table) {
                if (Schema::hasColumn('photos', 'sort_order')) {
                    $table->dropColumn(['sort_order']);
                }
            });
        }
    }
};
