<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panitia extends Model
{
    use HasFactory;

    protected $table = 'panitia';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'jabatan',
        'barcode',
    ];

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
}