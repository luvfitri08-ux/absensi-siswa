@php $g = $guru ?? null; @endphp

<div class="mb-3">
    <label class="form-label">NIP</label>
    @if ($g)
        <input type="text" value="{{ $g->nip }}" class="form-control" disabled>
        <div class="form-text">NIP tidak bisa diubah.</div>
    @else
        <input type="text" name="nip" value="{{ old('nip') }}" maxlength="10"
               class="form-control @error('nip') is-invalid @enderror">
        @error('nip')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Dipakai sebagai username. Password awal sama dengan NIP.</div>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="nama" value="{{ old('nama', $g->nama ?? '') }}"
           class="form-control @error('nama') is-invalid @enderror">
    @error('nama')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Jenis Kelamin</label>
    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
        <option value="">-- Pilih --</option>
        <option value="L" @selected(old('jenis_kelamin', $g->jenis_kelamin ?? '') === 'L')>Laki-laki</option>
        <option value="P" @selected(old('jenis_kelamin', $g->jenis_kelamin ?? '') === 'P')>Perempuan</option>
    </select>
    @error('jenis_kelamin')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $g->tempat_lahir ?? '') }}"
               class="form-control @error('tempat_lahir') is-invalid @enderror">
        @error('tempat_lahir')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $g->tanggal_lahir ?? '') }}"
               class="form-control @error('tanggal_lahir') is-invalid @enderror">
        @error('tanggal_lahir')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" rows="3"
              class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $g->alamat ?? '') }}</textarea>
    @error('alamat')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>