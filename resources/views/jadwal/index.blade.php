@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Jadwal Pelajaran</h4>
    <a href="{{ route('jadwal.create') }}" class="btn btn-primary">+ Tambah</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px">No</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Kelas</th>
                    <th style="width: 240px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarJadwal as $jadwal)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $jadwal->hari }}</td>
                        <td>{{ substr($jadwal->waktu_awal, 0, 5) }} - {{ substr($jadwal->waktu_akhir, 0, 5) }}</td>
                        <td>{{ $jadwal->mapel->nama_mapel ?? '-' }}</td>
                        <td>{{ $jadwal->guru->nama ?? '-' }}</td>
                        <td>{{ $jadwal->kode_kelas }}</td>
                        <td>
                            <a href="{{ route('jadwal.peserta', $jadwal) }}" class="btn btn-sm btn-info">Peserta</a>
                            <a href="{{ route('jadwal.edit', $jadwal) }}" class="btn btn-sm btn-warning">Ubah</a>
                            <form action="{{ route('jadwal.destroy', $jadwal) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection