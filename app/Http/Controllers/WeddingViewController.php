<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\ActivityLog;
use App\Models\DownloadLog;
use App\Models\Photo;
use App\Models\User;
use App\Models\Wedding;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class WeddingViewController extends Controller
{
    /**
     * Menampilkan halaman dashboard / daftar wedding.
     */
    public function index()
    {
        if (!Schema::hasTable('weddings')) {
            return response()->view('weddings.index', [
                'weddings' => collect(),
                'totalWeddings' => 0,
                'totalAlbums' => 0,
                'totalPhotos' => 0,
                'totalDownloads' => 0,
                'totalUsers' => 0,
                'newWeddingsThisMonth' => 0,
                'publishedCount' => 0,
                'inReviewCount' => 0,
                'draftCount' => 0,
                'avgPhotosPerWedding' => 0,
                'pinProtectedCount' => 0,
                'pinProtectedPercent' => 0,
                'usedGb' => 0,
                'quotaGb' => 50,
                'storagePercent' => 0,
                'freeGb' => 50,
                'availableYears' => collect(),
            ]);
        }

        $relations = ['user'];
        if (Schema::hasTable('albums')) {
            $relations[] = 'albums';
        }
        if (Schema::hasTable('photos')) {
            $relations[] = 'photos';
        }
        if (Schema::hasTable('download_logs')) {
            $relations[] = 'downloadLogs';
        }

        $weddings = Wedding::with($relations)->latest()->get();
        $totalWeddings = $weddings->count();
        $totalAlbums = Schema::hasTable('albums') ? Album::count() : 0;
        $totalPhotos = Schema::hasTable('photos') ? Photo::whereNull('deleted_at')->count() : 0;
        $totalDownloads = Schema::hasTable('download_logs') ? DownloadLog::count() : 0;
        $totalUsers = Schema::hasTable('users') ? User::count() : 0;

        // Metrik 1: Wedding baru bulan ini
        $newWeddingsThisMonth = Wedding::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // Metrik 2 & Filter Status: Hitung Published, In Review, dan Draft secara real
        $publishedCount = 0;
        $inReviewCount = 0;
        $draftCount = 0;

        foreach ($weddings as $w) {
            $pCount = $w->photos ? $w->photos->count() : 0;
            if ($w->is_published) {
                $w->computed_status = 'published';
                $publishedCount++;
            } elseif ($pCount > 0) {
                $w->computed_status = 'review';
                $inReviewCount++;
            } else {
                $w->computed_status = 'draft';
                $draftCount++;
            }
        }

        // Metrik 3: PIN Protection real data
        $pinProtectedCount = $weddings->where('pin_enabled', true)->count();
        $pinProtectedPercent = $totalWeddings > 0 ? round(($pinProtectedCount / $totalWeddings) * 100) : 0;

        // Metrik 4: Rata-rata foto per wedding
        $avgPhotosPerWedding = $totalWeddings > 0 ? round($totalPhotos / $totalWeddings) : 0;

        // Metrik 5: Penyimpanan riil (dihitung dari kolom file_size tabel photos)
        $totalStorageBytes = Schema::hasTable('photos')
            ? (int) Photo::whereNull('deleted_at')->sum('file_size')
            : 0;

        $quotaGb = 50; // Kapasitas cloud storage standar
        $storageQuotaBytes = $quotaGb * 1024 * 1024 * 1024;
        $usedGb = round($totalStorageBytes / (1024 * 1024 * 1024), 1);
        $storagePercent = $storageQuotaBytes > 0 ? min(100, round(($totalStorageBytes / $storageQuotaBytes) * 100, 1)) : 0;
        $freeGb = max(0, round($quotaGb - $usedGb, 1));

        // Tahun yang tersedia dari data wedding riil untuk filter tanggal
        $availableYears = $weddings->pluck('wedding_date')
            ->filter()
            ->map(function ($date) {
                return $date instanceof \Carbon\Carbon ? $date->format('Y') : \Carbon\Carbon::parse($date)->format('Y');
            })
            ->unique()
            ->sortDesc()
            ->values();

        return view('weddings.index', compact(
            'weddings',
            'totalWeddings',
            'totalAlbums',
            'totalPhotos',
            'totalDownloads',
            'totalUsers',
            'newWeddingsThisMonth',
            'publishedCount',
            'inReviewCount',
            'draftCount',
            'avgPhotosPerWedding',
            'pinProtectedCount',
            'pinProtectedPercent',
            'usedGb',
            'quotaGb',
            'storagePercent',
            'freeGb',
            'availableYears'
        ));
    }

    /**
     * Menampilkan formulir pendaftaran wedding baru.
     */
    public function create()
    {
        $users = User::all();
        $defaultUser = User::first();

        $themes = [
            'Elegance Emerald' => 'Elegance Emerald (Deep botanical slate)',
            'Warm Minimalist' => 'Warm Minimalist (Earth, travertine stone)',
            'Modern Classic' => 'Modern Classic (Editorial monochrome)',
            'Sunset Gold' => 'Sunset Gold (Warm amber hour)',
        ];

        return view('weddings.create', compact('users', 'defaultUser', 'themes'));
    }

    /**
     * Menyimpan data wedding baru dan langsung menyinkronkan foto jika Google Drive diisi.
     */
    public function store(Request $request, GoogleDriveService $googleDrive)
    {
        $validated = $request->validate([
            'couple_name'         => 'required|string|max:255',
            'slug'                => 'nullable|string|max:255|unique:weddings,slug',
            'wedding_date'        => 'nullable|date',
            'theme'               => 'nullable|string|max:100',
            'quote'               => 'nullable|string|max:1000',
            'cover_drive_file_id' => 'nullable|string|max:255',
            'drive_folder'        => 'nullable|string|max:500',
            'pin'                 => 'nullable|string|max:20',
            'pin_enabled'         => 'nullable',
            'guest_download'      => 'nullable',
            'user_id'             => 'nullable|exists:users,id',
        ]);

        // Auto-generate slug jika kosong
        $slug = !empty($validated['slug']) 
            ? Str::slug($validated['slug']) 
            : Str::slug($validated['couple_name']);

        // Pastikan slug unik jika hasil generate bentrok
        $originalSlug = $slug;
        $counter = 1;
        while (Wedding::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        // Tentukan user_id (gunakan user yang dipilih atau admin saat ini)
        $userId = $validated['user_id'] ?? auth()->id() ?? User::first()?->id;

        // Bersihkan folder ID Google Drive jika ada
        $driveFolderId = !empty($validated['drive_folder']) 
            ? GoogleDriveService::extractFolderId($validated['drive_folder']) 
            : 'default_folder';

        // Bersihkan cover drive file ID jika berupa link
        $coverDriveFileId = !empty($validated['cover_drive_file_id'])
            ? GoogleDriveService::extractFolderId($validated['cover_drive_file_id'])
            : null;

        $wedding = Wedding::create([
            'user_id'             => $userId,
            'couple_name'         => $validated['couple_name'],
            'slug'                => $slug,
            'wedding_date'        => $validated['wedding_date'] ?? null,
            'drive_folder_id'     => $driveFolderId,
            'cover_drive_file_id' => $coverDriveFileId,
            'quote'               => $validated['quote'] ?? 'Two souls, one heart. Bersama melangkah dalam cinta selamanya.',
            'theme'               => $validated['theme'] ?? 'Elegance Emerald',
            'pin'                 => $request->input('pin', '1234'),
            'pin_enabled'         => $request->boolean('pin_enabled', true),
            'guest_download'      => $request->boolean('guest_download', true),
            'is_published'        => true,
        ]);

        // Jika folder drive diisi dan bukan default, otomatis sinkronkan foto!
        if (!empty($validated['drive_folder']) && $driveFolderId !== 'default_folder') {
            $syncResult = $googleDrive->syncWeddingPhotos($wedding, $driveFolderId);
            $photoCount = $syncResult['count'] ?? 0;
            ActivityLog::log('upload', "Sinkronisasi {$photoCount} foto dari Google Drive untuk galeri {$wedding->couple_name}", 'Wedding', $wedding->id, ['couple_name' => $wedding->couple_name, 'count' => $photoCount]);
            $message = $syncResult['message'] ?? "Pernikahan {$wedding->couple_name} berhasil dibuat!";
            return redirect()->route('weddings.show', $wedding->slug)
                ->with($syncResult['success'] ? 'success' : 'info', $message);
        }

        ActivityLog::log('upload', "Galeri wedding baru dibuat: {$wedding->couple_name}", 'Wedding', $wedding->id, ['couple_name' => $wedding->couple_name, 'slug' => $wedding->slug]);

        return redirect()->route('weddings.show', $wedding->slug)
            ->with('success', "Pernikahan {$wedding->couple_name} berhasil dibuat! Silakan hubungkan Google Drive Anda di bawah ini.");
    }

    /**
     * Menampilkan formulir edit proyek pernikahan.
     */
    public function edit(string $slug)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $users = User::all();

        $themes = [
            'Elegance Emerald' => 'Elegance Emerald (Deep botanical slate)',
            'Warm Minimalist' => 'Warm Minimalist (Earth, travertine stone)',
            'Modern Classic' => 'Modern Classic (Editorial monochrome)',
            'Sunset Gold' => 'Sunset Gold (Warm amber hour)',
        ];

        return view('weddings.edit', compact('wedding', 'users', 'themes'));
    }

    /**
     * Memperbarui data proyek pernikahan.
     */
    public function update(Request $request, string $slug, GoogleDriveService $googleDrive)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'couple_name'         => 'required|string|max:255',
            'slug'                => 'nullable|string|max:255|unique:weddings,slug,' . $wedding->id,
            'wedding_date'        => 'nullable|date',
            'theme'               => 'nullable|string|max:100',
            'quote'               => 'nullable|string|max:1000',
            'cover_drive_file_id' => 'nullable|string|max:255',
            'drive_folder'        => 'nullable|string|max:500',
            'pin'                 => 'nullable|string|max:20',
            'pin_enabled'         => 'nullable',
            'guest_download'      => 'nullable',
            'user_id'             => 'nullable|exists:users,id',
        ]);

        $coverDriveFileId = !empty($validated['cover_drive_file_id'])
            ? GoogleDriveService::extractFolderId($validated['cover_drive_file_id'])
            : $wedding->cover_drive_file_id;

        $driveFolderId = !empty($validated['drive_folder'])
            ? GoogleDriveService::extractFolderId($validated['drive_folder'])
            : $wedding->drive_folder_id;

        $newSlug = !empty($validated['slug']) ? Str::slug($validated['slug']) : $wedding->slug;

        $wedding->update([
            'couple_name'         => $validated['couple_name'],
            'slug'                => $newSlug,
            'wedding_date'        => $validated['wedding_date'] ?? $wedding->wedding_date,
            'theme'               => $validated['theme'] ?? $wedding->theme,
            'quote'               => $validated['quote'] ?? $wedding->quote,
            'cover_drive_file_id' => $coverDriveFileId,
            'drive_folder_id'     => $driveFolderId,
            'pin'                 => $request->input('pin', $wedding->pin ?? '1234'),
            'pin_enabled'         => $request->boolean('pin_enabled', true),
            'guest_download'      => $request->boolean('guest_download', true),
            'user_id'             => $validated['user_id'] ?? $wedding->user_id,
        ]);

        ActivityLog::log('edit', "Informasi galeri {$wedding->couple_name} berhasil diperbarui", 'Wedding', $wedding->id, ['couple_name' => $wedding->couple_name, 'theme' => $validated['theme'] ?? $wedding->theme]);

        return redirect()->route('weddings.show', $wedding->slug)
            ->with('success', "Proyek pernikahan {$wedding->couple_name} berhasil diperbarui!");
    }

    /**
     * Menampilkan detail satu wedding berdasarkan slug.
     */
    public function show(string $slug, GoogleDriveService $googleDrive)
    {
        $relations = ['user'];
        if (Schema::hasTable('albums')) {
            $relations['albums'] = function ($query) {
                $query->orderBy('sort_order', 'asc')->with(['photos' => function ($pq) {
                    $pq->whereNull('deleted_at');
                }]);
            };
        }
        if (Schema::hasTable('download_logs')) {
            $relations['downloadLogs'] = function ($query) {
                $query->latest('downloaded_at')->limit(15);
            };
        }
        if (Schema::hasTable('photos')) {
            $relations[] = 'photos';
            $relations[] = 'trashedPhotos';
        }

        $wedding = Wedding::with($relations)->where('slug', $slug)->firstOrFail();

        $serviceAccountEmail = $googleDrive->getServiceAccountEmail();
        $trashedPhotosCount = Schema::hasTable('photos') && $wedding->relationLoaded('trashedPhotos') 
            ? $wedding->trashedPhotos->count() 
            : 0;

        return view('weddings.show', compact('wedding', 'serviceAccountEmail', 'trashedPhotosCount'));
    }

    /**
     * Menyinkronkan foto langsung dari Google Drive ke database untuk wedding ini.
     */
    public function syncDrive(string $slug, GoogleDriveService $googleDrive)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $result = $googleDrive->syncWeddingPhotos($wedding);

        if ($result['success']) {
            $photoCount = $result['count'] ?? 0;
            ActivityLog::log('upload', "Sinkronisasi {$photoCount} foto dari Drive untuk galeri {$wedding->couple_name}", 'Wedding', $wedding->id, ['couple_name' => $wedding->couple_name, 'count' => $photoCount]);
            $flashType = !empty($result['is_empty']) ? 'info' : 'success';
            return redirect()->route('weddings.show', $slug)
                ->with($flashType, $result['message']);
        }

        return redirect()->route('weddings.show', $slug)
            ->with('error', $result['message']);
    }

    /**
     * Memperbarui link / ID Folder Google Drive wedding dan langsung melakukan sinkronisasi.
     */
    public function updateDriveFolder(Request $request, string $slug, GoogleDriveService $googleDrive)
    {
        $validated = $request->validate([
            'drive_folder' => 'required|string',
        ]);

        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $cleanedFolderId = GoogleDriveService::extractFolderId($validated['drive_folder']);

        $wedding->update([
            'drive_folder_id' => $cleanedFolderId,
        ]);

        $result = $googleDrive->syncWeddingPhotos($wedding, $cleanedFolderId);

        if ($result['success']) {
            $flashType = !empty($result['is_empty']) ? 'info' : 'success';
            return redirect()->route('weddings.show', $slug)
                ->with($flashType, $result['message']);
        }

        return redirect()->route('weddings.show', $slug)
            ->with('error', $result['message']);
    }

    /**
     * Mengunduh foto dan otomatis mencatat ke log unduhan database dengan waktu realtime device.
     */
    public function downloadPhoto(string $slug, string $fileId, GoogleDriveService $googleDrive)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $photo = Schema::hasTable('photos') 
            ? Photo::where('drive_file_id', $fileId)->first() 
            : null;
        $fileName = $photo ? $photo->nama_file : "wedding_photo_{$fileId}.jpg";

        $clientTime = request()->input('client_time') ?: request()->query('client_time');
        $googleDrive->recordDownload($wedding, $fileId, $fileName, request()->ip(), $clientTime);

        return redirect()->away("https://drive.google.com/uc?export=download&confirm=t&id={$fileId}");
    }

    /**
     * Menampilkan halaman riwayat foto yang dihapus dari Google Drive.
     */
    public function trash(string $slug)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $trashedPhotos = Photo::onlyTrashed()
            ->where('wedding_id', $wedding->id)
            ->latest('deleted_at')
            ->get();

        return view('weddings.trash', compact('wedding', 'trashedPhotos'));
    }

    /**
     * Menghapus foto secara permanen dari database.
     */
    public function forceDeletePhoto(string $slug, int $photoId)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $photo = Photo::onlyTrashed()
            ->where('wedding_id', $wedding->id)
            ->where('id', $photoId)
            ->firstOrFail();

        $namaFile = $photo->nama_file;
        $photo->forceDelete();

        return redirect()->route('weddings.trash', $slug)
            ->with('success', "Foto '{$namaFile}' telah berhasil dihapus permanen dari database.");
    }

    /**
     * Menghapus semua foto di riwayat hapus secara permanen dari database.
     */
    public function forceDeleteAllPhotos(string $slug)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $count = Photo::onlyTrashed()
            ->where('wedding_id', $wedding->id)
            ->count();

        Photo::onlyTrashed()
            ->where('wedding_id', $wedding->id)
            ->forceDelete();

        return redirect()->route('weddings.trash', $slug)
            ->with('success', "Seluruh ({$count}) foto di riwayat hapus telah berhasil dihapus permanen dari database.");
    }

    /**
     * Memulihkan kembali foto dari riwayat hapus ke galeri aktif.
     */
    public function restorePhoto(string $slug, int $photoId)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $photo = Photo::onlyTrashed()
            ->where('wedding_id', $wedding->id)
            ->where('id', $photoId)
            ->firstOrFail();

        $namaFile = $photo->nama_file;
        $photo->restore();

        return redirect()->route('weddings.trash', $slug)
            ->with('success', "Foto '{$namaFile}' telah berhasil dipulihkan kembali ke galeri foto.");
    }

    /**
     * Publish wedding gallery (make accessible to clients with PIN).
     */
    public function publish(string $slug)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $wedding->update(['is_published' => true]);

        $clientUrl = route('client.pin', $slug);

        return redirect()->route('weddings.show', $slug)
            ->with('success', "Galeri {$wedding->couple_name} berhasil DIPUBLISH! Link client: {$clientUrl}");
    }

    /**
     * Unpublish wedding gallery (hide from clients).
     */
    public function unpublish(string $slug)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $wedding->update(['is_published' => false]);

        return redirect()->route('weddings.show', $slug)
            ->with('info', "Galeri {$wedding->couple_name} diset ke UNPUBLISHED. Client tidak bisa mengakses.");
    }

    /**
     * Update the PIN for a wedding gallery.
     */
    public function updatePin(Request $request, string $slug)
    {
        $validated = $request->validate([
            'pin'         => 'required|string|min:4|max:20',
            'pin_enabled' => 'nullable|boolean',
        ]);

        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $wedding->update([
            'pin'         => $validated['pin'],
            'pin_enabled' => $request->boolean('pin_enabled', true),
        ]);

        return redirect()->route('weddings.show', $slug)
            ->with('success', 'PIN galeri berhasil diperbarui.');
    }

    /**
     * Menghapus seluruh proyek wedding secara permanen:
     * Menghapus record wedding, couple name, slug URL, seluruh foto, album, logs, dan semua data terkait.
     */
    public function destroy(string $slug)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $coupleName = $wedding->couple_name;
        $weddingId  = $wedding->id;

        // 1. Hapus semua foto (termasuk yang di-trash / soft-deleted)
        if (Schema::hasTable('photos')) {
            Photo::withTrashed()->where('wedding_id', $weddingId)->forceDelete();
        }

        // 2. Hapus album
        if (Schema::hasTable('albums')) {
            Album::where('wedding_id', $weddingId)->delete();
        }

        // 3. Hapus log unduhan
        if (Schema::hasTable('download_logs')) {
            DownloadLog::where('wedding_id', $weddingId)->delete();
        }

        // 4. Hapus data client visitor
        if (Schema::hasTable('client_visitors')) {
            \App\Models\ClientVisitor::where('wedding_id', $weddingId)->delete();
        }

        // 5. Hapus wishes jika ada
        if (Schema::hasTable('wishes')) {
            \App\Models\Wish::where('wedding_id', $weddingId)->delete();
        }

        // 6. Catat log sebelum hapus wedding
        ActivityLog::log('delete', "Proyek wedding '{$coupleName}' beserta semua datanya dihapus permanen", 'Wedding', $weddingId, ['couple_name' => $coupleName]);

        // 7. Hapus data wedding beserta slug
        $wedding->delete();

        return redirect()->route('weddings.index')
            ->with('success', "Proyek wedding '{$coupleName}' beserta slug dan seluruh datanya telah berhasil dihapus secara permanen.");
    }
}
