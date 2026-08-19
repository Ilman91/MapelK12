<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PublicController extends Controller
{
    // 1. Get All Films
    public function films()
    {
        try {
            $films = DB::table('films')
                ->join('genres', 'films.id_genre', '=', 'genres.id')
                ->select(
                    'films.id',
                    'films.judul',
                    'films.slug',
                    'films.poster',
                    'films.tahun_rilis',
                    'films.durasi',
                    'films.sutradara',
                    'genres.nama_genre'
                )
                ->orderBy('films.id', 'desc')
                ->paginate(10);

            return response()->json([
                'status' => true,
                'message' => 'Data film berhasil diambil.',
                'data' => $films
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 2. Detail Film
    public function detailFilm($id)
    {
        try {
            $film = DB::table('films')
                ->join('genres', 'films.id_genre', '=', 'genres.id')
                ->select(
                    'films.*',
                    'genres.nama_genre'
                )
                ->where('films.id', $id)
                ->first();

            if (!$film) {
                return response()->json(['status' => false, 'message' => 'Film tidak ditemukan.'], 404);
            }

            // Ambil data aktor yang membintangi film ini lewat tabel pivot 'aktor_films'
            $aktors = DB::table('aktor_films')
                ->join('aktors', 'aktor_films.id_aktor', '=', 'aktors.id')
                ->select('aktors.id', 'aktors.nama_aktor', 'aktors.foto')
                ->where('aktor_films.id_film', $id)
                ->get();

            // Gabungkan data aktor ke dalam object film
            $film->aktors = $aktors;

            return response()->json([
                'status' => true,
                'message' => 'Detail film berhasil diambil.',
                'data' => $film
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 3. Get All Genres
    public function genres()
    {
        try {
            $genres = DB::table('genres')
                ->select('id', 'nama_genre', 'slug')
                ->orderBy('nama_genre', 'asc')
                ->paginate(10);

            return response()->json([
                'status' => true,
                'message' => 'Data genre berhasil diambil.',
                'data' => $genres
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 4. Get Films by Genre ID
    public function filmByGenre($id)
    {
        try {
            $films = DB::table('films')
                ->join('genres', 'films.id_genre', '=', 'genres.id')
                ->select(
                    'films.id',
                    'films.judul',
                    'films.poster',
                    'films.tahun_rilis',
                    'genres.nama_genre'
                )
                ->where('films.id_genre', $id)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Data film berdasarkan genre berhasil diambil.',
                'data' => $films
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 5. Get All Actors
    public function actors()
    {
        try {
            $actors = DB::table('aktors')
                ->select('id', 'nama_aktor', 'foto')
                ->orderBy('nama_aktor', 'asc')
                ->paginate(10);

            return response()->json([
                'status' => true,
                'message' => 'Data aktor berhasil diambil.',
                'data' => $actors
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 6. Get Films by Actor ID
    public function filmByActor($id)
    {
        try {
            $films = DB::table('films')
                ->join('aktor_films', 'films.id', '=', 'aktor_films.id_film')
                ->join('genres', 'films.id_genre', '=', 'genres.id')
                ->select(
                    'films.id',
                    'films.judul',
                    'films.poster',
                    'films.tahun_rilis',
                    'genres.nama_genre'
                )
                ->where('aktor_films.id_aktor', $id)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Data film berdasarkan aktor berhasil diambil.',
                'data' => $films
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 7. Search Films
    public function search(Request $request)
    {
        try {
            $keyword = $request->query('q');

            $films = DB::table('films')
                ->join('genres', 'films.id_genre', '=', 'genres.id')
                ->select(
                    'films.id',
                    'films.judul',
                    'films.poster',
                    'films.tahun_rilis',
                    'genres.nama_genre'
                )
                ->where('films.judul', 'LIKE', '%' . $keyword . '%')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Hasil pencarian film.',
                'data' => $films
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}