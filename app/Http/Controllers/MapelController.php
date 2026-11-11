<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $daftarMapel = Mapel::orderBy('kode_mapel')->get();

        return view('mapel.index', compact('daftarMapel'));
    }

    public function create()
    {
        return view('mapel.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:10', 'unique:mapel,kode_mapel'],
            'nama_mapel' => ['required', 'string', 'max:100'],
        ]);

        Mapel::create($data);

        return redirect()->route('mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        return view('mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $data = $request->validate([
            'nama_mapel' => ['required', 'string', 'max:100'],
        ]);

        $mapel->update($data);

        return redirect()->route('mapel.index')
            ->with('success', 'Mata pelajaran berhasil diubah.');
    }

    public function destroy(Mapel $mapel)
    {
        try {
            $mapel->delete();
        } catch (QueryException $e) {
            return redirect()->route('mapel.index')
                ->with('error', 'Mata pelajaran tidak bisa dihapus karena masih dipakai di jadwal atau data siswa.');
        }

        return redirect()->route('mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}