@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<h4 class="mb-1">Dashboard Guru</h4>
<p class="text-muted">Selamat datang, {{ auth()->user()->name }}.</p>

<div class="card shadow-sm">
    <div class="card-header">Jadwal mengajar saya</div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Siswa</th>
                    <th style="width: 250px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarJadwal as $j)
                    <tr>
                        <td>{{ $j->hari }}</td>
                        <td>{{ substr($j->waktu_awal, 0, 5) }} - {{ substr($j->waktu_akhir, 0, 5) }}</td>
                        <td>{{ $j->mapel->nama_mapel ?? '-' }}</td>
                        <td>{{ $j->kode_kelas }}</td>
                        <td>{{ $j->peserta->count() }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#siswa-{{ $j->id_jadwal }}">
                                Lihat Siswa
                            </button>
                            <a href="{{ route('guru.presensi.index', ['id_jadwal' => $j->id_jadwal]) }}"
                               class="btn btn-sm btn-primary">Isi Presensi</a>
                        </td>
                    </tr>
                    <tr class="collapse" id="siswa-{{ $j->id_jadwal }}">
                        <td colspan="6" class="bg-light">
                            @forelse ($j->peserta->sortBy(fn ($p) => $p->mapelSiswa->siswa->nama) as $p)
                                <div>
                                    {{ $p->mapelSiswa->siswa->nama }}
                                    <span class="text-muted">({{ $p->mapelSiswa->nisn }})</span>
                                </div>
                            @empty
                                <span class="text-muted">Belum ada siswa terdaftar.</span>
                            @endforelse
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada jadwal untuk Anda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection