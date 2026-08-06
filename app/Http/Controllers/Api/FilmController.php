<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Film;
use Illuminate\Support\Str;
use Exception;

class FilmController extends Controller
{
    public function index()
    {
        try {
            $films = Film::latest()->get();
            return response()->json([
                'status' => true,
                'message' => 'Data film berhasil ditampilkan',
                'data'    => $films
            ], 200);
        } catch (Exception $e) {
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
                'judul' => 'required|string|unique:films,judul',
                'durasi' => 'required|string',
                'rating' => 'required|string',
                'deskripsi' => 'required|string',
                'tahun_rilis' => 'required|date',
                'poster' => 'string|nullable',
                'id_genre' => 'required|exists:genres,id',
                'sutradara' => 'required|string'
            ]);

            $film = new Film();
            $film->judul = $request->judul;
            $film->slug = Str::slug($request->judul) . Str::random(10);
            $film->durasi = $request->durasi;
            $film->rating = $request->rating;
            $film->deskripsi = $request->deskripsi;
            $film->tahun_rilis = $request->tahun_rilis;
            $film->poster = $request->poster;
            $film->id_genre = $request->id_genre;
            $film->sutradara = $request->sutradara;
            $film->save();

            return response()->json([
                'status' => true,
                'message' => 'Data film berhasil disimpan',
                'data'    => $film
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $film = Film::find($id);
            if (!$film) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data film tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'judul' => 'required|string|unique:films,judul,' . $id,
                'durasi' => 'required|string',
                'rating' => 'required|string',
                'deskripsi' => 'required|string',
                'tahun_rilis' => 'required|date',
                'poster' => 'string|nullable',
                'id_genre' => 'required|exists:genres,id',
                'sutradara' => 'required|string'
            ]);

            $film->judul = $request->judul;
            $film->slug = Str::slug($request->judul) . Str::random(10);
            $film->durasi = $request->durasi;
            $film->rating = $request->rating;
            $film->deskripsi = $request->deskripsi;
            $film->tahun_rilis = $request->tahun_rilis;
            $film->poster = $request->poster;
            $film->id_genre = $request->id_genre;
            $film->sutradara = $request->sutradara;
            $film->save();

            return response()->json([
                'status' => true,
                'message' => 'Data film berhasil diperbarui',
                'data'    => $film
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
            $film = Film::find($id);
            if (!$film) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data film tidak ditemukan'
                ], 404);
            }

            $film->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data film berhasil dihapus'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
