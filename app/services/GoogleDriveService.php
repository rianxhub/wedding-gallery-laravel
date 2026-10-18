<?php

namespace App\Services;

use App\Models\Album;
use App\Models\DownloadLog;
use App\Models\Photo;
use App\Models\Wedding;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class GoogleDriveService
{
    protected ?Drive $drive = null;
    protected string $serviceAccountEmail = '';
    protected ?string $apiKey = null;

    public function __construct()
    {
        $this->apiKey = config('services.google.api_key') ?: env('GOOGLE_API_KEY');

        $credentialsPath = storage_path('app/google/service-account.json');

        if (file_exists($credentialsPath)) {
            $json = json_decode(file_get_contents($credentialsPath), true);
            $this->serviceAccountEmail = $json['client_email'] ?? 'gallery-wedding@gallery-wedding-508713.iam.gserviceaccount.com';

            $client = new Client();
            $client->setAuthConfig($credentialsPath);
            $client->addScope([
                Drive::DRIVE_READONLY,
                Drive::DRIVE_METADATA_READONLY,
            ]);

            $this->drive = new Drive($client);
        }
    }

    /**
     * Mendapatkan instance Drive API.
     */
    public function getDrive(): ?Drive
    {
        return $this->drive;
    }

    /**
     * Mengecek apakah GOOGLE_API_KEY telah diatur.
     */
    public function hasApiKey(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Mendapatkan GOOGLE_API_KEY.
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * Mendapatkan email service account.
     */
    public function getServiceAccountEmail(): string
    {
        return $this->serviceAccountEmail;
    }

    /**
     * Mengekstrak Folder ID dari berbagai format link Google Drive.
     */
    public static function extractFolderId(?string $input): string
    {
        if (empty($input)) {
            return '';
        }

        $input = trim($input);

        // Jika berupa link https://drive.google.com/drive/folders/ID...
        if (preg_match('/folders\/([a-zA-Z0-9_-]+)/', $input, $matches)) {
            return $matches[1];
        }

        // Jika berupa link file https://drive.google.com/file/d/ID...
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $input, $matches)) {
            return $matches[1];
        }

        // Jika berupa link dengan parameter id=ID...
        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $input, $matches)) {
            return $matches[1];
        }

        $parts = explode('?', $input);
        return trim($parts[0]);
    }

    /**
     * Menguji koneksi ke Google Drive API.
     */
    public function testConnection(): array
    {
        $response = $this->drive->files->listFiles([
            'pageSize' => 10,
            'fields' => 'files(id, name, mimeType, webViewLink)',
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true,
        ]);

        return [
            'connected' => true,
            'service_account_email' => $this->serviceAccountEmail,
            'files_count' => count($response->getFiles()),
            'files_sample' => $response->getFiles(),
        ];
    }

    /**
     * Mengambil file foto secara rekursif dari folder dan sub-foldernya di Google Drive.
     *
     * @param string $folderId
     * @param int $maxDepth
     * @return array<\Google\Service\Drive\DriveFile>
     */
    public function fetchPhotosRecursively(string $folderId, int $maxDepth = 3): array
    {
        $allFiles = [];
        $foldersToScan = [$folderId];
        $scannedFolders = [];
        $depth = 0;

        while (!empty($foldersToScan) && $depth < $maxDepth) {
            $nextFolders = [];
            $depth++;

            foreach ($foldersToScan as $currentFolderId) {
                if (isset($scannedFolders[$currentFolderId])) {
                    continue;
                }
                $scannedFolders[$currentFolderId] = true;

                $pageToken = null;
                do {
                    $optParams = [
                        'q' => "'{$currentFolderId}' in parents and trashed = false",
                        'fields' => 'nextPageToken, files(id, name, mimeType, thumbnailLink, webContentLink, webViewLink, size)',
                        'pageSize' => 100,
                        'supportsAllDrives' => true,
                        'includeItemsFromAllDrives' => true,
                    ];

                    if ($pageToken) {
                        $optParams['pageToken'] = $pageToken;
                    }

                    $response = $this->drive->files->listFiles($optParams);
                    $items = $response->getFiles();

                    foreach ($items as $item) {
                        if ($item->getMimeType() === 'application/vnd.google-apps.folder') {
                            $nextFolders[] = $item->getId();
                        } else {
                            $allFiles[] = $item;
                        }
                    }

                    $pageToken = $response->getNextPageToken();
                } while ($pageToken);
            }

            $foldersToScan = $nextFolders;
        }

        return $allFiles;
    }

    /**
     * Mengambil file foto secara rekursif dari folder publik menggunakan Google API Key.
     */
    public function fetchPhotosRecursivelyViaApiKey(string $folderId, int $maxDepth = 3): array
    {
        $allFiles = [];
        $foldersToScan = [$folderId];
        $scannedFolders = [];
        $depth = 0;

        while (!empty($foldersToScan) && $depth < $maxDepth) {
            $nextFolders = [];
            $depth++;

            foreach ($foldersToScan as $currentFolderId) {
                if (isset($scannedFolders[$currentFolderId])) {
                    continue;
                }
                $scannedFolders[$currentFolderId] = true;

                $pageToken = null;
                do {
                    $params = [
                        'key' => $this->apiKey,
                        'q' => "'{$currentFolderId}' in parents and trashed = false",
                        'fields' => 'nextPageToken, files(id, name, mimeType, thumbnailLink, webContentLink, webViewLink, size)',
                        'pageSize' => 100,
                        'supportsAllDrives' => 'true',
                        'includeItemsFromAllDrives' => 'true',
                    ];

                    if ($pageToken) {
                        $params['pageToken'] = $pageToken;
                    }

                    $response = Http::timeout(15)->get('https://www.googleapis.com/drive/v3/files', $params);
                    if (!$response->successful()) {
                        break;
                    }

                    $data = $response->json();
                    $items = $data['files'] ?? [];

                    foreach ($items as $item) {
                        if (($item['mimeType'] ?? '') === 'application/vnd.google-apps.folder') {
                            $nextFolders[] = $item['id'];
                        } else {
                            $allFiles[] = $item;
                        }
                    }

                    $pageToken = $data['nextPageToken'] ?? null;
                } while ($pageToken);
            }

            $foldersToScan = $nextFolders;
        }

        return $allFiles;
    }

    /**
     * Mengambil daftar subfolder langsung di dalam sebuah folder Google Drive via API Key.
     *
     * @param string $folderId
     * @return array<array{id: string, name: string}>
     */
    public function fetchDirectSubfoldersViaApiKey(string $folderId): array
    {
        $subfolders = [];
        $pageToken = null;

        do {
            $params = [
                'key' => $this->apiKey,
                'q' => "'{$folderId}' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
                'fields' => 'nextPageToken, files(id, name)',
                'pageSize' => 100,
                'supportsAllDrives' => 'true',
                'includeItemsFromAllDrives' => 'true',
                'orderBy' => 'name',
            ];

            if ($pageToken) {
                $params['pageToken'] = $pageToken;
            }

            $response = Http::timeout(15)->get('https://www.googleapis.com/drive/v3/files', $params);
            if (!$response->successful()) {
                break;
            }

            $data = $response->json();
            foreach ($data['files'] ?? [] as $sub) {
                $subfolders[] = [
                    'id' => $sub['id'],
                    'name' => $sub['name'],
                ];
            }

            $pageToken = $data['nextPageToken'] ?? null;
        } while ($pageToken);

        return $subfolders;
    }

    /**
     * Mengambil daftar subfolder langsung di dalam sebuah folder Google Drive via Service Account.
     *
     * @param string $folderId
     * @return array<array{id: string, name: string}>
     */
    public function fetchDirectSubfoldersViaServiceAccount(string $folderId): array
    {
        if (!$this->drive) {
            return [];
        }

        $subfolders = [];
        $pageToken = null;

        do {
            $optParams = [
                'q' => "'{$folderId}' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
                'fields' => 'nextPageToken, files(id, name)',
                'pageSize' => 100,
                'supportsAllDrives' => true,
                'includeItemsFromAllDrives' => true,
                'orderBy' => 'name',
            ];

            if ($pageToken) {
                $optParams['pageToken'] = $pageToken;
            }

            $response = $this->drive->files->listFiles($optParams);
            foreach ($response->getFiles() as $sub) {
                $subfolders[] = [
                    'id' => $sub->getId(),
                    'name' => $sub->getName(),
                ];
            }

            $pageToken = $response->getNextPageToken();
        } while ($pageToken);

        return $subfolders;
    }

    /**
     * Mengambil file foto langsung di dalam folder root (bukan di subfolder) via API Key.
     */
    public function fetchDirectPhotosViaApiKey(string $folderId): array
    {
        $directFiles = [];
        $pageToken = null;

        do {
            $params = [
                'key' => $this->apiKey,
                'q' => "'{$folderId}' in parents and mimeType != 'application/vnd.google-apps.folder' and trashed = false",
                'fields' => 'nextPageToken, files(id, name, mimeType, thumbnailLink, webContentLink, webViewLink, size)',
                'pageSize' => 100,
                'supportsAllDrives' => 'true',
                'includeItemsFromAllDrives' => 'true',
            ];

            if ($pageToken) {
                $params['pageToken'] = $pageToken;
            }

            $response = Http::timeout(15)->get('https://www.googleapis.com/drive/v3/files', $params);
            if (!$response->successful()) {
                break;
            }

            $data = $response->json();
            foreach ($data['files'] ?? [] as $item) {
                $directFiles[] = $item;
            }

            $pageToken = $data['nextPageToken'] ?? null;
        } while ($pageToken);

        return $directFiles;
    }

    /**
     * Mengambil file foto langsung di dalam folder root (bukan di subfolder) via Service Account.
     */
    public function fetchDirectPhotosViaServiceAccount(string $folderId): array
    {
        if (!$this->drive) {
            return [];
        }

        $directFiles = [];
        $pageToken = null;

        do {
            $optParams = [
                'q' => "'{$folderId}' in parents and mimeType != 'application/vnd.google-apps.folder' and trashed = false",
                'fields' => 'nextPageToken, files(id, name, mimeType, thumbnailLink, webContentLink, webViewLink, size)',
                'pageSize' => 100,
                'supportsAllDrives' => true,
                'includeItemsFromAllDrives' => true,
            ];

            if ($pageToken) {
                $optParams['pageToken'] = $pageToken;
            }

            $response = $this->drive->files->listFiles($optParams);
            foreach ($response->getFiles() as $item) {
                $directFiles[] = $item;
            }

            $pageToken = $response->getNextPageToken();
        } while ($pageToken);

        return $directFiles;
    }

    /**
     * Otomatis membuat atau memperbarui Album di database berdasarkan subfolder Google Drive.
     *
     * @param Wedding $wedding
     * @param array<array{id: string, name: string}> $subfolders
     * @return array<Album>
     */
    public function syncAlbumsFromSubfolders(Wedding $wedding, array $subfolders): array
    {
        $syncedAlbums = [];

        foreach ($subfolders as $index => $sub) {
            $syncedAlbums[] = Album::updateOrCreate(
                [
                    'wedding_id' => $wedding->id,
                    'drive_subfolder_id' => $sub['id'],
                ],
                [
                    'album_name' => $sub['name'],
                    'sort_order' => $index + 1,
                ]
            );
        }

        // Hapus album Google Drive lama yang subfoldernya sudah tidak ada lagi di folder ini
        if (!empty($subfolders)) {
            $currentSubfolderIds = array_column($subfolders, 'id');
            Album::where('wedding_id', $wedding->id)
                ->whereNotNull('drive_subfolder_id')
                ->whereNotIn('drive_subfolder_id', $currentSubfolderIds)
                ->delete();
        } else {
            Album::where('wedding_id', $wedding->id)
                ->whereNotNull('drive_subfolder_id')
                ->delete();
        }

        return $syncedAlbums;
    }

    /**
     * Memindahkan foto yang sudah tidak ada lagi di Google Drive ke Riwayat Hapus (Soft Delete).
     * Foto tidak akan tampil di galeri publik, namun datanya tetap aman di database.
     *
     * @param Wedding $wedding
     * @param array<string> $currentDriveIds
     * @return int Jumlah foto yang dipindahkan ke riwayat hapus
     */
    public function softDeleteMissingPhotos(Wedding $wedding, array $currentDriveIds): int
    {
        $missingPhotos = Photo::where('wedding_id', $wedding->id)
            ->whereNull('deleted_at')
            ->whereNotIn('drive_file_id', $currentDriveIds)
            ->get();

        $count = $missingPhotos->count();
        foreach ($missingPhotos as $photo) {
            $photo->delete();
        }

        return $count;
    }

    /**
     * Sinkronisasi foto menggunakan Google API Key (Cukup modal link/ID folder dengan akses publik).
     */
    public function syncViaApiKey(Wedding $wedding, string $targetFolderId): array
    {
        try {
            // 1. Cek folder dengan Google API Key
            $folderResponse = Http::timeout(15)->get("https://www.googleapis.com/drive/v3/files/{$targetFolderId}", [
                'key' => $this->apiKey,
                'fields' => 'id, name, mimeType, trashed',
                'supportsAllDrives' => 'true',
            ]);

            if ($folderResponse->status() === 404 || $folderResponse->status() === 403) {
                return [
                    'success' => false,
                    'is_not_public' => true,
                    'message' => "Folder Google Drive (ID: {$targetFolderId}) belum diatur ke publik. Pastikan Akses Umum diatur ke 'Siapa saja yang memiliki link' sebagai Pelihat.",
                ];
            }

            if (!$folderResponse->successful()) {
                $err = $folderResponse->json('error.message') ?? $folderResponse->body();
                return [
                    'success' => false,
                    'message' => "Gagal mengakses Google Drive API: {$err}",
                ];
            }

            $folderData = $folderResponse->json();

            if (!empty($folderData['trashed'])) {
                return [
                    'success' => false,
                    'message' => "Folder Google Drive '{$folderData['name']}' berada di Sampah (Trash).",
                ];
            }

            if (($folderData['mimeType'] ?? '') !== 'application/vnd.google-apps.folder') {
                return [
                    'success' => false,
                    'message' => "Tautan yang dimasukkan mengarah ke file, bukan folder Google Drive.",
                ];
            }

            // 2. Deteksi subfolder langsung dan otomatis buat/perbarui Album di database
            $subfolders = $this->fetchDirectSubfoldersViaApiKey($targetFolderId);
            $syncedAlbums = $this->syncAlbumsFromSubfolders($wedding, $subfolders);
            $albumCount = count($syncedAlbums);

            // Buat mapping drive_subfolder_id -> Album model ID
            $albumMap = [];
            foreach ($syncedAlbums as $album) {
                if ($album->drive_subfolder_id) {
                    $albumMap[$album->drive_subfolder_id] = $album->id;
                }
            }

            // 3. Ambil file foto secara terkelompok:
            // a) Dari setiap subfolder (album)
            $collectedFiles = [];
            $seenFileIds = [];

            foreach ($subfolders as $sub) {
                $albumId = $albumMap[$sub['id']] ?? null;
                $subFiles = $this->fetchPhotosRecursivelyViaApiKey($sub['id']);
                foreach ($subFiles as $sf) {
                    if (($sf['mimeType'] ?? '') !== 'application/vnd.google-apps.folder' && !isset($seenFileIds[$sf['id']])) {
                        $seenFileIds[$sf['id']] = true;
                        $collectedFiles[] = [
                            'file' => $sf,
                            'album_id' => $albumId,
                        ];
                    }
                }
            }

            // b) Dari root folder utama langsung (untuk foto yang tidak dimasukkan ke subfolder)
            $rootFiles = $this->fetchDirectPhotosViaApiKey($targetFolderId);
            foreach ($rootFiles as $rf) {
                if (($rf['mimeType'] ?? '') !== 'application/vnd.google-apps.folder' && !isset($seenFileIds[$rf['id']])) {
                    $seenFileIds[$rf['id']] = true;
                    $collectedFiles[] = [
                        'file' => $rf,
                        'album_id' => null,
                    ];
                }
            }

            $currentDriveFileIds = array_keys($seenFileIds);

            // 4. Deteksi foto yang dihapus dari Google Drive -> masukkan ke Riwayat Hapus (Soft Delete)
            $trashedCount = $this->softDeleteMissingPhotos($wedding, $currentDriveFileIds);

            if (empty($collectedFiles)) {
                $albumMsg = $albumCount > 0 
                    ? " Ditemukan {$albumCount} subfolder album (" . implode(', ', array_column($subfolders, 'name')) . ") yang berhasil dicatat." 
                    : "";

                $trashMsg = $trashedCount > 0 
                    ? " ({$trashedCount} foto yang dihapus dari Drive dipindahkan ke Riwayat Hapus)." 
                    : "";

                return [
                    'success' => true,
                    'is_empty' => true,
                    'message' => "Folder Google Drive '{$folderData['name']}' (ID: {$targetFolderId}) berhasil terhubung via Link Publik!{$albumMsg}{$trashMsg} Namun belum ada foto aktif di dalamnya.",
                    'synced_count' => 0,
                    'synced_albums_count' => $albumCount,
                    'trashed_count' => $trashedCount,
                    'folder_id' => $targetFolderId,
                    'folder_name' => $folderData['name'],
                ];
            }

            $syncedCount = 0;
            $syncedPhotos = [];

            foreach ($collectedFiles as $item) {
                $file = $item['file'];
                $albumId = $item['album_id'];
                $fileId = $file['id'];
                $fileName = $file['name'] ?? "photo_{$fileId}.jpg";
                $mimeType = $file['mimeType'] ?? 'image/jpeg';
                $thumbnail = !empty($file['thumbnailLink']) 
                    ? preg_replace('/=s\d+$/', '=s800', $file['thumbnailLink']) 
                    : "https://lh3.googleusercontent.com/d/{$fileId}=w800-rw";

                $photo = Photo::withTrashed()->updateOrCreate(
                    [
                        'drive_file_id' => $fileId,
                    ],
                    [
                        'wedding_id' => $wedding->id,
                        'album_id' => $albumId,
                        'nama_file' => $fileName,
                        'mime_type' => $mimeType,
                        'file_size' => (int) ($file['size'] ?? 0),
                        'thumbnail_url' => $thumbnail,
                        'deleted_at' => null, // Pulihkan otomatis jika diunggah kembali ke Drive
                    ]
                );

                $syncedPhotos[] = $photo;
                $syncedCount++;
            }

            $albumInfo = $albumCount > 0 
                ? " dan {$albumCount} album otomatis (" . implode(', ', array_column($subfolders, 'name')) . ")" 
                : "";

            $trashInfo = $trashedCount > 0 
                ? " ({$trashedCount} foto terdeteksi dihapus dari Drive & masuk Riwayat Hapus)" 
                : "";

            return [
                'success' => true,
                'is_empty' => false,
                'message' => "Berhasil menyinkronkan {$syncedCount} foto{$albumInfo}{$trashInfo} dari Google Drive publik '{$folderData['name']}'!",
                'synced_count' => $syncedCount,
                'synced_albums_count' => $albumCount,
                'trashed_count' => $trashedCount,
                'photos' => $syncedPhotos,
                'albums' => $syncedAlbums,
                'folder_id' => $targetFolderId,
                'folder_name' => $folderData['name'],
            ];

        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => "Kendala Google API Key: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Sinkronisasi foto menggunakan Google Service Account.
     */
    public function syncViaServiceAccount(Wedding $wedding, string $targetFolderId): array
    {
        if (!$this->drive) {
            return [
                'success' => false,
                'message' => 'Service Account belum dikonfigurasi.',
                'synced_count' => 0,
                'folder_id' => $targetFolderId,
            ];
        }

        try {
            // 1. Verifikasi folder Google Drive via Service Account
            $folderMetadata = $this->drive->files->get($targetFolderId, [
                'fields' => 'id, name, mimeType, trashed',
                'supportsAllDrives' => true,
            ]);

            if ($folderMetadata->getTrashed()) {
                return [
                    'success' => false,
                    'message' => "Folder Google Drive '{$folderMetadata->getName()}' (ID: {$targetFolderId}) berada di Sampah (Trash).",
                    'synced_count' => 0,
                    'folder_id' => $targetFolderId,
                ];
            }

            if ($folderMetadata->getMimeType() !== 'application/vnd.google-apps.folder') {
                return [
                    'success' => false,
                    'message' => "Tautan yang Anda masukkan adalah file ('{$folderMetadata->getName()}'), bukan folder Google Drive.",
                    'synced_count' => 0,
                    'folder_id' => $targetFolderId,
                ];
            }

            // 2. Deteksi subfolder langsung dan otomatis buat/perbarui Album di database
            $subfolders = $this->fetchDirectSubfoldersViaServiceAccount($targetFolderId);
            $syncedAlbums = $this->syncAlbumsFromSubfolders($wedding, $subfolders);
            $albumCount = count($syncedAlbums);

            $albumMap = [];
            foreach ($syncedAlbums as $album) {
                if ($album->drive_subfolder_id) {
                    $albumMap[$album->drive_subfolder_id] = $album->id;
                }
            }

            // 3. Ambil file foto secara terkelompok:
            // a) Dari setiap subfolder
            $collectedFiles = [];
            $seenFileIds = [];

            foreach ($subfolders as $sub) {
                $albumId = $albumMap[$sub['id']] ?? null;
                $subFiles = $this->fetchPhotosRecursively($sub['id']);
                foreach ($subFiles as $sf) {
                    if ($sf->getMimeType() !== 'application/vnd.google-apps.folder' && !isset($seenFileIds[$sf->getId()])) {
                        $seenFileIds[$sf->getId()] = true;
                        $collectedFiles[] = [
                            'file' => $sf,
                            'album_id' => $albumId,
                        ];
                    }
                }
            }

            // b) Dari root folder langsung
            $rootFiles = $this->fetchDirectPhotosViaServiceAccount($targetFolderId);
            foreach ($rootFiles as $rf) {
                if ($rf->getMimeType() !== 'application/vnd.google-apps.folder' && !isset($seenFileIds[$rf->getId()])) {
                    $seenFileIds[$rf->getId()] = true;
                    $collectedFiles[] = [
                        'file' => $rf,
                        'album_id' => null,
                    ];
                }
            }

            $currentDriveFileIds = array_keys($seenFileIds);

            // 4. Deteksi foto yang dihapus dari Google Drive -> masukkan ke Riwayat Hapus (Soft Delete)
            $trashedCount = $this->softDeleteMissingPhotos($wedding, $currentDriveFileIds);

            if (empty($collectedFiles)) {
                $albumMsg = $albumCount > 0 
                    ? " Ditemukan {$albumCount} subfolder album (" . implode(', ', array_column($subfolders, 'name')) . ") yang berhasil dicatat." 
                    : "";

                $trashMsg = $trashedCount > 0 
                    ? " ({$trashedCount} foto yang dihapus dari Drive dipindahkan ke Riwayat Hapus)." 
                    : "";

                return [
                    'success' => true,
                    'is_empty' => true,
                    'message' => "Folder Google Drive '{$folderMetadata->getName()}' (ID: {$targetFolderId}) berhasil terhubung!{$albumMsg}{$trashMsg} Namun belum ditemukan foto aktif di dalamnya.",
                    'synced_count' => 0,
                    'synced_albums_count' => $albumCount,
                    'trashed_count' => $trashedCount,
                    'folder_id' => $targetFolderId,
                    'folder_name' => $folderMetadata->getName(),
                ];
            }

            $syncedCount = 0;
            $syncedPhotos = [];

            foreach ($collectedFiles as $item) {
                $file = $item['file'];
                $albumId = $item['album_id'];

                $thumbnail = $file->getThumbnailLink() 
                    ? preg_replace('/=s\d+$/', '=s800', $file->getThumbnailLink())
                    : "https://lh3.googleusercontent.com/d/{$file->getId()}=w800-rw";

                $photo = Photo::withTrashed()->updateOrCreate(
                    [
                        'drive_file_id' => $file->getId(),
                    ],
                    [
                        'wedding_id' => $wedding->id,
                        'album_id' => $albumId,
                        'nama_file' => $file->getName(),
                        'mime_type' => $file->getMimeType(),
                        'file_size' => (int) ($file->getSize() ?? 0),
                        'thumbnail_url' => $thumbnail,
                        'deleted_at' => null, // Pulihkan otomatis jika diunggah kembali ke Drive
                    ]
                );

                $syncedPhotos[] = $photo;
                $syncedCount++;
            }

            $albumInfo = $albumCount > 0 
                ? " dan {$albumCount} album otomatis (" . implode(', ', array_column($subfolders, 'name')) . ")" 
                : "";

            $trashInfo = $trashedCount > 0 
                ? " ({$trashedCount} foto terdeteksi dihapus dari Drive & masuk Riwayat Hapus)" 
                : "";

            return [
                'success' => true,
                'is_empty' => false,
                'message' => "Berhasil menyinkronkan {$syncedCount} foto{$albumInfo}{$trashInfo} dari Google Drive '{$folderMetadata->getName()}'.",
                'synced_count' => $syncedCount,
                'synced_albums_count' => $albumCount,
                'trashed_count' => $trashedCount,
                'photos' => $syncedPhotos,
                'albums' => $syncedAlbums,
                'folder_id' => $targetFolderId,
                'folder_name' => $folderMetadata->getName(),
            ];
        } catch (Throwable $e) {
            $errorMessage = $e->getMessage();
            $email = $this->serviceAccountEmail;

            if (str_contains($errorMessage, '404') || str_contains($errorMessage, 'notFound')) {
                return [
                    'success' => false,
                    'is_not_found' => true,
                    'message' => "Folder Google Drive (ID: {$targetFolderId}) belum dibagikan ke Service Account atau belum diset publik.",
                    'synced_count' => 0,
                    'folder_id' => $targetFolderId,
                    'service_account_email' => $email,
                ];
            }

            if (str_contains($errorMessage, '403') || str_contains($errorMessage, 'accessNotConfigured')) {
                return [
                    'success' => false,
                    'message' => "Akses Google Drive ditolak (403).",
                    'synced_count' => 0,
                    'folder_id' => $targetFolderId,
                    'service_account_email' => $email,
                ];
            }

            return [
                'success' => false,
                'message' => "Kendala koneksi Google Drive: " . $errorMessage,
                'synced_count' => 0,
                'folder_id' => $targetFolderId,
                'service_account_email' => $email,
            ];
        }
    }

    /**
     * Mengambil daftar file dari sebuah folder Google Drive.
     */
    public function listFilesFromFolder(string $folderId, int $pageSize = 100): array
    {
        try {
            $folderId = self::extractFolderId($folderId);
            if ($this->hasApiKey()) {
                return $this->fetchPhotosRecursivelyViaApiKey($folderId);
            }
            return $this->fetchPhotosRecursively($folderId);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Menghubungkan & menyinkronkan foto dari folder Google Drive ke tabel `photos` di database.
     * Otomatis mencoba Public API Key terlebih dahulu, lalu Service Account jika diperlukan.
     */
    public function syncWeddingPhotos(Wedding $wedding, ?string $folderId = null): array
    {
        $rawFolderId = $folderId ?: $wedding->drive_folder_id;
        $targetFolderId = self::extractFolderId($rawFolderId);

        if (empty($targetFolderId)) {
            return [
                'success' => false,
                'message' => 'ID folder Google Drive belum diatur untuk wedding ini.',
                'synced_count' => 0,
            ];
        }

        // STRATEGI 1: Gunakan Google API Key jika tersedia (Cukup modal Link Folder Publik)
        if ($this->hasApiKey()) {
            $apiKeyResult = $this->syncViaApiKey($wedding, $targetFolderId);
            if ($apiKeyResult['success']) {
                return $apiKeyResult;
            }
        }

        // STRATEGI 2: Gunakan Service Account jika tersedia
        if ($this->drive) {
            $saResult = $this->syncViaServiceAccount($wedding, $targetFolderId);
            if ($saResult['success']) {
                return $saResult;
            }
        }

        // Jika belum berhasil: Berikan pesan solusi yang jelas untuk kedua cara
        $email = $this->serviceAccountEmail;
        return [
            'success' => false,
            'message' => "Folder Google Drive (ID: {$targetFolderId}) belum dapat diakses:\n" .
                "• Cara 1 (Paling Mudah): Buka folder di Google Drive -> Bagikan -> Ubah Akses Umum menjadi 'Siapa saja yang memiliki link' (Pelihat).\n" .
                ($email ? "• Cara 2: Tambahkan email Service Account: {$email} sebagai Pelihat." : ""),
            'synced_count' => 0,
            'folder_id' => $targetFolderId,
            'service_account_email' => $email,
        ];
    }

    /**
     * Mendapatkan metadata dan konten file untuk pengunduhan.
     */
    public function getDownloadStream(string $fileId)
    {
        $file = $this->drive->files->get($fileId, [
            'fields' => 'id, name, mimeType, size',
            'supportsAllDrives' => true,
        ]);
        $response = $this->drive->files->get($fileId, [
            'alt' => 'media',
            'supportsAllDrives' => true,
        ]);

        return [
            'file' => $file,
            'stream' => $response->getBody(),
        ];
    }

    /**
     * Mencatat log unduhan ke database dengan waktu realtime device.
     */
    public function recordDownload(Wedding $wedding, string $fileId, string $fileName, ?string $ipAddress = null, ?string $clientTime = null): DownloadLog
    {
        $downloadedAt = now();
        if (!empty($clientTime)) {
            try {
                $downloadedAt = \Carbon\Carbon::parse($clientTime)->timezone(config('app.timezone', 'Asia/Jakarta'));
            } catch (\Throwable $e) {
                $downloadedAt = now();
            }
        }

        return DownloadLog::create([
            'wedding_id' => $wedding->id,
            'drive_file_id' => $fileId,
            'file_name' => $fileName,
            'ip_address' => $ipAddress ?: request()->ip(),
            'downloaded_at' => $downloadedAt,
        ]);
    }

    /**
     * Mengambil metrik kuota dan kapasitas penyimpanan Google Drive.
     *
     * @return array
     */
    public function getStorageMetrics(): array
    {
        $defaultLimit = 15 * 1024 * 1024 * 1024; // Standar Google Drive Free (15 GB)
        $quota = [
            'limit' => $defaultLimit,
            'usage' => 0,
            'free' => $defaultLimit,
            'usage_percentage' => 0,
            'source' => 'local_calculated',
            'account_email' => $this->serviceAccountEmail,
        ];

        // 1. Coba ambil kuota riil dari Google Drive Service Account
        if ($this->drive) {
            try {
                $about = $this->drive->about->get([
                    'fields' => 'storageQuota, user',
                ]);
                $storageQuota = $about->getStorageQuota();
                if ($storageQuota) {
                    $limit = (int) $storageQuota->getLimit();
                    $usage = (int) $storageQuota->getUsage();
                    if ($limit > 0) {
                        $quota['limit'] = $limit;
                        $quota['usage'] = $usage;
                        $quota['free'] = max(0, $limit - $usage);
                        $quota['usage_percentage'] = min(100, round(($usage / $limit) * 100, 1));
                        $quota['source'] = 'google_drive_api';
                    }
                }
            } catch (\Throwable $e) {
                // Abaikan jika tidak didukung API
            }
        }

        // 2. Hitung akumulasi ukuran seluruh foto aktif di database
        $totalPhotosBytes = (int) Photo::whereNull('deleted_at')->sum('file_size');
        $totalPhotosCount = (int) Photo::whereNull('deleted_at')->count();

        if ($quota['usage'] <= 0 && $totalPhotosBytes > 0) {
            $quota['usage'] = $totalPhotosBytes;
            $quota['free'] = max(0, $quota['limit'] - $totalPhotosBytes);
            $quota['usage_percentage'] = min(100, round(($totalPhotosBytes / $quota['limit']) * 100, 1));
        }

        $quota['total_photos_bytes'] = $totalPhotosBytes;
        $quota['total_photos_count'] = $totalPhotosCount;
        $quota['formatted_limit'] = self::formatBytes($quota['limit']);
        $quota['formatted_usage'] = self::formatBytes($quota['usage']);
        $quota['formatted_free'] = self::formatBytes($quota['free']);
        $quota['formatted_photos_bytes'] = self::formatBytes($totalPhotosBytes);

        return $quota;
    }

    /**
     * Format byte integer menjadi format manusiawi yang mudah dibaca (KB, MB, GB, TB).
     */
    public static function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = min(4, floor(log($bytes, 1024)));
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }
}