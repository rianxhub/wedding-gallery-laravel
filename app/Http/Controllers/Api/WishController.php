<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wish;
use Illuminate\Http\Request;

class WishController extends Controller
{
    /**
     * Menampilkan semua ucapan (wishes).
     * Mendukung filter berdasarkan wedding_id (?wedding_id=...).
     */
    public function index(Request $request)
    {
        $query = Wish::with('wedding')->latest();

        if ($request->has('wedding_id')) {
            $query->where('wedding_id', $request->query('wedding_id'));
        }

        $wishes = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Data ucapan berhasil diambil',
            'data' => $wishes,
        ]);
    }

    /**
     * Menyimpan ucapan baru dari tamu undangan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wedding_id' => 'required|exists:weddings,id',
            'nama_tamu' => 'required|string|max:255',
            'ucapan' => 'required|string',
            'kehadiran' => 'required|in:hadir,tidak_hadir,ragu_ragu',
        ]);

        $wish = Wish::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ucapan berhasil dikirim',
            'data' => $wish,
        ], 201);
    }

    /**
     * Menampilkan detail satu ucapan.
     */
    public function show(Wish $wish)
    {
        $wish->load('wedding');

        return response()->json([
            'success' => true,
            'message' => 'Data ucapan berhasil diambil',
            'data' => $wish,
        ]);
    }

    /**
     * Mengubah data ucapan.
     */
    public function update(Request $request, Wish $wish)
    {
        $validated = $request->validate([
            'wedding_id' => 'sometimes|exists:weddings,id',
            'nama_tamu' => 'sometimes|required|string|max:255',
            'ucapan' => 'sometimes|required|string',
            'kehadiran' => 'sometimes|required|in:hadir,tidak_hadir,ragu_ragu',
        ]);

        $wish->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ucapan berhasil diperbarui',
            'data' => $wish,
        ]);
    }

    /**
     * Menghapus ucapan.
     */
    public function destroy(Wish $wish)
    {
        $wish->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ucapan berhasil dihapus',
        ]);
    }
}
