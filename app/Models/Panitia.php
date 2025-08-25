<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Divisi;
use App\Models\jabatan;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Panitia extends Model
{
    use HasFactory;

    protected $table = 'panitia';
    protected $guarded = ['id'];
    // Sembunyikan password
     protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $fillable = [
        'nama', 
        'email', 
        'npm', 
        'password',
        'no_hp', 
        'divisi_id',
        'barcode'
    ];

    /**
     * Get the user that owns the Panitia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
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
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}