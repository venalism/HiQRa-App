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

        public function panitias()
    {
        return $this->belongsToMany(Panitia::class, 'kegiatan_panitias');
    }
    
        public function targetDivisis()
    {
        return $this->belongsToMany(Divisi::class, 'kegiatan_target_divisi');
    }

        public function targetKelas()
    {
        return $this->belongsToMany(Kelas::class, 'kegiatan_kelas');
    }
    /**
     * Get the kelas that owns the Kegiatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    /**
     * The prodis that belong to the Kegiatan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function prodis()
    {
        // Relasi BelongsToMany: Kegiatan memiliki banyak Prodi
        // Asumsi: Ada tabel pivot (penghubung) untuk kegiatan dan prodi.
        // Relasi ini dibutuhkan untuk filter pencarian yang kamu gunakan.
        return $this->belongsToMany(Prodi::class);
    }
}