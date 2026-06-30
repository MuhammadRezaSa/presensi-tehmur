@extends('layouts.owner')

@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard')

@section('styles')
<style>
    /* ── CARD STATISTIK ────────────────────────────────── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-details h3 {
        font-size: 0.85rem;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .stat-details .number {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Warna Icon & Background */
    .icon-cabang {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .icon-karyawan {
        background-color: rgba(79, 70, 229, 0.1);
        color: #4f46e5;
    }

    .icon-hadir {
        background-color: rgba(21, 128, 61, 0.1);
        color: #15803d;
    }

    .icon-terlambat {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* ── PANELS (GRAFIK/TABEL) ─────────────────────────── */
    .dashboard-panels {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    .panel {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }

    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .panel-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--primary);
    }

    /* Tabel Presensi */
    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    th {
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid #e2e8f0;
    }

    td {
        padding: 1rem;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    /* Status Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.6rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .badge-success {
        background-color: #dcfce7;
        color: #15803d;
    }

    .badge-warning {
        background-color: #fef9c3;
        color: #a16207;
    }

    .badge-danger {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    /* Quick Info Box */
    .quick-info-box {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        border-radius: 0.75rem;
        background-color: var(--bg-body);
        border: 1px solid #f1f5f9;
    }

    .info-item-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.5rem;
        background-color: var(--primary);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .info-item-details h4 {
        font-size: 0.875rem;
        font-weight: 600;
    }

    .info-item-details p {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .dashboard-panels {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Grid Card Statistik -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-details">
            <h3>Total Cabang</h3>
            <div class="number">{{ $totalCabang }}</div>
        </div>
        <div class="stat-icon icon-cabang">
            <i class="fa-solid fa-store"></i>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-details">
            <h3>Total Karyawan</h3>
            <div class="number">{{ $totalKaryawan }}</div>
        </div>
        <div class="stat-icon icon-karyawan">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-details">
            <h3>Hadir Hari Ini</h3>
            <div class="number">{{ $hadirHariIni }}</div>
        </div>
        <div class="stat-icon icon-hadir">
            <i class="fa-solid fa-circle-check"></i>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-details">
            <h3>Terlambat Hari Ini</h3>
            <div class="number">{{ $terlambatHariIni }}</div>
        </div>
        <div class="stat-icon icon-terlambat">
            <i class="fa-solid fa-clock"></i>
        </div>
    </div>
</div>

<!-- Layout Panel Bawah -->
<div class="dashboard-panels">
    <!-- Panel Kiri: Aktivitas Kehadiran Hari Ini -->
    <div class="panel">
        <div class="panel-header">
            <h2>Aktivitas Kehadiran Hari Ini</h2>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Cabang</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($presensiTerbaru as $p)
                        <tr>
                            <td>
                                <strong>{{ $p->karyawan->name }}</strong>
                                <div style="font-size: 0.75rem; color: var(--text-muted)">{{ $p->karyawan->jabatan }}</div>
                            </td>
                            <td>{{ $p->cabang->nama_cabang }}</td>
                            <td>{{ $p->jam_masuk ?? '-' }}</td>
                            <td>{{ $p->jam_pulang ?? '-' }}</td>
                            <td>
                                @if($p->status == 'hadir')
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Tepat Waktu</span>
                                @elseif($p->status == 'terlambat')
                                    <span class="badge badge-warning"><i class="fa-solid fa-triangle-exclamation"></i> Terlambat ({{ $p->menit_terlambat }}m)</span>
                                @else
                                    <span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> Tidak Hadir</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                <i class="fa-regular fa-folder-open" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                                Belum ada aktivitas presensi hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Panel Kanan: Aturan Kedisiplinan -->
    <div class="panel">
        <div class="panel-header">
            <h2>Aturan Kedisiplinan & Cabang</h2>
        </div>
        <div class="quick-info-box">
            <div class="info-item">
                <div class="info-item-icon">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
                <div class="info-item-details">
                    <h4>Batas Toleransi Keterlambatan</h4>
                    <p>Maksimal 10 menit dari waktu jam masuk shift kerja.</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-item-icon" style="background-color: #ea580c;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="info-item-details">
                    <h4>Akumulasi Keterlambatan</h4>
                    <p>Batas maksimal keterlambatan 100 menit per bulan.</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-item-icon" style="background-color: #16a34a;">
                    <i class="fa-solid fa-location-crosshairs"></i>
                </div>
                <div class="info-item-details">
                    <h4>Validasi Radius Presensi</h4>
                    <p>Karyawan wajib berada dalam jarak maksimal 100 meter dari cabang.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
