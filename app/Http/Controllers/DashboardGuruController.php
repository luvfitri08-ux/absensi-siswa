<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;

class DashboardGuruController extends Controller
{
    public function __invoke()
    {
        $daftarJadwal = Jadwal::with(['mapel', 'peserta.mapelSiswa.siswa'])
            ->where('nip', auth()->user()->username)
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('waktu_awal')
            ->get();

        return view('guru.dashboard', compact('daftarJadwal'));
    }
}