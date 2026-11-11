@extends('layouts.app')

@section('title', 'Ubah Guru')

@section('content')
<h4 class="mb-3">Ubah Guru</h4>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('guru.update', $guru) }}">
            @csrf
            @method('PUT')
            @include('guru._form')
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('guru.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection