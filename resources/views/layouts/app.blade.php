<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Absensi Siswa')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/dashboard">Absensi Siswa</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuUtama">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard">Dashboard</a>
                </li>
                    @if (auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mapel.index') }}">Mapel</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('guru.index') }}">Guru</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('siswa.index') }}">Siswa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('jadwal.index') }}">Jadwal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('presensi.index') }}">Presensi</a>
                        </li>
                    @endif
                    @if (auth()->user()->role === 'guru')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('guru.presensi.index') }}">Presensi</a>
                        </li>
                    @endif
                    @if (auth()->user()->role === 'siswa')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('siswa.riwayat') }}">Riwayat Presensi</a>
                        </li>
                    @endif
                </ul>
            <span class="navbar-text me-3">
                {{ auth()->user()->name }} ({{ auth()->user()->role }})
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Keluar</button>
            </form>
        </div>
    </div>
</nav>

<main class="container py-4">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>