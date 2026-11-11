<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\JadwalSiswa;
use App\Models\MapelSiswa;
use App\Models\Siswa;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesertaJadwalController extends Controller
{
    public function index(Jadwal $jadwal)
    {
        $jadwal->load(['mapel', 'guru']);

        $peserta = JadwalSiswa::with('mapelSiswa.siswa')
            ->where('id_jadwal', $jadwal->id_jadwal)
            ->get()
            ->sortBy(fn ($p) => $p->mapelSiswa->siswa->nama);

        $siswaTersedia = Siswa::whereNotIn('nisn', $peserta->map(fn ($p) => $p->mapelSiswa->nisn))
            ->orderBy('nama')
            ->get();

        return view('jadwal.peserta', compact('jadwal', 'peserta', 'siswaTersedia'));
    }

    public function store(Request $request, Jadwal $jadwal)
    {
        $data = $request->validate([
            'nisn' => ['required', 'array', 'min:1'],
            'nisn.*' => ['exists:siswa,nisn'],
        ], [
            'nisn.required' => 'Pilih minimal satu siswa.',
            'nisn.min' => 'Pilih minimal satu siswa.',
        ]);

        DB::transaction(function () use ($data, $jadwal) {
            foreach ($data['nisn'] as $nisn) {
                $mapelSiswa = MapelSiswa::firstOrCreate([
                    'nisn' => $nisn,
                    'kode_mapel' => $jadwal->kode_mapel,
                    'kode_kelas' => $jadwal->kode_kelas,
                ]);

                JadwalSiswa::firstOrCreate([
                    'id_mapel_siswa' => $mapelSiswa->id_mapel_siswa,
                    'id_jadwal' => $jadwal->id_jadwal,
                ]);
            }
        });

        return back()->with('success', count($data['nisn']) . ' siswa berhasil didaftarkan ke jadwal ini.');
    }

    public function destroy(Jadwal $jadwal, JadwalSiswa $jadwalSiswa)
    {
        abort_unless($jadwalSiswa->id_jadwal == $jadwal->id_jadwal, 404);

        try {
            $jadwalSiswa->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Siswa tidak bisa dikeluarkan karena sudah punya catatan presensi.');
        }

        return back()->with('success', 'Siswa dikeluarkan dari jadwal ini.');
    }
}