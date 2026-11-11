<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index()
    {
        $daftarSiswa = Siswa::orderBy('nama')->get();

        return view('siswa.index', compact('daftarSiswa'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nisn' => ['required', 'string', 'max:10', 'unique:siswa,nisn', 'unique:users,username'],
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'date'],
            'bapak' => ['required', 'string', 'max:100'],
            'ibu' => ['required', 'string', 'max:100'],
            'alamat' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            Siswa::create($data);

            User::create([
                'name' => $data['nama'],
                'username' => $data['nisn'],
                'password' => $data['nisn'],
                'role' => 'siswa',
            ]);
        });

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan. Username dan password awal: NISN.');
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'date'],
            'bapak' => ['required', 'string', 'max:100'],
            'ibu' => ['required', 'string', 'max:100'],
            'alamat' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($siswa, $data) {
            $siswa->update($data);

            User::where('username', $siswa->nisn)
                ->where('role', 'siswa')
                ->update(['name' => $data['nama']]);
        });

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diubah.');
    }

    public function destroy(Siswa $siswa)
    {
        try {
            DB::transaction(function () use ($siswa) {
                $siswa->delete();

                User::where('username', $siswa->nisn)
                    ->where('role', 'siswa')
                    ->delete();
            });
        } catch (QueryException $e) {
            return redirect()->route('siswa.index')
                ->with('error', 'Siswa tidak bisa dihapus karena masih terdaftar di mata pelajaran atau jadwal.');
        }

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil dihapus.');
    }
}