<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Menampilkan daftar album (bisa difilter berdasarkan wedding_id).
     */
    public function index(Request $request)
    {
        if ($request->acceptsHtml() && !$request->expectsJson() && !$request->has('json')) {
            return redirect()->route('albums.index');
        }

        $query = Album::with('wedding')->orderBy('sort_order', 'asc');

        if ($request->has('wedding_id')) {
            $query->where('wedding_id', $request->query('wedding_id'));
        }

        $albums = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Data album berhasil diambil',
            'data' => $albums,
        ]);
    }

    /**
     * Menyimpan album baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wedding_id' => 'required|exists:weddings,id',
            'album_name' => 'required|string|max:255',
            'drive_subfolder_id' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $album = Album::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Album berhasil dibuat',
            'data' => $album,
        ], 201);
    }

    /**
     * Menampilkan detail satu album.
     */
    public function show(Album $album)
    {
        $album->load('wedding');

        return response()->json([
            'success' => true,
            'message' => 'Data album berhasil diambil',
            'data' => $album,
        ]);
    }

    /**
     * Mengubah data album.
     */
    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'wedding_id' => 'sometimes|required|exists:weddings,id',
            'album_name' => 'sometimes|required|string|max:255',
            'drive_subfolder_id' => 'sometimes|required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $album->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Album berhasil diperbarui',
            'data' => $album,
        ]);
    }

    /**
     * Menghapus album.
     */
    public function destroy(Album $album)
    {
        $album->delete();

        return response()->json([
            'success' => true,
            'message' => 'Album berhasil dihapus',
        ]);
    }
}
