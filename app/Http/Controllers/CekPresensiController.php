<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use Illuminate\Http\Request;

class CekPresensiController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'nisn' => ['nullable', 'string', 'max:10'],
        ]);

        $nisn = trim((string) $request->input('nisn'));
        $siswa = null;
        $tidakDitemukan = false;
        $riwayat = collect();

        if ($nisn !== '') {
            $siswa = Siswa::find($nisn);

            if ($siswa) {
                $riwayat = Presensi::with('jadwalSiswa.jadwal.mapel')
                    ->whereHas('jadwalSiswa.mapelSiswa', fn ($q) => $q->where('nisn', $siswa->nisn))
                    ->orderByDesc('tanggal')
                    ->get();
            } else {
                $tidakDitemukan = true;
            }
        }

        $total = $riwayat->count();
        $rekap = [
            'H' => $riwayat->where('status', 'H')->count(),
            'I' => $riwayat->where('status', 'I')->count(),
            'A' => $riwayat->where('status', 'A')->count(),
        ];
        $persen = $total > 0 ? round($rekap['H'] / $total * 100) : 0;

        return view('cek-presensi', compact('nisn', 'siswa', 'tidakDitemukan', 'riwayat', 'rekap', 'persen'));
    }
}