@php $s = $siswa ?? null; @endphp

<div class="mb-3">
    <label class="form-label">NISN</label>
    @if ($s)
        <input type="text" value="{{ $s->nisn }}" class="form-control" disabled>
        <div class="form-text">NISN tidak bisa diubah.</div>
    @else
        <input type="text" name="nisn" value="{{ old('nisn') }}" maxlength="10"
               class="form-control @error('nisn') is-invalid @enderror">
        @error('nisn')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Dipakai sebagai username. Password awal sama dengan NISN.</div>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="nama" value="{{ old('nama', $s->nama ?? '') }}"
           class="form-control @error('nama') is-invalid @enderror">
    @error('nama')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            <option value="L" @selected(old('jenis_kelamin', $s->jenis_kelamin ?? '') === 'L')>Laki-laki</option>
            <option value="P" @selected(old('jenis_kelamin', $s->jenis_kelamin ?? '') === 'P')>Perempuan</option>
        </select>
        @error('jenis_kelamin')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="tempat_lahir" value="{{ old('tempat_lahir', $s->tempat_lahir ?? '') }}"
               class="form-control @error('tempat_lahir') is-invalid @enderror">
        @error('tempat_lahir')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Ayah</label>
        <input type="text" name="bapak" value="{{ old('bapak', $s->bapak ?? '') }}"
               class="form-control @error('bapak') is-invalid @enderror">
        @error('bapak')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama Ibu</label>
        <input type="text" name="ibu" value="{{ old('ibu', $s->ibu ?? '') }}"
               class="form-control @error('ibu') is-invalid @enderror">
        @error('ibu')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" rows="3"
              class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $s->alamat ?? '') }}</textarea>
    @error('alamat')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>