<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Wedding;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Throwable;

class GoogleDriveController extends Controller
{
    /**
     * Uji koneksi ke Google Drive API Service Account.
     */
    public function test(GoogleDriveService $googleDrive)
    {
        try {
            $result = $googleDrive->testConnection();

            return response()->json([
                'success' => true,
                'message' => 'Google Drive API berhasil terhubung dengan Service Account!',
                'data' => $result,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke Google Drive: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengambil daftar file dari Google Drive berdasarkan folder_id.
     */
    public function listFiles(Request $request, GoogleDriveService $googleDrive)
    {
        $folderId = $request->query('folder_id');

        if (!$folderId) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter folder_id wajib disertakan.',
            ], 422);
        }

        try {
            $files = $googleDrive->listFilesFromFolder($folderId);

            return response()->json([
                'success' => true,
                'message' => 'Daftar file Google Drive berhasil diambil.',
                'data' => $files,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil file Google Drive: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sinkronisasi foto dari Google Drive ke database MySQL untuk Wedding tertentu.
     */
    public function sync(Wedding $wedding, GoogleDriveService $googleDrive)
    {
        $result = $googleDrive->syncWeddingPhotos($wedding);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'synced_count' => $result['synced_count'],
            'wedding' => $wedding->load('photos'),
        ]);
    }

    /**
     * Mengunduh foto dari Google Drive dan mencatat riwayat ke tabel `download_logs`.
     */
    public function download(Wedding $wedding, string $fileId, GoogleDriveService $googleDrive)
    {
        $photo = Photo::where('drive_file_id', $fileId)->first();
        $fileName = $photo ? $photo->nama_file : "photo_{$fileId}.jpg";

        // 1. Catat log unduhan ke database
        $googleDrive->recordDownload($wedding, $fileId, $fileName, request()->ip());

        // 2. Redirect ke direct download link Google Drive
        return redirect()->away("https://drive.google.com/uc?export=download&id={$fileId}");
    }
}