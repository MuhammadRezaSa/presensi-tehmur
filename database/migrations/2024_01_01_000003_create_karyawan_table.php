<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->constrained('cabang')->onDelete('cascade');
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('nik')->unique()->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('foto_wajah')->nullable(); // path foto untuk face recognition
            $table->string('jabatan')->nullable();
            $table->enum('shift', ['pagi', 'siang', 'malam'])->default('pagi');
            $table->time('jam_masuk_shift')->default('08:00:00');
            $table->time('jam_pulang_shift')->default('17:00:00');
            $table->decimal('gaji_pokok', 12, 2)->default(0);
            $table->decimal('upah_lembur_per_jam', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
