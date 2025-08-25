<?php

// File: app/Models/Panitia.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Panitia extends Authenticatable
{
    use HasFactory;

    protected $table = 'panitia';
    
    // Menggunakan guarded untuk mengizinkan semua mass assignment kecuali 'id'
    protected $guarded = ['id'];

    // Sembunyikan password
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    // Relasi divisi dan jabatan
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    // Relasi absensi dan kegiatan
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'absensi');
    }
}