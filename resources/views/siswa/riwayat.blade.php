@extends('layouts.app')

@section('title', 'Riwayat Presensi')

@section('content')
<h4 class="mb-3">Riwayat Presensi</h4>

<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="fs-2 fw-bold text-success">{{ $rekap['H'] }}</div>
                <div class="text-muted">Hadir</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="fs-2 fw-bold text-warning">{{ $rekap['I'] }}</div>
                <div class="text-muted">Izin</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="fs-2 fw-bold text-danger">{{ $rekap['A'] }}</div>
                <div class="text-muted">Alfa</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="fs-2 fw-bold">{{ $persen }}%</div>
                <div class="text-muted">Kehadiran</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px">No</th>
                    <th>Tanggal</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayat as $p)
                    @php
                        $badge = ['H' => ['success', 'Hadir'], 'I' => ['warning', 'Izin'], 'A' => ['danger', 'Alfa']][$p->status];
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->tanggal->translatedFormat('l, d F Y') }}</td>
                        <td>{{ $p->jadwalSiswa->jadwal->mapel->nama_mapel ?? '-' }}</td>
                        <td>{{ $p->jadwalSiswa->jadwal->guru->nama ?? '-' }}</td>
                        <td><span class="badge text-bg-{{ $badge[0] }}">{{ $badge[1] }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada catatan presensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection