@extends('layouts.app')

@section('title', 'Ubah Mata Pelajaran')

@section('content')
<h4 class="mb-3">Ubah Mata Pelajaran</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('mapel.update', $mapel) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Kode Mapel</label>
                <input type="text" value="{{ $mapel->kode_mapel }}" class="form-control" disabled>
                <div class="form-text">Kode tidak bisa diubah.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Mata Pelajaran</label>
                <input type="text" name="nama_mapel" value="{{ old('nama_mapel', $mapel->nama_mapel) }}"
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