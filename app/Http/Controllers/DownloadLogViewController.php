<?php

namespace App\Http\Controllers;

use App\Models\DownloadLog;

class DownloadLogViewController extends Controller
{
    /**
     * Menampilkan riwayat unduhan foto/album.
     */
    public function index()
    {
        $logs = DownloadLog::with('wedding')->latest('downloaded_at')->paginate(15);

        return view('logs.index', compact('logs'));
    }
}
