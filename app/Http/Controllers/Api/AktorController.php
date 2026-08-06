<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aktor;
use Illuminate\Support\Str;
use Exception;

class AktorController extends Controller
{
    public function index()
    {
        try {
            $aktors = Aktor::latest()->get();
            return response()->json([
                'status' => true,
                'message' => 'Data aktor berhasil ditampilkan',
                'data'    => $aktors
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
                'nama_aktor' => 'required|string|unique:aktors,nama_aktor',
                'umur' => 'integer|min:0',
                'foto' => 'string|nullable'
            ]);

            $aktor = new Aktor();
            $aktor->nama_aktor = $request->nama_aktor;
            $aktor->gender = $request->gender;
            $aktor->umur = $request->umur;
            $aktor->foto = $request->foto;
            $aktor->save();

            return response()->json([
                'status' => true,
                'message' => 'Data aktor berhasil disimpan',
                'data'    => $aktor
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
            $aktor = Aktor::find($id);
            if (!$aktor) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data aktor tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'nama_aktor' => 'required|string|unique:aktors,nama_aktor,' . $id,
                'umur' => 'integer|min:0',
                'foto' => 'string|nullable'
            ]);

            $aktor->nama_aktor = $request->nama_aktor;
            $aktor->gender = $request->gender;
            $aktor->umur = $request->umur;
            $aktor->foto = $request->foto;
            $aktor->save();

            return response()->json([
                'status' => true,
                'message' => 'Data aktor berhasil diperbarui',
                'data'    => $aktor
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
            $aktor = Aktor::find($id);
            if (!$aktor) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data aktor tidak ditemukan'
                ], 404);
            }

            $aktor->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data aktor berhasil dihapus'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
