<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'nama',
        'email',
        'npm',
        'password',
        'no_hp',
        'prodi',
        'kelas_id',
        'barcode',
        'user_id',
    ];

    /**
     * Get the user that owns the Peserta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the absensi for the Peserta
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    /**
     * The kegiatan that belong to the Peserta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'absensi');
    }
    // public function kelas()
    // {
    //     return $this->belongsTo(Kelas::class);
    // }
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}