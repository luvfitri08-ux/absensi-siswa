<?php

namespace App\Http\Controllers;

use App\Models\JadwalSiswa;
use App\Models\Presensi;
use App\Models\Siswa;

class AreaSiswaController extends Controller
{
    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function dashboard()
    {
        $siswa = Siswa::findOrFail(auth()->user()->username);

        $jadwalSiswa = JadwalSiswa::with(['jadwal.mapel', 'jadwal.guru', 'mapelSiswa'])
            ->whereHas('mapelSiswa', fn ($q) => $q->where('nisn', $siswa->nisn))
            ->get()
            ->sortBy(fn ($js) => [array_search($js->jadwal->hari, self::HARI), $js->jadwal->waktu_awal])
            ->values();

        $daftarMapel = $jadwalSiswa
            ->map(fn ($js) => $js->jadwal->mapel)
            ->filter()
            ->unique('kode_mapel')
            ->values();

        $kelas = $jadwalSiswa
            ->map(fn ($js) => $js->mapelSiswa->kode_kelas)
            ->unique()
            ->implode(', ');

        return view('siswa.dashboard', compact('siswa', 'jadwalSiswa', 'daftarMapel', 'kelas'));
    }

    public function riwayat()
    {
        $siswa = Siswa::findOrFail(auth()->user()->username);

        $riwayat = Presensi::with(['jadwalSiswa.jadwal.mapel', 'jadwalSiswa.jadwal.guru'])
            ->whereHas('jadwalSiswa.mapelSiswa', fn ($q) => $q->where('nisn', $siswa->nisn))
            ->orderByDesc('tanggal')
            ->get();

        $total = $riwayat->count();
        $rekap = [
            'H' => $riwayat->where('status', 'H')->count(),
            'I' => $riwayat->where('status', 'I')->count(),
            'A' => $riwayat->where('status', 'A')->count(),
        ];
        $persen = $total > 0 ? round($rekap['H'] / $total * 100) : 0;

        return view('siswa.riwayat', compact('siswa', 'riwayat', 'rekap', 'total', 'persen'));
    }
}