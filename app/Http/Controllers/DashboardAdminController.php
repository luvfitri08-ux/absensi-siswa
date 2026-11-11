<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\Siswa;

class DashboardAdminController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'jumlahGuru' => Guru::count(),
            'jumlahSiswa' => Siswa::count(),
            'jumlahMapel' => Mapel::count(),
            'jumlahJadwal' => Jadwal::count(),
        ]);
    }
}