<?php

use App\Http\Controllers\Api\AlbumController;
use App\Http\Controllers\Api\DownloadLogController;
use App\Http\Controllers\Api\GoogleDriveController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WeddingController;
use App\Http\Controllers\Api\WishController;
use Illuminate\Support\Facades\Route;

// Prefix all API route names with "api." so they do not collide with Web View route names
Route::name('api.')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('weddings', WeddingController::class);
    Route::apiResource('albums', AlbumController::class);
    Route::apiResource('download-logs', DownloadLogController::class)->only(['index', 'store', 'show']);

    Route::apiResource('photos', PhotoController::class);
    Route::apiResource('wishes', WishController::class);

    // Google Drive Integrations
    Route::get('/google-drive/test', [GoogleDriveController::class, 'test'])->name('google-drive.test');
    Route::get('/google-drive/files', [GoogleDriveController::class, 'listFiles'])->name('google-drive.files');
    Route::post('/weddings/{wedding}/sync-drive', [GoogleDriveController::class, 'sync'])->name('weddings.sync-drive');
    Route::get('/weddings/{wedding}/download/{fileId}', [GoogleDriveController::class, 'download'])->name('weddings.download');
});