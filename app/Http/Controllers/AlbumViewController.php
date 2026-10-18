<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Wedding;
use App\Services\GoogleDriveService;

class AlbumViewController extends Controller
{
    /**
     * Menampilkan pusat pemantauan kapasitas penyimpanan Google Drive & ukuran size wedding.
     */
    public function index(GoogleDriveService $googleDrive)
    {
        // 1. Dapatkan metrik kuota dan kapasitas riil Google Drive
        $storage = $googleDrive->getStorageMetrics();

        // 2. Dapatkan data seluruh wedding beserta ukuran size fotonya
        $weddings = Wedding::with(['albums', 'photos' => function ($query) {
            $query->whereNull('deleted_at');
        }])->latest()->get();

        // 3. Dapatkan seluruh album subfolder terstruktur
        $albums = Album::with(['wedding', 'photos' => function ($query) {
            $query->whereNull('deleted_at');
        }])->orderBy('wedding_id')->orderBy('sort_order')->get();

        return view('albums.index', compact('storage', 'weddings', 'albums'));
    }
}
