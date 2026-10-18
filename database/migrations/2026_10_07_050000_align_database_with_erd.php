<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menyelaraskan struktur tabel USERS, WEDDINGS, ALBUMS, dan DOWNLOAD_LOGS
     * secara persis dengan spesifikasi ERD.
     */
    public function up(): void
    {
        // 1. Perbaiki tabel USERS: Jadikan kolom 'role' tipe ENUM('admin', 'user')
        if (Schema::hasTable('users')) {
            // Pastikan data role yang ada valid sebelum diubah ke ENUM
            DB::table('users')->whereNull('role')->orWhereNotIn('role', ['admin', 'user'])->update(['role' => 'user']);

            // Ubah tipe kolom role ke ENUM (spesifik MySQL)
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
            }
        }

        // 2. Perbaiki tabel WEDDINGS
        if (Schema::hasTable('weddings')) {
            // A. Sinkronisasi data lama nama_pasangan -> couple_name jika couple_name masih null
            if (Schema::hasColumn('weddings', 'nama_pasangan') && Schema::hasColumn('weddings', 'couple_name')) {
                DB::table('weddings')
                    ->whereNull('couple_name')
                    ->whereNotNull('nama_pasangan')
                    ->update(['couple_name' => DB::raw('nama_pasangan')]);
            }

            // B. Sinkronisasi data lama tanggal -> wedding_date jika wedding_date masih null
            if (Schema::hasColumn('weddings', 'tanggal') && Schema::hasColumn('weddings', 'wedding_date')) {
                DB::table('weddings')
                    ->whereNull('wedding_date')
                    ->whereNotNull('tanggal')
                    ->update(['wedding_date' => DB::raw('tanggal')]);
            }

            // C. Pastikan user_id memiliki default user jika ada yang null
            $firstUserId = DB::table('users')->first()?->id;
            if ($firstUserId) {
                DB::table('weddings')->whereNull('user_id')->update(['user_id' => $firstUserId]);
            }

            // D. Drop kolom-kolom lama yang TIDAK ADA di ERD: nama_pasangan, tanggal, lokasi, pin
            Schema::table('weddings', function (Blueprint $table) {
                $columnsToDrop = [];
                foreach (['nama_pasangan', 'tanggal', 'lokasi', 'pin'] as $col) {
                    if (Schema::hasColumn('weddings', $col)) {
                        $columnsToDrop[] = $col;
                    }
                }
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });

            // E. Pastikan kolom-kolom sesuai spesifikasi ERD
            Schema::table('weddings', function (Blueprint $table) {
                if (!Schema::hasColumn('weddings', 'cover_drive_file_id')) {
                    $table->string('cover_drive_file_id')->nullable()->after('drive_folder_id');
                }
                if (!Schema::hasColumn('weddings', 'quote')) {
                    $table->text('quote')->nullable()->after('cover_drive_file_id');
                }
                if (!Schema::hasColumn('weddings', 'theme')) {
                    $table->string('theme', 100)->nullable()->after('quote');
                }
            });
        }

        // 3. Periksa tabel ALBUMS: pastikan kolom sesuai ERD
        if (!Schema::hasTable('albums')) {
            Schema::create('albums', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wedding_id')->constrained('weddings')->cascadeOnDelete();
                $table->string('album_name');
                $table->string('drive_subfolder_id')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        } else {
            Schema::table('albums', function (Blueprint $table) {
                if (!Schema::hasColumn('albums', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('drive_subfolder_id');
                }
            });
        }

        // 4. Periksa tabel DOWNLOAD_LOGS: pastikan kolom sesuai ERD
        if (!Schema::hasTable('download_logs')) {
            Schema::create('download_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wedding_id')->constrained('weddings')->cascadeOnDelete();
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
        if (Schema::hasTable('users')) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(255) NOT NULL DEFAULT 'user'");
        }

        if (Schema::hasTable('weddings')) {
            Schema::table('weddings', function (Blueprint $table) {
                $table->string('nama_pasangan')->nullable();
                $table->date('tanggal')->nullable();
                $table->string('lokasi')->nullable();
                $table->string('pin')->default('1234')->nullable();
            });
        }
    }
};
