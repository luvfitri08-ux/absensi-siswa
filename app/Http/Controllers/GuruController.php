<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function index()
    {
        $daftarGuru = Guru::orderBy('nama')->get();

        return view('guru.index', compact('daftarGuru'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nip' => ['required', 'string', 'max:10', 'unique:guru,nip', 'unique:users,username'],
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {
            Guru::create($data);

            User::create([
                'name' => $data['nama'],
                'username' => $data['nip'],
                'password' => $data['nip'],
                'role' => 'guru',
            ]);
        });

        return redirect()->route('guru.index')
            ->with('success', 'Guru berhasil ditambahkan. Username dan password awal: NIP.');
    }

    public function edit(Guru $guru)
    {
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($guru, $data) {
            $guru->update($data);

            User::where('username', $guru->nip)
                ->where('role', 'guru')
                ->update(['name' => $data['nama']]);
        });

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil diubah.');
    }

    public function destroy(Guru $guru)
    {
        try {
            DB::transaction(function () use ($guru) {
                $guru->delete();

                User::where('username', $guru->nip)
                    ->where('role', 'guru')
                    ->delete();
            });
        } catch (QueryException $e) {
            return redirect()->route('guru.index')
                ->with('error', 'Guru tidak bisa dihapus karena masih dipakai di jadwal.');
        }

        return redirect()->route('guru.index')
            ->with('success', 'Guru berhasil dihapus.');
    }
}