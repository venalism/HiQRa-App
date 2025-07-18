<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisis'; // Pastikan sesuai dengan nama tabel kamu
    protected $fillable = ['nama_divisi'];

    public function panitias()
    {
        return $this->hasMany(Panitia::class);
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
    public function panitia()
    {
        return $this->hasMany(Panitia::class);
    }

}