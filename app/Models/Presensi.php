<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    protected $table = 'presensi';
    protected $primaryKey = 'id_presensi';
    public $timestamps = false;

    protected $fillable = ['id_jadwal_siswa', 'tanggal', 'status'];

    protected $casts = ['tanggal' => 'datetime'];

    public function jadwalSiswa(): BelongsTo
    {
        return $this->belongsTo(JadwalSiswa::class, 'id_jadwal_siswa', 'id_jadwal_siswa');
    }
}