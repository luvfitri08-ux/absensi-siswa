<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\JadwalSiswa;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    private const HARI = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];

    public function index(Request $request)
    {
        $request->validate([
            'id_jadwal' => ['nullable', 'integer'],
            'tanggal' => ['nullable', 'date'],
        ]);

        $daftarJadwal = $this->queryJadwal()
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('waktu_awal')
            ->get();

        $tanggal = $request->input('tanggal', now()->toDateString());
        $jadwal = null;
        $peserta = collect();
        $statusTersimpan = collect();

        if ($request->filled('id_jadwal')) {
            $jadwal = $this->queryJadwal()->find($request->input('id_jadwal'));
        }

        if ($jadwal) {
            $peserta = JadwalSiswa::with('mapelSiswa.siswa')
                ->where('id_jadwal', $jadwal->id_jadwal)
                ->get()
                ->sortBy(fn ($p) => $p->mapelSiswa->siswa->nama);

            $statusTersimpan = Presensi::whereIn('id_jadwal_siswa', $peserta->pluck('id_jadwal_siswa'))
                ->whereDate('tanggal', $tanggal)
                ->pluck('status', 'id_jadwal_siswa');
        }

        return view('presensi.index', [
            'daftarJadwal' => $daftarJadwal,
            'jadwal' => $jadwal,
            'tanggal' => $tanggal,
            'peserta' => $peserta,
            'statusTersimpan' => $statusTersimpan,
            'ruteIndex' => $this->namaRute('index'),
            'ruteSimpan' => $this->namaRute('store'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_jadwal' => ['required', 'exists:jadwal,id_jadwal'],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'status' => ['required', 'array'],
            'status.*' => ['in:H,I,A'],
        ]);

        $jadwal = $this->queryJadwal()->findOrFail($data['id_jadwal']);

        $hariTanggal = self::HARI[Carbon::parse($data['tanggal'])->dayOfWeekIso];
        if ($hariTanggal !== $jadwal->hari) {
            return back()->withInput()->with(
                'error',
                "Tanggal tersebut jatuh pada hari {$hariTanggal}, sedangkan jadwal ini hari {$jadwal->hari}."
            );
        }

        $idValid = JadwalSiswa::where('id_jadwal', $jadwal->id_jadwal)
            ->pluck('id_jadwal_siswa')
            ->all();

        DB::transaction(function () use ($data, $jadwal, $idValid) {
            $waktu = $data['tanggal'] . ' ' . $jadwal->waktu_awal;

            foreach ($data['status'] as $idJadwalSiswa => $status) {
                if (! in_array($idJadwalSiswa, $idValid)) {
                    continue;
                }

                $presensi = Presensi::where('id_jadwal_siswa', $idJadwalSiswa)
                    ->whereDate('tanggal', $data['tanggal'])
                    ->first();

                if ($presensi) {
                    $presensi->update(['status' => $status]);
                } else {
                    Presensi::create([
                        'id_jadwal_siswa' => $idJadwalSiswa,
                        'tanggal' => $waktu,
                        'status' => $status,
                    ]);
                }
            }
        });

        return redirect()
            ->route($this->namaRute('index'), ['id_jadwal' => $jadwal->id_jadwal, 'tanggal' => $data['tanggal']])
            ->with('success', 'Presensi berhasil disimpan.');
    }

    private function queryJadwal()
    {
        $query = Jadwal::with(['mapel', 'guru']);

        if (auth()->user()->role === 'guru') {
            $query->where('nip', auth()->user()->username);
        }

        return $query;
    }

    private function namaRute(string $aksi): string
    {
        return (auth()->user()->role === 'guru' ? 'guru.presensi.' : 'presensi.') . $aksi;
    }
}