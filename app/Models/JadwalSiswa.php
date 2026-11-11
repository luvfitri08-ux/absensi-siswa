<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalSiswa extends Model
{
    protected $table = 'jadwal_siswa';
    protected $primaryKey = 'id_jadwal_siswa';
    public $timestamps = false;

    protected $fillable = ['id_mapel_siswa', 'id_jadwal'];

    public function mapelSiswa(): BelongsTo
    {
        return $this->belongsTo(MapelSiswa::class, 'id_mapel_siswa', 'id_mapel_siswa');
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'id_jadwal_siswa', 'id_jadwal_siswa');
    }
}