@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
<h4 class="mb-3">Tambah Siswa</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('siswa.store') }}">
            @csrf
            @include('siswa._form')
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection