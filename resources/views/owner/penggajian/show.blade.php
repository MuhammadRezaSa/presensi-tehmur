@extends('layouts.owner')

@section('title', 'Detail Gaji')
@section('page-title', 'Detail Slip Gaji')

@section('styles')
<style>
    .slip-card {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        max-width: 700px;
        margin: 0 auto;
    }

    .slip-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px dashed #e2e8f0;
    }

    .slip-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        color: var(--primary);
    }

    .slip-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .slip-section-title {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .slip-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        font-size: 0.9rem;
    }

    .slip-total-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        font-size: 1.1rem;
        font-weight: 700;
        border-top: 2px solid #e2e8f0;
        color: var(--accent-green);
        margin-top: 1rem;
    }

    .btn-pdf {
        display: block;
        width: 100%;
        text-align: center;
        padding: 0.75rem;
        background-color: #b91c1c;
        color: #ffffff;
        text-decoration: none;
        border-radius: 0.5rem;
        font-weight: 600;
        margin-top: 2rem;
        transition: background-color 0.2s;
    }

    .btn-pdf:hover {
        background-color: #991b1b;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('owner.penggajian.index') }}" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 0.9rem;">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Gaji
    </a>
</div>

<div class="slip-card">
    <div class="slip-header">
        <h2>SLIP GAJI KARYAWAN</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Outlet Teh Mur — {{ $penggajian->nama_bulan }} {{ $penggajian->tahun }}</p>
    </div>

    <div class="slip-grid">
        <div>
            <div class="slip-section-title">Informasi Karyawan</div>
            <div style="font-size: 0.9rem; line-height: 1.6;">
                <strong>Nama:</strong> {{ $penggajian->karyawan->name }}<br>
                <strong>Jabatan:</strong> {{ $penggajian->karyawan->jabatan }}<br>
                <strong>NIK:</strong> {{ $penggajian->karyawan->nik ?? '-' }}<br>
                <strong>Cabang:</strong> {{ $penggajian->karyawan->cabang->nama_cabang }}
            </div>
        </div>
        <div>
            <div class="slip-section-title">Kehadiran (Absensi)</div>
            <div style="font-size: 0.9rem; line-height: 1.6;">
                <strong>Hadir Efektif:</strong> {{ $penggajian->total_hari_hadir }} Hari<br>
                <strong>Ketidakhadiran:</strong> {{ $penggajian->total_hari_tidak_hadir }} Hari<br>
                <strong>Total Terlambat:</strong> {{ $penggajian->total_menit_terlambat }} Menit<br>
                <strong>Total Lembur:</strong> {{ intdiv($penggajian->total_menit_lembur, 60) }}j {{ $penggajian->total_menit_lembur % 60 }}m
            </div>
        </div>
    </div>

    <div>
        <div class="slip-section-title" style="border-bottom: 1px solid #e2e8f0; padding-bottom: 0.25rem;">Rincian Keuangan</div>
        
        <!-- Pendapatan -->
        <div class="slip-row">
            <span>Gaji Pokok</span>
            <span>Rp {{ number_format($penggajian->gaji_pokok, 0, ',', '.') }}</span>
        </div>
        <div class="slip-row" style="color: var(--accent-green);">
            <span>Tunjangan Lembur (+)</span>
            <span>Rp {{ number_format($penggajian->tunjangan_lembur, 0, ',', '.') }}</span>
        </div>

        <!-- Potongan -->
        <div class="slip-row" style="color: #b91c1c;">
            <span>Potongan Ketidakhadiran (-)</span>
            <span>Rp {{ number_format($penggajian->potongan_tidak_hadir, 0, ',', '.') }}</span>
        </div>
        <div class="slip-row" style="color: #b91c1c;">
            <span>Potongan Keterlambatan (-)</span>
            <span>Rp {{ number_format($penggajian->potongan_terlambat, 0, ',', '.') }}</span>
        </div>

        <!-- Total Bersih -->
        <div class="slip-total-row">
            <span>Gaji Bersih Diterima (Take Home Pay)</span>
            <span>Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</span>
        </div>
    </div>

    <a href="{{ route('owner.penggajian.pdf', $penggajian->id) }}" class="btn-pdf">
        <i class="fa-solid fa-file-pdf"></i> Unduh Slip Gaji PDF
    </a>
</div>
@endsection
