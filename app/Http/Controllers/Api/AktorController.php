<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aktor;
use Exception;

class AktorController extends Controller
{
    public function index()
    {
        try {
            // Load relasi 'films' agar data film tiap aktor ikut tampil
            $aktors = Aktor::with('films')->latest()->get();

            return response()->json([
                'status'  => true,
                'message' => 'Data aktor berhasil ditampilkan',
                'data'    => $aktors
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_aktor' => 'required|string|unique:aktors,nama_aktor',
                'gender'     => 'required|in:male,female', // Ditambahkan agar match dengan ENUM migration
                'umur'       => 'required|integer|min:0',  // Ditambahkan required
                'foto'       => 'nullable|string'
            ]);

            $aktor = new Aktor();
            $aktor->nama_aktor = $request->nama_aktor;
            $aktor->gender     = $request->gender;
            $aktor->umur       = $request->umur;
            $aktor->foto       = $request->foto;
            $aktor->save();

            return response()->json([
                'status'  => true,
                'message' => 'Data aktor berhasil disimpan',
                'data'    => $aktor
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
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
                    'status'  => false,
                    'message' => 'Data aktor tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'nama_aktor' => 'required|string|unique:aktors,nama_aktor,' . $id,
                'gender'     => 'required|in:male,female',
                'umur'       => 'required|integer|min:0',
                'foto'       => 'nullable|string'
            ]);

            $aktor->nama_aktor = $request->nama_aktor;
            $aktor->gender     = $request->gender;
            $aktor->umur       = $request->umur;
            $aktor->foto       = $request->foto;
            $aktor->save();

            return response()->json([
                'status'  => true,
                'message' => 'Data aktor berhasil diperbarui',
                'data'    => $aktor
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
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
                    'status'  => false,
                    'message' => 'Data aktor tidak ditemukan'
                ], 404);
            }

            // Lepas relasi pivot sebelum hapus
            $aktor->films()->detach();
            $aktor->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data aktor berhasil dihapus'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}