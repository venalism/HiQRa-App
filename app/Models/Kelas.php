<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prodi;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama', 'prodi_id'];

    // public function prodi()
    // {
    //     return $this->belongsTo(Prodi::class);
    // }
    public function peserta()
    {
        return $this->hasMany(Peserta::class);
    }
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
}
