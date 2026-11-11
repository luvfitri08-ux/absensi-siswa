@extends('layouts.app')

@section('title', 'Input Presensi')

@section('content')
<h4 class="mb-3">Input Presensi</h4>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route($ruteIndex) }}" class="row g-3 align-items-end">
            <div class="col-md-7">
                <label class="form-label">Jadwal</label>
                <select name="id_jadwal" class="form-select">
                    <option value="">-- Pilih jadwal --</option>
                    @foreach ($daftarJadwal as $j)
                        <option value="{{ $j->id_jadwal }}" @selected($jadwal && $jadwal->id_jadwal == $j->id_jadwal)>
                            {{ $j->hari }} {{ substr($j->waktu_awal, 0, 5) }}-{{ substr($j->waktu_akhir, 0, 5) }}
                            | {{ $j->mapel->nama_mapel ?? '-' }} | {{ $j->kode_kelas }} | {{ $j->guru->nama ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" max="{{ now()->toDateString() }}"
                       class="form-control">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </form>
        @error('tanggal')
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror
    </div>
</div>

@if ($jadwal)
    <div class="card shadow-sm">
        <div class="card-header">
            {{ $jadwal->mapel->nama_mapel ?? '-' }} &middot; {{ $jadwal->kode_kelas }} &middot;
            {{ $jadwal->hari }}, {{ $tanggal }}
            <span class="text-muted">(H = Hadir, I = Izin, A = Alfa)</span>
        </div>

        @if ($peserta->isEmpty())
            <div class="card-body text-muted">
                Belum ada siswa terdaftar di jadwal ini.
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('jadwal.peserta', $jadwal) }}">Daftarkan siswa</a> dulu.
                @else
                    Hubungi admin untuk mendaftarkan siswa.
                @endif
            </div>
        @else
            <form method="POST" action="{{ route($ruteSimpan) }}">
                @csrf
                <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px">No</th>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th style="width: 220px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peserta as $p)
                                @php
                                    $id = $p->id_jadwal_siswa;
                                    $pilihan = old("status.$id", $statusTersimpan[$id] ?? 'H');
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->mapelSiswa->nisn }}</td>
                                    <td>{{ $p->mapelSiswa->siswa->nama }}</td>
                                    <td>
                                        @foreach (['H' => 'H', 'I' => 'I', 'A' => 'A'] as $nilai => $label)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio"
                                                       name="status[{{ $id }}]" value="{{ $nilai }}"
                                                       id="s-{{ $id }}-{{ $nilai }}" @checked($pilihan === $nilai)>
                                                <label class="form-check-label" for="s-{{ $id }}-{{ $nilai }}">{{ $label }}</label>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-body border-top">
                    <button type="submit" class="btn btn-primary">Simpan Presensi</button>
                </div>
            </form>
        @endif
    </div>
@endif
@endsection