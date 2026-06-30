@extends('layouts.owner')

@section('title', 'Kelola Penggajian')
@section('page-title', 'Penggajian Karyawan')

@section('styles')
<style>
    .grid-payroll {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 1.5rem;
    }

    .card-panel {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        height: fit-content;
    }

    .card-panel h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f1f5f9;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 0.35rem;
    }

    .form-control {
        width: 100%;
        padding: 0.65rem 0.85rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background-color: #f8fafc;
        outline: none;
        font-size: 0.9rem;
    }

    .btn-generate {
        width: 100%;
        padding: 0.75rem;
        background-color: var(--accent-green);
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: background-color 0.2s;
    }

    .btn-generate:hover {
        background-color: #166534;
    }

    .btn-view {
        padding: 0.35rem 0.75rem;
        background-color: rgba(0, 40, 85, 0.05);
        color: var(--primary);
        text-decoration: none;
        border-radius: 0.375rem;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .btn-view:hover {
        background-color: var(--primary);
        color: #ffffff;
    }
</style>
@endsection

@section('content')
<div class="grid-payroll">
    <!-- Panel Kiri: Form Hitung Gaji Baru -->
    <div class="card-panel">
        <h2>Hitung Gaji Baru</h2>
        <form action="{{ route('owner.penggajian.generate') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="karyawan_id">Pilih Karyawan</label>
                <select id="karyawan_id" name="karyawan_id" class="form-control" required>
                    <option value="" disabled selected>-- Pilih Staff --</option>
                    @foreach($karyawan as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }} ({{ $k->jabatan }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="bulan">Bulan</label>
                <select id="bulan" name="bulan" class="form-control" required>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="tahun">Tahun</label>
                <select id="tahun" name="tahun" class="form-control" required>
                    <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                    <option value="{{ date('Y')-1 }}">{{ date('Y')-1 }}</option>
                </select>
            </div>

            <button type="submit" class="btn-generate">
                <i class="fa-solid fa-calculator"></i> Kalkulasi Gaji
            </button>
        </form>
    </div>

    <!-- Panel Kanan: Daftar Slip Gaji Terhitung -->
    <div class="card-panel">
        <h2>Daftar Slip Gaji Terbit</h2>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Periode</th>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Karyawan</th>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Hadir/Izin/Bolos</th>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Total Diterima</th>
                        <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0; width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penggajian as $p)
                        <tr>
                            <td style="padding: 0.85rem 1rem; border-bottom: 1px solid #f1f5f9;">
                                <strong>{{ $p->nama_bulan }} {{ $p->tahun }}</strong>
                            </td>
                            <td style="padding: 0.85rem 1rem; border-bottom: 1px solid #f1f5f9;">
                                <strong>{{ $p->karyawan->name }}</strong>
                                <div style="font-size: 0.75rem; color: var(--text-muted)">{{ $p->karyawan->jabatan }}</div>
                            </td>
                            <td style="padding: 0.85rem 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem;">
                                Hadir: {{ $p->total_hari_hadir }}h | Bolos: {{ $p->total_hari_tidak_hadir }}h
                            </td>
                            <td style="padding: 0.85rem 1rem; border-bottom: 1px solid #f1f5f9; font-weight: 700; color: var(--accent-green);">
                                Rp {{ number_format($p->total_gaji, 0, ',', '.') }}
                            </td>
                            <td style="padding: 0.85rem 1rem; border-bottom: 1px solid #f1f5f9;">
                                <a href="{{ route('owner.penggajian.show', $p->id) }}" class="btn-view">
                                    <i class="fa-solid fa-file-invoice-dollar"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                                <i class="fa-regular fa-folder-open" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
                                Belum ada slip gaji yang diproses.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
