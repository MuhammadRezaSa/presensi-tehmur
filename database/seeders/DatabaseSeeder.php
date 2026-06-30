<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cabang;
use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Owner
        $ownerUser = User::create([
            'name' => 'Muhammad Reza Sajidin',
            'email' => 'owner@tehmur.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'is_active' => true,
        ]);

        // 2. Buat User Cabang
        $cabangUser = User::create([
            'name' => 'Admin Cabang Sudirman',
            'email' => 'sudirman@tehmur.com',
            'password' => Hash::make('password123'),
            'role' => 'cabang',
            'is_active' => true,
        ]);

        // 3. Buat Cabang Utama & hubungkan ke User Cabang
        $cabangSudirman = Cabang::create([
            'user_id' => $cabangUser->id,
            'nama_cabang' => 'Teh Mur Sudirman',
            'alamat' => 'Jl. Jenderal Sudirman No. 123, Pekanbaru',
            'latitude' => 0.507068, // Titik koordinat simulasi di Pekanbaru
            'longitude' => 101.447579,
            'radius_meter' => 100.00,
            'pin_tambah_karyawan' => '123456',
            'is_active' => true,
        ]);

        // 4. Buat Karyawan (Tanpa akun User login, langsung ke cabang)
        Karyawan::create([
            'cabang_id' => $cabangSudirman->id,
            'nama' => 'Budi Santoso',
            'email' => 'budi@tehmur.com',
            'nik' => '22071133770001',
            'no_telepon' => '081234567890',
            'foto_wajah' => 'profiles/default.jpg',
            'jabatan' => 'Barista',
            'shift' => 'pagi',
            'jam_masuk_shift' => '08:00:00',
            'jam_pulang_shift' => '17:00:00',
            'gaji_pokok' => 2000000.00, // Gaji Pokok Rp 2.000.000
            'upah_lembur_per_jam' => 15000.00, // Lembur Rp 15.000/jam
            'is_active' => true,
        ]);
    }
}
