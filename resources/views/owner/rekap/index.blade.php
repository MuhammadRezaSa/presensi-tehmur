@extends('layouts.owner')

@section('title', 'Rekap Kehadiran')
@section('page-title', 'Rekap Kehadiran Karyawan')

@section('styles')
<style>
    .filter-card {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        margin-bottom: 2rem;
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        align-items: flex-end;
    }

    .form-group {
        margin-bottom: 0;
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
        padding: 0.6rem 0.85rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background-color: #f8fafc;
        outline: none;
        font-size: 0.9rem;
    }

    .btn-filter {
        padding: 0.65rem 1rem;
        background-color: var(--primary);
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-pdf {
        padding: 0.65rem 1rem;
        background-color: #b91c1c;
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-pdf:hover {
        background-color: #991b1b;
    }

    .table-card {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-hadir { background-color: #dcfce7; color: #15803d; }
    .badge-terlambat { background-color: #fef9c3; color: #a16207; }
</style>
@endsection

@section('content')
<!-- Card Filter -->
<div class="filter-card">
    <form action="{{ route('owner.rekap.index') }}" method="GET" class="filter-form">
        <div class="form-group">
            <label for="tanggal_mulai">Tanggal Mulai</label>
            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="{{ $tglMulai }}">
        </div>

        <div class="form-group">
            <label for="tanggal_selesai">Tanggal Selesai</label>
            <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" value="{{ $tglSelesai }}">
        </div>

        <div class="form-group">
            <label for="cabang_id">Cabang</label>
            <select id="cabang_id" name="cabang_id" class="form-control">
                <option value="">-- Semua Cabang --</option>
                @foreach($cabang as $c)
                    <option value="{{ $c->id }}" {{ $cabangId == $c->id ? 'selected' : '' }}>{{ $c->nama_cabang }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn-filter">
                <i class="fa-solid fa-magnifying-glass"></i> Filter
            </button>
            <a href="{{ route('owner.rekap.pdf', ['tanggal_mulai' => $tglMulai, 'tanggal_selesai' => $tglSelesai, 'cabang_id' => $cabangId]) }}" class="btn-pdf">
                <i class="fa-solid fa-file-pdf"></i> Unduh PDF
            </a>
        </div>
    </form>
</div>

<!-- Card Tabel Log -->
<div class="table-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Tanggal</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Karyawan</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Cabang</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Masuk</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Pulang</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Durasi Kerja</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presensi as $p)
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;"><strong>{{ $p->tanggal->translatedFormat('d M Y') }}</strong></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">
                            <strong>{{ $p->karyawan->name }}</strong>
                            <div style="font-size: 0.75rem; color: var(--text-muted)">{{ $p->karyawan->jabatan }}</div>
                        </td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">{{ $p->cabang->nama_cabang }}</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">
                            {{ substr($p->jam_masuk, 0, 5) }}
                            <div style="font-size: 0.7rem; color: var(--accent-green);"><i class="fa-solid fa-location-dot"></i> GPS Valid</div>
                        </td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">
                            {{ $p->jam_pulang ? substr($p->jam_pulang, 0, 5) : '-' }}
                        </td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">
                            {{ $p->total_jam_kerja }}
                            @if($p->menit_lembur > 0)
                                <div style="font-size: 0.7rem; color: var(--accent-green);">Lembur: {{ $p->menit_lembur }}m</div>
                            @endif
                        </td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">
                            <span class="badge-status badge-{{ $p->status }}">
                                {{ $p->status == 'hadir' ? 'Tepat Waktu' : 'Terlambat (' . $p->menit_terlambat . 'm)' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                            <i class="fa-regular fa-folder-open" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
                            Tidak ditemukan log presensi pada filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
