<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $query = Photo::with('wedding')->latest();

        if ($request->has('wedding_id')) {
            $query->where('wedding_id', $request->query('wedding_id'));
        }

        if ($request->boolean('trashed')) {
            $query->onlyTrashed();
        } elseif ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        $photos = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Data foto berhasil diambil',
            'data' => $photos,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wedding_id' => 'required|exists:weddings,id',
            'drive_file_id' => 'required|string|max:255|unique:photos,drive_file_id',
            'nama_file' => 'required|string|max:255',
            'mime_type' => 'nullable|string|max:255',
            'thumbnail_url' => 'nullable|url',
        ]);

        $photo = Photo::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil ditambahkan',
            'data' => $photo,
        ], 201);
    }

    public function show(Photo $photo)
    {
        $photo->load('wedding');

        return response()->json([
            'success' => true,
            'message' => 'Data foto berhasil diambil',
            'data' => $photo,
        ]);
    }

    public function update(Request $request, Photo $photo)
    {
        $validated = $request->validate([
            'wedding_id' => 'required|exists:weddings,id',
            'drive_file_id' => 'required|string|max:255|unique:photos,drive_file_id,' . $photo->id,
            'nama_file' => 'required|string|max:255',
            'mime_type' => 'nullable|string|max:255',
            'thumbnail_url' => 'nullable|url',
        ]);

        $photo->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diperbarui',
            'data' => $photo,
        ]);
    }

    public function destroy(Photo $photo)
    {
        $photo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dihapus',
        ]);
    }
}