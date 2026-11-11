@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<h4 class="mb-1">Dashboard Siswa</h4>
<p class="text-muted">Selamat datang, {{ $siswa->nama }}.</p>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">NISN</div>
                <div class="fs-5 fw-bold">{{ $siswa->nisn }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Kelas</div>
                <div class="fs-5 fw-bold">{{ $kelas ?: '-' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Mata Pelajaran</div>
                <div class="fs-5 fw-bold">{{ $daftarMapel->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-header">Jadwal pelajaran saya</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwalSiswa as $js)
                    <tr>
                        <td>{{ $js->jadwal->hari }}</td>
                        <td>{{ substr($js->jadwal->waktu_awal, 0, 5) }} - {{ substr($js->jadwal->waktu_akhir, 0, 5) }}</td>
                        <td>{{ $js->jadwal->mapel->nama_mapel ?? '-' }}</td>
                        <td>{{ $js->jadwal->guru->nama ?? '-' }}</td>
                        <td>{{ $js->mapelSiswa->kode_kelas }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada jadwal untuk Anda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-header">Daftar mata pelajaran</div>
    <ul class="list-group list-group-flush">
        @forelse ($daftarMapel as $m)
            <li class="list-group-item">{{ $m->kode_mapel }} - {{ $m->nama_mapel }}</li>
        @empty
            <li class="list-group-item text-muted">Belum ada mata pelajaran.</li>
        @endforelse
    </ul>
</div>

<a href="{{ route('siswa.riwayat') }}" class="btn btn-primary">Lihat Riwayat Presensi</a>
@endsection