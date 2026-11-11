<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Presensi Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 720px;">
    <h4 class="text-center mb-1">Cek Presensi Siswa</h4>
    <p class="text-center text-muted mb-4">Masukkan NISN anak untuk melihat kehadirannya.</p>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('cek-presensi') }}" class="row g-2">
                <div class="col-sm-9">
                    <input type="text" name="nisn" value="{{ $nisn }}" maxlength="10"
                           class="form-control" placeholder="NISN" autofocus>
                </div>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary w-100">Cek</button>
                </div>
            </form>
            @error('nisn')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
        </div>
    </div>

    @if ($tidakDitemukan)
        <div class="alert alert-warning">NISN tidak ditemukan. Periksa kembali nomornya.</div>
    @endif

    @if ($siswa)
        <h5 class="mb-3">{{ $siswa->nama }}</h5>

        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <div class="fs-3 fw-bold text-success">{{ $rekap['H'] }}</div>
                        <div class="text-muted">Hadir</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <div class="fs-3 fw-bold text-warning">{{ $rekap['I'] }}</div>
                        <div class="text-muted">Izin</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <div class="fs-3 fw-bold text-danger">{{ $rekap['A'] }}</div>
                        <div class="text-muted">Alfa</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <div class="fs-3 fw-bold">{{ $persen }}%</div>
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
                            <th>Tanggal</th>
                            <th>Mata Pelajaran</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $p)
                            @php
                                $badge = ['H' => ['success', 'Hadir'], 'I' => ['warning', 'Izin'], 'A' => ['danger', 'Alfa']][$p->status];
                            @endphp
                            <tr>
                                <td>{{ $p->tanggal->translatedFormat('l, d F Y') }}</td>
                                <td>{{ $p->jadwalSiswa->jadwal->mapel->nama_mapel ?? '-' }}</td>
                                <td><span class="badge text-bg-{{ $badge[0] }}">{{ $badge[1] }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Belum ada catatan presensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('login') }}" class="small">Masuk sebagai admin, guru, atau siswa</a>
    </div>
</div>
</body>
</html>