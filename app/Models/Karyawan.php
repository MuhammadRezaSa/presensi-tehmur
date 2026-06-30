<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $fillable = [
        'cabang_id', 'nama', 'email', 'nik', 'no_telepon', 'foto_wajah',
        'jabatan', 'shift', 'jam_masuk_shift', 'jam_pulang_shift',
        'gaji_pokok', 'upah_lembur_per_jam', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function penggajian()
    {
        return $this->hasMany(Penggajian::class);
    }

    public function getNameAttribute()
    {
        return $this->nama;
    }
}
