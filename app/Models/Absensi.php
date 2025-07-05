<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'peserta_id',
        'kegiatan_id',
        'user_id',
        'waktu_hadir',
        'metode',
        'status',
        'keterangan',
    ];

    /**
     * Get the peserta that owns the Absensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    /**
     * Get the kegiatan that owns the Absensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    /**
     * Get the user (panitia) that recorded the Absensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
