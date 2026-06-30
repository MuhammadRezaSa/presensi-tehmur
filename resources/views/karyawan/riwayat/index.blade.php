@extends('layouts.karyawan')

@section('title', 'Riwayat Absensi Cabang')

@section('styles')
<style>
    .riwayat-head {
        margin-bottom: 1rem;
    }

    .riwayat-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.2rem;
    }

    .riwayat-subtitle {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .table-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 0.9rem;
        margin-top: 1rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    }

    .table-wrap {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        text-align: left;
        min-width: 860px;
        background-color: #ffffff;
    }

    thead th {
        padding: 0.8rem 1rem;
        font-size: 0.73rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #eef2f7;
        font-size: 0.875rem;
        color: var(--text-dark);
        vertical-align: middle;
        background: #ffffff;
    }

    tbody tr:hover td {
        background: #f8fafc;
    }

    .emp-cell {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .emp-row-photo {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        flex-shrink: 0;
    }

    .emp-name {
        font-weight: 700;
        font-size: 0.875rem;
        color: #0f172a;
    }

    .emp-role {
        font-size: 0.72rem;
        color: #64748b;
        margin-top: 0.1rem;
    }

    .time-in {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: 0.9rem;
        font-weight: 700;
        color: #15803d;
    }

    .time-out {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: 0.9rem;
        font-weight: 700;
        color: #334155;
    }

    .muted {
        color: #94a3b8;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.28rem 0.62rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .status-hadir {
        background-color: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    .status-terlambat {
        background-color: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }

    .status-tidak_hadir {
        background-color: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .status-izin {
        background-color: #dbeafe;
        color: #1e40af;
        border-color: #bfdbfe;
    }

    .status-sakit {
        background-color: #ede9fe;
        color: #5b21b6;
        border-color: #ddd6fe;
    }

    .pagination {
        margin-top: 1rem;
    }

    .pagination nav > div:first-child {
        display: none;
    }

    .pagination span,
    .pagination a {
        font-size: 0.82rem !important;
    }

    .empty-state {
        text-align: center;
        padding: 2.8rem 1rem;
        color: #64748b;
    }

    .empty-state i {
        font-size: 2.3rem;
        display: block;
        margin-bottom: 0.85rem;
        color: #94a3b8;
    }

    @media (max-width: 768px) {
        .table-card {
            padding: 0.65rem;
        }

        .riwayat-title {
            font-size: 1.15rem;
        }
    }
</style>
@endsection

@section('content')
<div class="riwayat-head">
    <h2 class="riwayat-title">Riwayat Absensi</h2>
    <p class="riwayat-subtitle">Histori lengkap absensi seluruh karyawan di cabang ini.</p>
</div>

<div class="table-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Total Kerja</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $r)
                    <tr>
                        <td>
                            <div class="emp-cell">
                                <img
                                    src="{{ asset($r->karyawan->foto_wajah ? $r->karyawan->foto_wajah : 'profiles/default.jpg') }}"
                                    alt="Foto"
                                    class="emp-row-photo"
                                    onerror="this.src='/profiles/default.jpg'"
                                >
                                <div>
                                    <div class="emp-name">{{ $r->karyawan->nama }}</div>
                                    <div class="emp-role">{{ $r->karyawan->jabatan ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: 600;">{{ $r->tanggal->translatedFormat('d F Y') }}</td>
                        <td>
                            <span class="time-in">{{ substr($r->jam_masuk, 0, 5) }}</span>
                        </td>
                        <td>
                            @if($r->jam_pulang)
                                <span class="time-out">{{ substr($r->jam_pulang, 0, 5) }}</span>
                            @else
                                <span class="muted">--:--</span>
                            @endif
                        </td>
                        <td>
                            @if($r->total_menit_kerja)
                                {{ intdiv($r->total_menit_kerja, 60) }}j {{ $r->total_menit_kerja % 60 }}m
                            @else
                                <span class="muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-status status-{{ $r->status }}">
                                {{ str_replace('_', ' ', $r->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fa-regular fa-calendar-times"></i>
                                Belum ada riwayat absensi untuk cabang ini.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($riwayat->hasPages())
        <div class="pagination">
            {{ $riwayat->links() }}
        </div>
    @endif
</div>
@endsection