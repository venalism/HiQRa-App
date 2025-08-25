<?php

// File: app/Models/Peserta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Peserta extends Authenticatable
{
    use HasFactory;

    protected $table = 'peserta';

    // Menggunakan guarded untuk mengizinkan semua mass assignment kecuali 'id'
    protected $guarded = ['id']; 

    // Sembunyikan password
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    // Relasi untuk kelas (sudah benar)
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi prodi yang diperbaiki (melalui kelas)
    public function prodi()
    {
        return $this->kelas->prodi();
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