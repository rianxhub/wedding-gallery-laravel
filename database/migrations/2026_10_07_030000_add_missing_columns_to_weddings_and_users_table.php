<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom role ke tabel users jika belum ada
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->after('password');
            });
        }

        // 2. Tambah kolom yang diperlukan ke tabel weddings jika belum ada
        if (Schema::hasTable('weddings')) {
            Schema::table('weddings', function (Blueprint $table) {
                if (!Schema::hasColumn('weddings', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
                }

                if (!Schema::hasColumn('weddings', 'couple_name')) {
                    $table->string('couple_name')->nullable()->after('user_id');
                }

                if (!Schema::hasColumn('weddings', 'wedding_date')) {
                    $table->date('wedding_date')->nullable()->after('slug');
                }

                if (!Schema::hasColumn('weddings', 'cover_drive_file_id')) {
                    $table->string('cover_drive_file_id')->nullable()->after('drive_folder_id');
                }

                if (!Schema::hasColumn('weddings', 'quote')) {
                    $table->text('quote')->nullable()->after('cover_drive_file_id');
                }

                if (!Schema::hasColumn('weddings', 'theme')) {
                    $table->string('theme', 100)->nullable()->after('quote');
                }

                if (!Schema::hasColumn('weddings', 'nama_pasangan')) {
                    $table->string('nama_pasangan')->nullable();
                }

                if (!Schema::hasColumn('weddings', 'tanggal')) {
                    $table->date('tanggal')->nullable();
                }

                if (!Schema::hasColumn('weddings', 'lokasi')) {
                    $table->string('lokasi')->nullable();
                }

                if (!Schema::hasColumn('weddings', 'pin')) {
                    $table->string('pin')->nullable();
                }
            });

            // Sinkronisasi data lama nama_pasangan -> couple_name jika ada
            if (Schema::hasColumn('weddings', 'nama_pasangan') && Schema::hasColumn('weddings', 'couple_name')) {
                DB::table('weddings')
                    ->whereNull('couple_name')
                    ->whereNotNull('nama_pasangan')
                    ->update(['couple_name' => DB::raw('nama_pasangan')]);
            }

            // Sinkronisasi data lama tanggal -> wedding_date jika ada
            if (Schema::hasColumn('weddings', 'tanggal') && Schema::hasColumn('weddings', 'wedding_date')) {
                DB::table('weddings')
                    ->whereNull('wedding_date')
                    ->whereNotNull('tanggal')
                    ->update(['wedding_date' => DB::raw('tanggal')]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }

        if (Schema::hasTable('weddings')) {
            Schema::table('weddings', function (Blueprint $table) {
                $columnsToDrop = [];

                if (Schema::hasColumn('weddings', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $columnsToDrop[] = 'user_id';
                }

                foreach (['couple_name', 'wedding_date', 'cover_drive_file_id', 'quote', 'theme'] as $col) {
                    if (Schema::hasColumn('weddings', $col)) {
                        $columnsToDrop[] = $col;
                    }
                }

                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};
