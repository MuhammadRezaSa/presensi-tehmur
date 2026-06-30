@extends('layouts.karyawan')

@section('title', 'Riwayat Absensi Cabang')

@section('styles')
<style>
    .riwayat-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .table-card {
        background-color: #0f172a;
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 1.5rem;
        overflow-x: auto;
        margin-top: 1.5rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    thead th {
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--border-color);
    }

    tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid rgba(51, 65, 85, 0.5);
        font-size: 0.875rem;
        color: var(--text-dark);
        vertical-align: middle;
    }

    .emp-row-photo {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--border-color);
        margin-right: 0.5rem;
        vertical-align: middle;
    }

    .badge-status {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-hadir { background-color: rgba(16, 185, 129, 0.15); color: #10b981; }
    .status-terlambat { background-color: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .status-tidak_hadir { background-color: rgba(239, 68, 68, 0.15); color: #ef4444; }

    .pagination {
        display: flex;
        gap: 0.5rem;
        margin-top: 1.5rem;
        justify-content: center;
    }

    .pagination a, .pagination span {
        padding: 0.45rem 0.85rem;
        border-radius: 0.375rem;
        background-color: #1e293b;
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .pagination .active {
        background-color: var(--accent-green);
        color: #ffffff;
        border-color: var(--accent-green);
    }
</style>
@endsection

@section('content')
<div>
    <h2 class="riwayat-title">Riwayat Absensi</h2>
    <p style="color: var(--text-muted); font-size: 0.85rem;">Histori lengkap absensi seluruh karyawan di cabang ini.</p>
</div>

<div class="table-card">
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
                        <div style="display: flex; align-items: center;">
                            <img src="{{ asset($r->karyawan->foto_wajah ? $r->karyawan->foto_wajah : 'profiles/default.jpg') }}" alt="Foto" class="emp-row-photo" onerror="this.src='/profiles/default.jpg'">
                            <div>
                                <div style="font-weight: 700; font-size: 0.875rem;">{{ $r->karyawan->nama }}</div>
                                <div style="font-size: 0.7rem; color: var(--text-muted);">{{ $r->karyawan->jabatan ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight: 600;">{{ $r->tanggal->translatedFormat('d F Y') }}</td>
                    <td>
                        <span style="font-family: monospace; font-size: 0.9rem; font-weight: 700; color: #10b981;">{{ substr($r->jam_masuk, 0, 5) }}</span>
                    </td>
                    <td>
                        @if($r->jam_pulang)
                            <span style="font-family: monospace; font-size: 0.9rem; font-weight: 700; color: var(--text-muted);">{{ substr($r->jam_pulang, 0, 5) }}</span>
                        @else
                            <span style="color: var(--text-muted);">--:--</span>
                        @endif
                    </td>
                    <td>
                        @if($r->total_menit_kerja)
                            {{ intdiv($r->total_menit_kerja, 60) }}j {{ $r->total_menit_kerja % 60 }}m
                        @else
                            <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-status status-{{ $r->status }}">{{ str_replace('_', ' ', $r->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        <i class="fa-regular fa-calendar-times" style="font-size: 2.5rem; display: block; margin-bottom: 1rem;"></i>
                        Belum ada riwayat absensi untuk cabang ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($riwayat->hasPages())
        <div class="pagination">
            {{ $riwayat->links() }}
        </div>
    @endif
</div>
@endsection
