@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<h4 class="mb-4">Dashboard Admin</h4>

<div class="row g-3">
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="fs-2 fw-bold">{{ $jumlahGuru }}</div>
                <div class="text-muted">Guru</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="fs-2 fw-bold">{{ $jumlahSiswa }}</div>
                <div class="text-muted">Siswa</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="fs-2 fw-bold">{{ $jumlahMapel }}</div>
                <div class="text-muted">Mata Pelajaran</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="fs-2 fw-bold">{{ $jumlahJadwal }}</div>
                <div class="text-muted">Jadwal</div>
            </div>
        </div>
    </div>
</div>
@endsection