<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 'presensi';

    protected $fillable = [
        'karyawan_id', 'cabang_id', 'tanggal',
        'jam_masuk', 'foto_masuk', 'lat_masuk', 'lng_masuk', 'wajah_masuk_valid', 'lokasi_masuk_valid',
        'jam_pulang', 'foto_pulang', 'lat_pulang', 'lng_pulang', 'wajah_pulang_valid', 'lokasi_pulang_valid',
        'total_menit_kerja', 'menit_terlambat', 'menit_lembur', 'status', 'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'wajah_masuk_valid' => 'boolean',
        'lokasi_masuk_valid' => 'boolean',
        'wajah_pulang_valid' => 'boolean',
        'lokasi_pulang_valid' => 'boolean',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function getTotalJamKerjaAttribute(): string
    {
        if (!$this->total_menit_kerja) return '-';
        $jam = intdiv($this->total_menit_kerja, 60);
        $menit = $this->total_menit_kerja % 60;
        return "{$jam}j {$menit}m";
    }
}
