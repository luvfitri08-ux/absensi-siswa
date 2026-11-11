<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class CrudDanPresensiTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Uji',
            'username' => 'admin_uji',
            'password' => 'rahasia123',
            'role' => 'admin',
        ]);
    }

    private function buatMapelDanGuru(): void
    {
        Mapel::create(['kode_mapel' => 'TST1', 'nama_mapel' => 'Mapel Uji']);

        Guru::create([
            'nip' => '9999999991',
            'nama' => 'Guru Uji',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Kota Uji',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Alamat Uji',
        ]);
    }

    private function buatJadwalSenin(): Jadwal
    {
        return Jadwal::create([
            'kode_mapel' => 'TST1',
            'nip' => '9999999991',
            'kode_kelas' => 'Kelas 1-A',
            'hari' => 'Senin',
            'waktu_awal' => '07:00',
            'waktu_akhir' => '08:00',
        ]);
    }

    public function test_admin_dapat_menambah_mapel(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/mapel', ['kode_mapel' => 'TST1', 'nama_mapel' => 'Mapel Uji'])
            ->assertRedirect('/admin/mapel');

        $this->assertDatabaseHas('mapel', ['kode_mapel' => 'TST1', 'nama_mapel' => 'Mapel Uji']);
    }

    public function test_kode_mapel_yang_sama_ditolak(): void
    {
        Mapel::create(['kode_mapel' => 'TST1', 'nama_mapel' => 'Mapel Uji']);

        $this->actingAs($this->admin)
            ->post('/admin/mapel', ['kode_mapel' => 'TST1', 'nama_mapel' => 'Lain'])
            ->assertSessionHasErrors('kode_mapel');
    }

    public function test_menambah_guru_otomatis_membuat_akun_login(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/guru', [
                'nip' => '9999999992',
                'nama' => 'Guru Baru',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Kota Uji',
                'tanggal_lahir' => '1991-02-02',
                'alamat' => 'Alamat Uji',
            ])
            ->assertRedirect('/admin/guru');

        $this->assertDatabaseHas('guru', ['nip' => '9999999992']);
        $this->assertDatabaseHas('users', ['username' => '9999999992', 'role' => 'guru']);
    }

    public function test_jadwal_bentrok_ditolak(): void
    {
        $this->buatMapelDanGuru();

        $data = [
            'kode_mapel' => 'TST1',
            'nip' => '9999999991',
            'kode_kelas' => 'Kelas 1-A',
            'hari' => 'Senin',
            'waktu_awal' => '07:00',
            'waktu_akhir' => '08:30',
        ];

        $this->actingAs($this->admin)->post('/admin/jadwal', $data)->assertSessionHasNoErrors();

        $bentrok = array_merge($data, [
            'kode_kelas' => 'Kelas 1-B',
            'waktu_awal' => '08:00',
            'waktu_akhir' => '09:00',
        ]);

        $this->actingAs($this->admin)->post('/admin/jadwal', $bentrok)->assertSessionHasErrors('waktu_awal');
    }

    public function test_presensi_di_hari_yang_salah_ditolak(): void
    {
        $this->buatMapelDanGuru();
        $jadwal = $this->buatJadwalSenin();

        $selasa = Carbon::now()->previous(Carbon::TUESDAY)->format('Y-m-d');

        $this->actingAs($this->admin)
            ->post('/admin/presensi', [
                'id_jadwal' => $jadwal->id_jadwal,
                'tanggal' => $selasa,
                'status' => [1 => 'H'],
            ])
            ->assertSessionHas('error');
    }

    public function test_guru_tidak_bisa_mengisi_presensi_jadwal_guru_lain(): void
    {
        $this->buatMapelDanGuru();
        $jadwal = $this->buatJadwalSenin();

        $guruLain = User::create([
            'name' => 'Guru Lain',
            'username' => '9999999993',
            'password' => 'rahasia123',
            'role' => 'guru',
        ]);

        $senin = Carbon::now()->previous(Carbon::MONDAY)->format('Y-m-d');

        $this->actingAs($guruLain)
            ->post('/guru/presensi', [
                'id_jadwal' => $jadwal->id_jadwal,
                'tanggal' => $senin,
                'status' => [1 => 'H'],
            ])
            ->assertNotFound();
    }
}