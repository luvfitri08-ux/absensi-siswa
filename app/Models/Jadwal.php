<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';
    public $timestamps = false;

    protected $fillable = ['kode_mapel', 'nip', 'kode_kelas', 'waktu_awal', 'waktu_akhir', 'hari'];

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'kode_mapel', 'kode_mapel');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'nip', 'nip');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(JadwalSiswa::class, 'id_jadwal', 'id_jadwal');
    }
}