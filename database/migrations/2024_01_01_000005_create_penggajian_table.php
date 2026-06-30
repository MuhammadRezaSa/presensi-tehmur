<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->integer('bulan'); // 1-12
            $table->integer('tahun');
            $table->integer('total_hari_hadir')->default(0);
            $table->integer('total_hari_kerja')->default(0); // hari kerja dalam periode
            $table->integer('total_menit_terlambat')->default(0);
            $table->integer('total_menit_lembur')->default(0);
            $table->integer('total_hari_izin')->default(0);
            $table->integer('total_hari_sakit')->default(0);
            $table->integer('total_hari_tidak_hadir')->default(0);
            $table->decimal('gaji_pokok', 12, 2)->default(0);
            $table->decimal('tunjangan_lembur', 12, 2)->default(0);
            $table->decimal('potongan_tidak_hadir', 12, 2)->default(0);
            $table->decimal('potongan_terlambat', 12, 2)->default(0);
            $table->decimal('total_gaji', 12, 2)->default(0);
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['karyawan_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};
