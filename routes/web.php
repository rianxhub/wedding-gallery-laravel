<?php

use App\Http\Controllers\AlbumViewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientGalleryController;
use App\Http\Controllers\DownloadLogViewController;
use App\Http\Controllers\UserViewController;
use App\Http\Controllers\WeddingViewController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// ============================================================
// AUTHENTICATION ROUTES (Admin Login & Logout)
// ============================================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// CLIENT / GUEST GALLERY ROUTES (Public — Clean Guest Interface)
// ============================================================
Route::get('/gallery/{slug}', [ClientGalleryController::class, 'showPin'])->name('client.pin');
Route::post('/gallery/{slug}/verify', [ClientGalleryController::class, 'verifyPin'])->name('client.verify-pin');
Route::get('/gallery/{slug}/view', [ClientGalleryController::class, 'gallery'])->name('client.gallery');
Route::post('/gallery/{slug}/download/{fileId}', [ClientGalleryController::class, 'asyncDownload'])->name('client.download.async');

// ============================================================
// ADMIN / STUDIO MANAGEMENT ROUTES (Protected: auth & admin)
// ============================================================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [WeddingViewController::class, 'index'])->name('home');

    // Wedding CRUD & Management
    Route::get('/weddings', [WeddingViewController::class, 'index'])->name('weddings.index');
    Route::get('/weddings/create', [WeddingViewController::class, 'create'])->name('weddings.create');
    Route::post('/weddings', [WeddingViewController::class, 'store'])->name('weddings.store');
    Route::get('/weddings/{slug}', [WeddingViewController::class, 'show'])->name('weddings.show'); // Admin preview only
    Route::get('/weddings/{slug}/preview', [WeddingViewController::class, 'show'])->name('weddings.preview');
    Route::get('/weddings/{slug}/edit', [WeddingViewController::class, 'edit'])->name('weddings.edit');
    Route::post('/weddings/{slug}/update', [WeddingViewController::class, 'update'])->name('weddings.update');
    Route::put('/weddings/{slug}', [WeddingViewController::class, 'update']);
    Route::delete('/weddings/{slug}', [WeddingViewController::class, 'destroy'])->name('weddings.destroy');
    Route::post('/weddings/{slug}/destroy', [WeddingViewController::class, 'destroy']);

    // Google Drive Sync & Actions
    Route::get('/weddings/{slug}/sync', [WeddingViewController::class, 'syncDrive'])->name('weddings.sync');
    Route::post('/weddings/{slug}/update-folder', [WeddingViewController::class, 'updateDriveFolder'])->name('weddings.update-folder');
    Route::get('/weddings/{slug}/download/{fileId}', [WeddingViewController::class, 'downloadPhoto'])->name('weddings.download');
    
    // Trash Management
    Route::get('/weddings/{slug}/trash', [WeddingViewController::class, 'trash'])->name('weddings.trash');
    Route::post('/weddings/{slug}/trash/{photoId}/force-delete', [WeddingViewController::class, 'forceDeletePhoto'])->name('weddings.trash.force-delete');
    Route::post('/weddings/{slug}/trash/force-delete-all', [WeddingViewController::class, 'forceDeleteAllPhotos'])->name('weddings.trash.force-delete-all');
    Route::post('/weddings/{slug}/trash/{photoId}/restore', [WeddingViewController::class, 'restorePhoto'])->name('weddings.trash.restore');

    // Publish & Security Settings
    Route::post('/weddings/{slug}/publish', [WeddingViewController::class, 'publish'])->name('weddings.publish');
    Route::post('/weddings/{slug}/unpublish', [WeddingViewController::class, 'unpublish'])->name('weddings.unpublish');
    Route::post('/weddings/{slug}/update-pin', [WeddingViewController::class, 'updatePin'])->name('weddings.update-pin');

    // Album Views
    Route::get('/albums', [AlbumViewController::class, 'index'])->name('albums.index');
    Route::get('/albums-gallery', [AlbumViewController::class, 'index']);

    // User Views & Management
    Route::get('/users', [UserViewController::class, 'index'])->name('users.index');
    Route::get('/users-list', [UserViewController::class, 'index']);
    Route::post('/users', [UserViewController::class, 'store'])->name('users.store');
    Route::delete('/users/{id}', [UserViewController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/destroy', [UserViewController::class, 'destroy']);

    // Download Logs Views
    Route::get('/download-logs', [DownloadLogViewController::class, 'index'])->name('logs.index');
    Route::get('/download-history', [DownloadLogViewController::class, 'index']);

    // Activity Log (Notification Bell)
    Route::post('/notifications/mark-read', function () {
        \App\Models\ActivityLog::where('is_read', false)->update(['is_read' => true]);
        return response()->json(['ok' => true]);
    })->name('notifications.mark-read');

    Route::get('/notifications/data', function () {
        $logs = \App\Models\ActivityLog::with('user')->latest()->limit(30)->get();
        $unread = \App\Models\ActivityLog::unread()->count();
        return response()->json([
            'unread'  => $unread,
            'upload'  => $logs->where('action', 'upload')->values(),
            'edit'    => $logs->where('action', 'edit')->values(),
            'delete'  => $logs->where('action', 'delete')->values(),
        ]);
    })->name('notifications.data');
});

// Helper Route untuk Eksekusi Migrasi & Seeder via Browser (Local Development)
Route::get('/migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        return response("<div style='font-family:sans-serif;max-width:800px;margin:40px auto;background:#0f172a;color:#f8fafc;padding:30px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.2);'>
            <h2 style='color:#38bdf8;margin-top:0;'>✨ Database Migration Berhasil!</h2>
            <pre style='background:#1e293b;padding:15px;border-radius:8px;color:#a5f3fc;overflow-x:auto;'>" . e($output) . "</pre>
            <div style='margin-top:20px;display:flex;gap:15px;'>
                <a href='/' style='background:#dfb76c;color:#0f172a;padding:10px 20px;text-decoration:none;border-radius:6px;font-weight:600;'>&larr; Buka Dashboard</a>
                <a href='/migrate/seed' style='background:#334155;color:#f8fafc;padding:10px 20px;text-decoration:none;border-radius:6px;'>Jalankan Seeder &rarr;</a>
            </div>
        </div>");
    } catch (\Throwable $e) {
        return response("<div style='font-family:sans-serif;max-width:800px;margin:40px auto;background:#7f1d1d;color:#ffffff;padding:30px;border-radius:12px;'>
            <h2 style='margin-top:0;'>❌ Migrasi Gagal:</h2>
            <pre style='background:#450a0a;padding:15px;border-radius:8px;color:#fca5a5;white-space:pre-wrap;'>" . e($e->getMessage()) . "</pre>
        </div>", 500);
    }
});

Route::get('/migrate/seed', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        $output = Artisan::output();
        return response("<div style='font-family:sans-serif;max-width:800px;margin:40px auto;background:#0f172a;color:#f8fafc;padding:30px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.2);'>
            <h2 style='color:#4ade80;margin-top:0;'>🎉 Database Migration & Seeder Selesai!</h2>
            <pre style='background:#1e293b;padding:15px;border-radius:8px;color:#86efac;overflow-x:auto;'>" . e($output) . "</pre>
            <div style='margin-top:20px;'>
                <a href='/' style='background:#dfb76c;color:#0f172a;padding:10px 20px;text-decoration:none;border-radius:6px;font-weight:600;'>&larr; Buka Halaman Dashboard Sekarang</a>
            </div>
        </div>");
    } catch (\Throwable $e) {
        return response("<div style='font-family:sans-serif;max-width:800px;margin:40px auto;background:#7f1d1d;color:#ffffff;padding:30px;border-radius:12px;'>
            <h2 style='margin-top:0;'>❌ Migrasi / Seeder Gagal:</h2>
            <pre style='background:#450a0a;padding:15px;border-radius:8px;color:#fca5a5;white-space:pre-wrap;'>" . e($e->getMessage()) . "</pre>
        </div>", 500);
    }
});

// ============================================================
// PUBLIC SHORT ROUTE (Parameter {slug} langsung)
// Ditempatkan di bagian paling bawah agar tidak membenturkan rute sistem
// ============================================================
Route::get('/{slug}', [ClientGalleryController::class, 'showPublic'])
    ->where('slug', '^(?!login|logout|weddings|albums|users|download-logs|migrate|up|api).*$')
    ->name('client.short');
