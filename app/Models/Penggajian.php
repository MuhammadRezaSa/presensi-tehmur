<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    protected $table = 'penggajian';

    protected $fillable = [
        'karyawan_id', 'bulan', 'tahun',
        'total_hari_hadir', 'total_hari_kerja', 'total_menit_terlambat',
        'total_menit_lembur', 'total_hari_izin', 'total_hari_sakit', 'total_hari_tidak_hadir',
        'gaji_pokok', 'tunjangan_lembur', 'potongan_tidak_hadir', 'potongan_terlambat',
        'total_gaji', 'status', 'catatan'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function getNamaBulanAttribute(): string
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulan[$this->bulan] ?? '-';
    }
}
