<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Illuminate\Http\Request;

class WeddingController extends Controller
{
    /**
     * Menampilkan semua wedding beserta data user dan albumnya.
     */
    public function index(Request $request)
    {
        if ($request->acceptsHtml() && !$request->expectsJson() && !$request->has('json')) {
            return redirect()->route('weddings.index');
        }

        $weddings = Wedding::with(['user', 'albums'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data wedding berhasil diambil',
            'data' => $weddings,
        ]);
    }

    /**
     * Menyimpan wedding baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'couple_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:weddings,slug',
            'wedding_date' => 'nullable|date',
            'drive_folder_id' => 'required|string|max:255',
            'cover_drive_file_id' => 'nullable|string|max:255',
            'quote' => 'nullable|string',
            'theme' => 'nullable|string|max:100',
        ]);

        $wedding = Wedding::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Wedding berhasil dibuat',
            'data' => $wedding->load(['user', 'albums']),
        ], 201);
    }

    /**
     * Menampilkan satu data wedding secara detail.
     */
    public function show(Wedding $wedding)
    {
        $wedding->load(['user', 'albums', 'downloadLogs']);

        return response()->json([
            'success' => true,
            'message' => 'Data wedding berhasil diambil',
            'data' => $wedding,
        ]);
    }

    /**
     * Mengubah data wedding.
     */
    public function update(Request $request, Wedding $wedding)
    {
        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'couple_name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:weddings,slug,' . $wedding->id,
            'wedding_date' => 'nullable|date',
            'drive_folder_id' => 'sometimes|required|string|max:255',
            'cover_drive_file_id' => 'nullable|string|max:255',
            'quote' => 'nullable|string',
            'theme' => 'nullable|string|max:100',
        ]);

        $wedding->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Wedding berhasil diperbarui',
            'data' => $wedding->load(['user', 'albums']),
        ]);
    }

    /**
     * Menghapus wedding.
     */
    public function destroy(Wedding $wedding)
    {
        $wedding->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wedding berhasil dihapus',
        ]);
    }
}