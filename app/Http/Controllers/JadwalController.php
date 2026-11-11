<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Mapel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JadwalController extends Controller
{
    private const KELAS = ['Kelas 1-A', 'Kelas 1-B', 'Kelas 2-A', 'Kelas 2-B', 'Kelas 3-A', 'Kelas 3-B'];

    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function index()
    {
        $daftarJadwal = Jadwal::with(['mapel', 'guru'])
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('waktu_awal')
            ->get();

        return view('jadwal.index', compact('daftarJadwal'));
    }

    public function create()
    {
        return view('jadwal.create', $this->dataForm());
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        if ($pesan = $this->cekBentrok($data)) {
            return back()->withInput()->withErrors(['waktu_awal' => $pesan]);
        }

        Jadwal::create($data);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        return view('jadwal.edit', $this->dataForm() + ['jadwal' => $jadwal]);
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $data = $this->validasi($request);

        if ($pesan = $this->cekBentrok($data, $jadwal->id_jadwal)) {
            return back()->withInput()->withErrors(['waktu_awal' => $pesan]);
        }

        $jadwal->update($data);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diubah.');
    }

    public function destroy(Jadwal $jadwal)
    {
        try {
            $jadwal->delete();
        } catch (QueryException $e) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Jadwal tidak bisa dihapus karena sudah dipakai di data siswa atau presensi.');
        }

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    private function dataForm(): array
    {
        return [
            'daftarMapel' => Mapel::orderBy('nama_mapel')->get(),
            'daftarGuru' => Guru::orderBy('nama')->get(),
            'daftarKelas' => self::KELAS,
            'daftarHari' => self::HARI,
        ];
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'kode_mapel' => ['required', 'exists:mapel,kode_mapel'],
            'nip' => ['required', 'exists:guru,nip'],
            'kode_kelas' => ['required', Rule::in(self::KELAS)],
            'hari' => ['required', Rule::in(self::HARI)],
            'waktu_awal' => ['required', 'date_format:H:i'],
            'waktu_akhir' => ['required', 'date_format:H:i', 'after:waktu_awal'],
        ]);
    }

    private function cekBentrok(array $data, ?int $abaikanId = null): ?string
    {
        $query = Jadwal::where('hari', $data['hari'])
            ->where('waktu_awal', '<', $data['waktu_akhir'])
            ->where('waktu_akhir', '>', $data['waktu_awal'])
            ->when($abaikanId, fn ($q) => $q->where('id_jadwal', '!=', $abaikanId));

        if ((clone $query)->where('nip', $data['nip'])->exists()) {
            return 'Guru ini sudah punya jadwal lain di jam tersebut.';
        }

        if ((clone $query)->where('kode_kelas', $data['kode_kelas'])->exists()) {
            return 'Kelas ini sudah punya jadwal lain di jam tersebut.';
        }

        return null;
    }
}