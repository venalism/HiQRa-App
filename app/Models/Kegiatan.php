<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'tanggal',
        'waktu',
        'lokasi',
        'target_prodi',
        'target_kelas',
        'target_tingkat',
        'target_jabatan',
    ];

    /**
     * Get all of the absensi for the Kegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    /**
     * The peserta that belong to the Kegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function peserta()
    {
        return $this->belongsToMany(Peserta::class, 'absensi');
    }
}
