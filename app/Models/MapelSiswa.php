<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapelSiswa extends Model
{
    protected $table = 'mapel_siswa';
    protected $primaryKey = 'id_mapel_siswa';
    public $timestamps = false;

    protected $fillable = ['nisn', 'kode_mapel', 'kode_kelas'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}