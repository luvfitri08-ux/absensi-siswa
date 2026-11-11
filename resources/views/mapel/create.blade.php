@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<h4 class="mb-3">Tambah Mata Pelajaran</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('mapel.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Kode Mapel</label>
                <input type="text" name="kode_mapel" value="{{ old('kode_mapel') }}" maxlength="10"
                       class="form-control @error('kode_mapel') is-invalid @enderror">
                @error('kode_mapel')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Mata Pelajaran</label>
                <input type="text" name="nama_mapel" value="{{ old('nama_mapel') }}"
                       class="form-control @error('nama_mapel') is-invalid @enderror">
                @error('nama_mapel')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('mapel.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection