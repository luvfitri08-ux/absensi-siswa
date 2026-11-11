@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Guru</h4>
    <a href="{{ route('guru.create') }}" class="btn btn-primary">+ Tambah</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px">No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>JK</th>
                    <th>Tempat, Tanggal Lahir</th>
                    <th style="width: 160px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarGuru as $guru)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $guru->nip }}</td>
                        <td>{{ $guru->nama }}</td>
                        <td>{{ $guru->jenis_kelamin }}</td>
                        <td>{{ $guru->tempat_lahir }}, {{ $guru->tanggal_lahir }}</td>
                        <td>
                            <a href="{{ route('guru.edit', $guru) }}" class="btn btn-sm btn-warning">Ubah</a>
                            <form action="{{ route('guru.destroy', $guru) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus guru ini beserta akun loginnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection