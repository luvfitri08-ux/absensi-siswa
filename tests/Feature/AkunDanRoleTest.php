<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AkunDanRoleTest extends TestCase
{
    private function buatUser(string $role): User
    {
        return User::create([
            'name' => 'Pengguna ' . $role,
            'username' => 'uji_' . $role,
            'password' => 'rahasia123',
            'role' => $role,
        ]);
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_login_berhasil_dengan_username_dan_password_benar(): void
    {
        $this->buatUser('admin');

        $this->post('/login', ['username' => 'uji_admin', 'password' => 'rahasia123'])
            ->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        $this->buatUser('admin');

        $this->post('/login', ['username' => 'uji_admin', 'password' => 'salah'])
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_guru_tidak_boleh_membuka_halaman_admin(): void
    {
        $this->actingAs($this->buatUser('guru'))
            ->get('/admin/siswa')
            ->assertForbidden();
    }

    public function test_siswa_tidak_boleh_membuka_halaman_guru(): void
    {
        $this->actingAs($this->buatUser('siswa'))
            ->get('/guru/dashboard')
            ->assertForbidden();
    }

    public function test_admin_tidak_boleh_membuka_halaman_siswa(): void
    {
        $this->actingAs($this->buatUser('admin'))
            ->get('/siswa/riwayat')
            ->assertForbidden();
    }

    public function test_logout_mengeluarkan_pengguna(): void
    {
        $this->actingAs($this->buatUser('admin'))
            ->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_halaman_orang_tua_bisa_dibuka_tanpa_login(): void
    {
        $this->get('/cek-presensi')
            ->assertOk()
            ->assertSee('Cek Presensi Siswa');
    }
}