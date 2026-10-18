<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DownloadLog;
use Illuminate\Http\Request;

class DownloadLogController extends Controller
{
    /**
     * Menampilkan daftar download logs.
     */
    public function index(Request $request)
    {
        if ($request->acceptsHtml() && !$request->expectsJson() && !$request->has('json')) {
            return redirect()->route('logs.index');
        }

        $query = DownloadLog::with('wedding')->latest('downloaded_at');

        if ($request->has('wedding_id')) {
            $query->where('wedding_id', $request->query('wedding_id'));
        }

        $logs = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Data log download berhasil diambil',
            'data' => $logs,
        ]);
    }

    /**
     * Mencatat log unduhan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wedding_id' => 'required|exists:weddings,id',
            'drive_file_id' => 'required|string|max:255',
            'file_name' => 'required|string|max:255',
            'ip_address' => 'nullable|string|max:45',
        ]);

        $log = DownloadLog::create([
            'wedding_id' => $validated['wedding_id'],
            'drive_file_id' => $validated['drive_file_id'],
            'file_name' => $validated['file_name'],
            'ip_address' => $validated['ip_address'] ?? $request->ip(),
            'downloaded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Log download berhasil dicatat',
            'data' => $log,
        ], 201);
    }

    /**
     * Menampilkan satu log download.
     */
    public function show(DownloadLog $downloadLog)
    {
        $downloadLog->load('wedding');

        return response()->json([
            'success' => true,
            'message' => 'Data log download berhasil diambil',
            'data' => $downloadLog,
        ]);
    }
}
