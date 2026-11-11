<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoSeeder extends Seeder
{
    private array $siswaPerKelas = ['Kelas 1-A' => [], 'Kelas 1-B' => []];

    private array $jadwalSiswa = [];

    public function run(): void
    {
        if (! $this->command->confirm('Seeder ini akan MENGHAPUS semua data guru, siswa, mapel, jadwal, dan presensi, lalu mengisi data contoh. Akun admin tidak dihapus. Lanjutkan?')) {
            $this->command->info('Dibatalkan.');

            return;
        }

        $this->kosongkan();
        $this->isiMapel();
        $this->isiGuru();
        $this->isiSiswa();
        $this->isiJadwal();
        $this->isiPresensi();

        $this->command->info('Data demo berhasil dibuat.');
    }

    private function kosongkan(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach (['presensi', 'jadwal_siswa', 'mapel_siswa', 'jadwal', 'siswa', 'guru', 'mapel'] as $tabel) {
            DB::table($tabel)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        User::whereIn('role', ['guru', 'siswa'])->delete();
    }

    private function isiMapel(): void
    {
        DB::table('mapel')->insert([
            ['kode_mapel' => 'MTK', 'nama_mapel' => 'Matematika'],
            ['kode_mapel' => 'BIN', 'nama_mapel' => 'Bahasa Indonesia'],
            ['kode_mapel' => 'BIG', 'nama_mapel' => 'Bahasa Inggris'],
            ['kode_mapel' => 'IPA', 'nama_mapel' => 'Ilmu Pengetahuan Alam'],
            ['kode_mapel' => 'IPS', 'nama_mapel' => 'Ilmu Pengetahuan Sosial'],
            ['kode_mapel' => 'PAI', 'nama_mapel' => 'Pendidikan Agama Islam'],
        ]);
    }

    private function isiGuru(): void
    {
        $daftar = [
            ['1980010101', 'Ahmad Fauzi, S.Pd.', 'L', 'Surabaya', '1980-01-01', 'Jl. Melati No. 1'],
            ['1985020202', 'Siti Aminah, S.Pd.', 'P', 'Malang', '1985-02-02', 'Jl. Mawar No. 2'],
            ['1990030303', 'Budi Santoso, M.Pd.', 'L', 'Jember', '1990-03-03', 'Jl. Kenanga No. 3'],
            ['1988040404', 'Dewi Lestari, S.Si.', 'P', 'Banyuwangi', '1988-04-04', 'Jl. Anggrek No. 4'],
        ];

        foreach ($daftar as [$nip, $nama, $jk, $tempat, $tanggal, $alamat]) {
            DB::table('guru')->insert([
                'nip' => $nip,
                'nama' => $nama,
                'jenis_kelamin' => $jk,
                'tempat_lahir' => $tempat,
                'tanggal_lahir' => $tanggal,
                'alamat' => $alamat,
            ]);

            User::create(['name' => $nama, 'username' => $nip, 'password' => $nip, 'role' => 'guru']);
        }
    }

    private function isiSiswa(): void
    {
        $daftar = [
            ['2024000001', 'Andi Prasetyo', 'L', '2013-01-15', 'Hendra Prasetyo', 'Rina Wati'],
            ['2024000002', 'Bunga Cahyani', 'P', '2013-02-20', 'Joko Susilo', 'Sri Rahayu'],
            ['2024000003', 'Candra Wijaya', 'L', '2013-03-11', 'Agus Wijaya', 'Lestari'],
            ['2024000004', 'Dina Maharani', 'P', '2013-04-05', 'Slamet Riyadi', 'Fatimah'],
            ['2024000005', 'Eko Saputra', 'L', '2013-05-23', 'Bambang Saputra', 'Nur Aini'],
            ['2024000006', 'Fitri Handayani', 'P', '2013-06-30', 'Darmawan', 'Yuliana'],
            ['2024000007', 'Galih Ramadhan', 'L', '2013-07-14', 'Sugeng Ramadhan', 'Endang'],
            ['2024000008', 'Hana Salsabila', 'P', '2013-08-09', 'Mulyadi', 'Khoirunnisa'],
            ['2024000009', 'Irfan Maulana', 'L', '2013-09-27', 'Rudi Maulana', 'Siti Nurhayati'],
            ['2024000010', 'Jihan Aulia', 'P', '2013-10-18', 'Teguh Santoso', 'Wahyuni'],
            ['2024000011', 'Kevin Ardiansyah', 'L', '2013-11-02', 'Heri Ardiansyah', 'Dwi Astuti'],
            ['2024000012', 'Laila Zahra', 'P', '2013-12-25', 'Imam Hidayat', 'Maryam'],
        ];

        foreach ($daftar as $i => [$nisn, $nama, $jk, $lahir, $bapak, $ibu]) {
            DB::table('siswa')->insert([
                'nisn' => $nisn,
                'nama' => $nama,
                'jenis_kelamin' => $jk,
                'tempat_lahir' => $lahir,
                'bapak' => $bapak,
                'ibu' => $ibu,
                'alamat' => 'Jl. Pelajar No. ' . ($i + 1),
            ]);

            User::create(['name' => $nama, 'username' => $nisn, 'password' => $nisn, 'role' => 'siswa']);

            $kelas = $i < 6 ? 'Kelas 1-A' : 'Kelas 1-B';
            $this->siswaPerKelas[$kelas][] = $nisn;
        }
    }

    private function isiJadwal(): void
    {
        $daftar = [
            ['Kelas 1-A', 'Senin', '07:30', '09:00', 'MTK', '1980010101'],
            ['Kelas 1-A', 'Senin', '09:15', '10:45', 'BIN', '1985020202'],
            ['Kelas 1-A', 'Selasa', '07:30', '09:00', 'IPA', '1990030303'],
            ['Kelas 1-A', 'Rabu', '07:30', '09:00', 'PAI', '1988040404'],
            ['Kelas 1-B', 'Senin', '07:30', '09:00', 'BIN', '1985020202'],
            ['Kelas 1-B', 'Senin', '09:15', '10:45', 'MTK', '1980010101'],
            ['Kelas 1-B', 'Selasa', '07:30', '09:00', 'BIG', '1988040404'],
            ['Kelas 1-B', 'Rabu', '07:30', '09:00', 'IPS', '1990030303'],
        ];

        foreach ($daftar as [$kelas, $hari, $mulai, $selesai, $mapel, $nip]) {
            $idJadwal = DB::table('jadwal')->insertGetId([
                'kode_mapel' => $mapel,
                'nip' => $nip,
                'kode_kelas' => $kelas,
                'waktu_awal' => $mulai,
                'waktu_akhir' => $selesai,
                'hari' => $hari,
            ]);

            foreach ($this->siswaPerKelas[$kelas] as $nisn) {
                $idMapelSiswa = DB::table('mapel_siswa')->insertGetId([
                    'nisn' => $nisn,
                    'kode_mapel' => $mapel,
                    'kode_kelas' => $kelas,
                ]);

                $idJadwalSiswa = DB::table('jadwal_siswa')->insertGetId([
                    'id_mapel_siswa' => $idMapelSiswa,
                    'id_jadwal' => $idJadwal,
                ]);

                $this->jadwalSiswa[] = ['id' => $idJadwalSiswa, 'hari' => $hari, 'mulai' => $mulai];
            }
        }
    }

    private function isiPresensi(): void
    {
        mt_srand(2026);

        $nomorHari = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3];

        foreach ($this->jadwalSiswa as $js) {
            $tanggal = Carbon::now()->startOfDay();

            while ($tanggal->dayOfWeekIso !== $nomorHari[$js['hari']]) {
                $tanggal->subDay();
            }

            for ($minggu = 0; $minggu < 3; $minggu++) {
                $acak = mt_rand(1, 100);
                $status = $acak <= 80 ? 'H' : ($acak <= 90 ? 'I' : 'A');

                DB::table('presensi')->insert([
                    'id_jadwal_siswa' => $js['id'],
                    'tanggal' => $tanggal->copy()->subWeeks($minggu)->format('Y-m-d') . ' ' . $js['mulai'] . ':00',
                    'status' => $status,
                ]);
            }
        }
    }
}