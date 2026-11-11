@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Siswa</h4>
    <a href="{{ route('siswa.create') }}" class="btn btn-primary">+ Tambah</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px">No</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>JK</th>
                    <th>Tanggal Lahir</th>
                    <th>Ayah</th>
                    <th>Ibu</th>
                    <th style="width: 160px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarSiswa as $siswa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $siswa->nisn }}</td>
                        <td>{{ $siswa->nama }}</td>
                        <td>{{ $siswa->jenis_kelamin }}</td>
                        <td>{{ $siswa->tempat_lahir }}</td>
                        <td>{{ $siswa->bapak }}</td>
                        <td>{{ $siswa->ibu }}</td>
                        <td>
                            <a href="{{ route('siswa.edit', $siswa) }}" class="btn btn-sm btn-warning">Ubah</a>
                            <form action="{{ route('siswa.destroy', $siswa) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus siswa ini beserta akun loginnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection