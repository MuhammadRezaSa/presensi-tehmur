<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'cabang';

    protected $fillable = [
        'user_id', 'nama_cabang', 'alamat', 'latitude', 'longitude', 'radius_meter', 'pin_tambah_karyawan', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }
}
