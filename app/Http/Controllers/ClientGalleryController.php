<?php

namespace App\Http\Controllers;

use App\Models\ClientVisitor;
use App\Models\Photo;
use App\Models\Wedding;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ClientGalleryController extends Controller
{
    /**
     * Halaman Masukan PIN Tamu — Tampil saat tamu mengakses rute publik {slug} atau /gallery/{slug}.
     */
    public function showPin(string $slug)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        // Jika PIN Protection tidak diaktifkan, langsung arahkan ke galeri tamu
        if (!$wedding->pin_enabled) {
            session(["client_pin_verified_{$slug}" => true]);
            return redirect()->route('client.gallery', $slug);
        }

        // Jika tamu sudah memasukkan PIN yang benar dalam sesi ini, langsung ke galeri
        if (session("client_pin_verified_{$slug}")) {
            return redirect()->route('client.gallery', $slug);
        }

        return view('client.pin', compact('wedding'));
    }

    /**
     * Alias publik untuk rute /{slug}
     */
    public function showPublic(string $slug)
    {
        return $this->showPin($slug);
    }

    /**
     * Verifikasi PIN yang dimasukkan tamu.
     * Mendukung pengiriman form standar maupun AJAX/fetch asinkron.
     */
    public function verifyPin(Request $request, string $slug)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        $request->validate([
            'pin' => 'required|string|max:20',
        ], [
            'pin.required' => 'Silakan masukkan 4 digit PIN galeri.',
        ]);

        $enteredPin = trim((string) $request->input('pin'));
        $correctPin = trim((string) ($wedding->pin ?? '1234'));

        // Jika PIN Protection tidak aktif atau PIN cocok
        if (!$wedding->pin_enabled || $enteredPin === $correctPin) {
            // Tandai sesi terverifikasi
            session(["client_pin_verified_{$slug}" => true]);

            // Catat log kunjungan tamu secara otomatis
            if (Schema::hasTable('client_visitors')) {
                $sessionToken = session("client_session_token_{$slug}", Str::random(40));
                session(["client_session_token_{$slug}" => $sessionToken]);

                ClientVisitor::create([
                    'wedding_id'       => $wedding->id,
                    'name'             => 'Tamu Undangan',
                    'email'            => null,
                    'ip_address'       => $request->ip(),
                    'session_token'    => $sessionToken,
                    'last_activity_at' => now(),
                ]);
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'PIN berhasil diverifikasi!',
                    'redirect' => route('client.gallery', $slug),
                ]);
            }

            return redirect()->route('client.gallery', $slug);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'PIN yang Anda masukkan salah. Silakan coba lagi.',
            ], 422);
        }

        return back()->withErrors(['pin' => 'PIN yang Anda masukkan salah. Silakan coba lagi.'])->withInput();
    }

    /**
     * Tampilan Galeri Foto Tamu Berdasarkan Tema Dinamis.
     * Hanya dapat diakses setelah memasukkan PIN yang benar (jika PIN aktif).
     */
    public function gallery(string $slug)
    {
        $wedding = Wedding::where('slug', $slug)
            ->with(['albums'])
            ->firstOrFail();

        // Pastikan tamu sudah lolos verifikasi PIN jika PIN diaktifkan
        if ($wedding->pin_enabled && !session("client_pin_verified_{$slug}")) {
            return redirect()->route('client.pin', $slug)
                ->withErrors(['pin' => 'Silakan masukkan PIN akses terlebih dahulu.']);
        }

        // Ambil foto aktif dari Google Drive yang tersinkronisasi
        $photos = Photo::where('wedding_id', $wedding->id)
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->latest('id')
            ->get();

        $albums = $wedding->albums()
            ->with(['photos' => function ($query) {
                $query->whereNull('deleted_at');
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('client.gallery', compact('wedding', 'photos', 'albums'));
    }

    /**
     * Pengunduhan Foto Asinkron (Async AJAX Download)
     * Bekerja tanpa me-reload halaman dan otomatis mencatat ke DownloadLog.
     */
    public function asyncDownload(Request $request, string $slug, string $fileId, GoogleDriveService $googleDrive)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        // Cek izin unduh: HANYA jika admin mencentang opsi "Guest Download"
        if (!$wedding->guest_download) {
            return response()->json([
                'success' => false,
                'message' => 'Izin unduh dinonaktifkan oleh administrator untuk galeri ini.',
            ], 403);
        }

        // Cek verifikasi PIN jika diaktifkan
        if ($wedding->pin_enabled && !session("client_pin_verified_{$slug}")) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi Anda tidak valid. Silakan masukkan PIN terlebih dahulu.',
            ], 401);
        }

        $photo = Photo::where('wedding_id', $wedding->id)
            ->where('drive_file_id', $fileId)
            ->first();

        $fileName = $photo ? $photo->nama_file : "wedding_photo_{$fileId}.jpg";
        $clientTime = $request->input('client_time') ?: now()->toIso8601String();

        // Catat unduhan ke tabel download_logs
        $googleDrive->recordDownload($wedding, $fileId, $fileName, $request->ip(), $clientTime);

        // Kembalikan URL langsung file Google Drive untuk diunduh asinkron via JS
        $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";

        return response()->json([
            'success'      => true,
            'message'      => 'Unduhan foto sedang diproses.',
            'file_name'    => $fileName,
            'file_id'      => $fileId,
            'download_url' => $downloadUrl,
        ]);
    }
}
