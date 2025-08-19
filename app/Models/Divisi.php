<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisis'; // Pastikan sesuai dengan nama tabel kamu
    protected $fillable = ['nama', 'jabatan_id'];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
    public function panitia()
    {
        return $this->hasMany(Panitia::class);
    }

}