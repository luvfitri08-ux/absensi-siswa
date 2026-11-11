@php $j = $jadwal ?? null; @endphp

<div class="mb-3">
    <label class="form-label">Mata Pelajaran</label>
    <select name="kode_mapel" class="form-select @error('kode_mapel') is-invalid @enderror">
        <option value="">-- Pilih --</option>
        @foreach ($daftarMapel as $m)
            <option value="{{ $m->kode_mapel }}" @selected(old('kode_mapel', $j->kode_mapel ?? '') === $m->kode_mapel)>
                {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
            </option>
        @endforeach
    </select>
    @error('kode_mapel')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Guru Pengajar</label>
    <select name="nip" class="form-select @error('nip') is-invalid @enderror">
        <option value="">-- Pilih --</option>
        @foreach ($daftarGuru as $g)
            <option value="{{ $g->nip }}" @selected(old('nip', $j->nip ?? '') === $g->nip)>
                {{ $g->nama }} ({{ $g->nip }})
            </option>
        @endforeach
    </select>
    @error('nip')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Kelas</label>
        <select name="kode_kelas" class="form-select @error('kode_kelas') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach ($daftarKelas as $k)
                <option value="{{ $k }}" @selected(strcasecmp(old('kode_kelas', $j->kode_kelas ?? ''), $k) === 0)>
                    {{ $k }}
                </option>
            @endforeach
        </select>
        @error('kode_kelas')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Hari</label>
        <select name="hari" class="form-select @error('hari') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach ($daftarHari as $h)
                <option value="{{ $h }}" @selected(old('hari', $j->hari ?? '') === $h)>{{ $h }}</option>
            @endforeach
        </select>
        @error('hari')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Jam Mulai</label>
        <input type="time" name="waktu_awal"
               value="{{ old('waktu_awal', $j ? substr($j->waktu_awal, 0, 5) : '') }}"
               class="form-control @error('waktu_awal') is-invalid @enderror">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Jam Selesai</label>
        <input type="time" name="waktu_akhir"
               value="{{ old('waktu_akhir', $j ? substr($j->waktu_akhir, 0, 5) : '') }}"
               class="form-control @error('waktu_akhir') is-invalid @enderror">
    </div>
</div>
@error('waktu_awal')
    <div class="text-danger small mb-3">{{ $message }}</div>
@enderror
@error('waktu_akhir')
    <div class="text-danger small mb-3">{{ $message }}</div>
@enderror