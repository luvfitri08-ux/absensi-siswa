@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<h4 class="mb-3">Tambah Jadwal</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('jadwal.store') }}">
            @csrf
            @include('jadwal._form')
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection