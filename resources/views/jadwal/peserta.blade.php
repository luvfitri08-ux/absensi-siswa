@extends('layouts.app')

@section('title', 'Peserta Jadwal')

@section('content')
<div class="mb-3">
    <a href="{{ route('jadwal.index') }}" class="text-decoration-none">&larr; Kembali ke jadwal</a>
</div>

<h4 class="mb-1">Peserta Jadwal</h4>
<p class="text-muted">
    {{ $jadwal->mapel->nama_mapel ?? '-' }} &middot; {{ $jadwal->kode_kelas }} &middot;
    {{ $jadwal->hari }}, {{ substr($jadwal->waktu_awal, 0, 5) }}-{{ substr($jadwal->waktu_akhir, 0, 5) }}
    &middot; {{ $jadwal->guru->nama ?? '-' }}
</p>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header">Siswa terdaftar ({{ $peserta->count() }})</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px">No</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th style="width: 110px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peserta as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->mapelSiswa->nisn }}</td>
                                <td>{{ $p->mapelSiswa->siswa->nama }}</td>
                                <td>
                                    <form action="{{ route('jadwal.peserta.destroy', [$jadwal, $p]) }}" method="POST"
                                          onsubmit="return confirm('Keluarkan siswa ini dari jadwal?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Keluarkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada siswa terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header">Daftarkan siswa</div>
            <div class="card-body">
                @if ($siswaTersedia->isEmpty())
                    <p class="text-muted mb-0">Semua siswa sudah terdaftar di jadwal ini.</p>
                @else
                    <form method="POST" action="{{ route('jadwal.peserta.store', $jadwal) }}">
                        @csrf
                        <div class="border rounded p-2 mb-3" style="max-height: 300px; overflow-y: auto;">
                            @foreach ($siswaTersedia as $s)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="nisn[]"
                                           value="{{ $s->nisn }}" id="siswa-{{ $s->nisn }}">
                                    <label class="form-check-label" for="siswa-{{ $s->nisn }}">
                                        {{ $s->nama }} ({{ $s->nisn }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('nisn')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-primary">Daftarkan</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection