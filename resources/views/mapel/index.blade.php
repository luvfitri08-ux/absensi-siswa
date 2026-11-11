@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Mata Pelajaran</h4>
    <a href="{{ route('mapel.create') }}" class="btn btn-primary">+ Tambah</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px">No</th>
                    <th>Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th style="width: 160px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarMapel as $mapel)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $mapel->kode_mapel }}</td>
                        <td>{{ $mapel->nama_mapel }}</td>
                        <td>
                            <a href="{{ route('mapel.edit', $mapel) }}" class="btn btn-sm btn-warning">Ubah</a>
                            <form action="{{ route('mapel.destroy', $mapel) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection