<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genre;
use Illuminate\Support\Str;
use Exception;

class GenreController extends Controller
{
    public function index()
    {
        try {
            $genres = Genre::latest()->get();
            return response()->json([
                'status' => true,
                'message' => 'Data genre berhasil ditampilkan',
                'data'    => $genres
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_genre' => 'required|string|unique:genres,nama_genre',
            ]);
            $genre = new Genre();
            $genre->nama_genre = $request->nama_genre;
            $genre->slug = Str::slug($request->nama_genre) . Str::random(10);
            $genre->save();

            return response()->json([
                'status' => true,
                'message' => 'Data genre berhasil disimpan',
                'data'    => $genre
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $genre = Genre::find($id);
            if (!$genre) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data genre tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'nama_genre' => 'required|string|unique:genres,nama_genre,' . $id,
            ]);

            $genre->nama_genre = $request->nama_genre;
            $genre->slug = Str::slug($request->nama_genre) . Str::random(10);
            $genre->save();

            return response()->json([
                'status' => true,
                'message' => 'Data genre berhasil diperbarui',
                'data'    => $genre
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $genre = Genre::find($id);

            if (!$genre) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data genre tidak ditemukan'
                ], 404);
            }

            $genre->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data genre berhasil dihapus'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}