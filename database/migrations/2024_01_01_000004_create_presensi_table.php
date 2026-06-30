<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->foreignId('cabang_id')->constrained('cabang')->onDelete('cascade');
            $table->date('tanggal');
            // Presensi Masuk
            $table->time('jam_masuk')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->decimal('lat_masuk', 10, 8)->nullable();
            $table->decimal('lng_masuk', 11, 8)->nullable();
            $table->boolean('wajah_masuk_valid')->default(false);
            $table->boolean('lokasi_masuk_valid')->default(false);
            // Presensi Pulang
            $table->time('jam_pulang')->nullable();
            $table->string('foto_pulang')->nullable();
            $table->decimal('lat_pulang', 10, 8)->nullable();
            $table->decimal('lng_pulang', 11, 8)->nullable();
            $table->boolean('wajah_pulang_valid')->default(false);
            $table->boolean('lokasi_pulang_valid')->default(false);
            // Perhitungan
            $table->integer('total_menit_kerja')->nullable();
            $table->integer('menit_terlambat')->default(0);
            $table->integer('menit_lembur')->default(0);
            $table->enum('status', ['hadir', 'terlambat', 'tidak_hadir', 'izin', 'sakit'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['karyawan_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
