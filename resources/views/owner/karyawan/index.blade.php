@extends('layouts.owner')

@section('title', 'Kelola Karyawan')
@section('page-title', 'Kelola Karyawan')

@section('styles')
<style>
    .action-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .btn-add {
        padding: 0.6rem 1.25rem;
        background-color: var(--accent-green);
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.2s;
        box-shadow: 0 4px 6px -1px rgba(21, 128, 61, 0.2);
    }

    .btn-add:hover {
        background-color: #166534;
    }

    .table-card {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }

    .avatar-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .emp-photo {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 0.375rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .btn-edit:hover {
        background-color: #3b82f6;
        color: #ffffff;
    }

    .btn-delete {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .btn-delete:hover {
        background-color: #ef4444;
        color: #ffffff;
    }

    .action-group {
        display: flex;
        gap: 0.5rem;
    }

    .badge-shift {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .shift-pagi { background-color: #e0f2fe; color: #0369a1; }
    .shift-siang { background-color: #fef3c7; color: #d97706; }
    .shift-malam { background-color: #f3e8ff; color: #7e22ce; }
</style>
@endsection

@section('content')
<div class="action-header">
    <p style="color: var(--text-muted);">Kelola data akun, informasi shift, gaji, dan foto wajah untuk verifikasi presensi.</p>
    <a href="{{ route('owner.karyawan.create') }}" class="btn-add">
        <i class="fa-solid fa-user-plus"></i> Tambah Karyawan
    </a>
</div>

<div class="table-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Karyawan</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">NIK</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Cabang</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Shift & Jam Kerja</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Gaji Pokok</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0; width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawan as $k)
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">
                            <div class="avatar-cell">
                                <img src="{{ asset($k->foto_wajah ? $k->foto_wajah : 'profiles/default.jpg') }}" alt="Wajah" class="emp-photo" onerror="this.src='/profiles/default.jpg'">
                                <div>
                                    <strong>{{ $k->nama }}</strong>
                                    <div style="font-size: 0.75rem; color: var(--text-muted)">{{ $k->jabatan ?? '-' }} | {{ $k->email ?? 'Tidak ada email' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9; font-family: monospace;">{{ $k->nik ?? '-' }}</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">{{ $k->cabang->nama_cabang }}</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">
                            <span class="badge-shift shift-{{ $k->shift }}">{{ $k->shift }}</span>
                            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                                {{ substr($k->jam_masuk_shift, 0, 5) }} - {{ substr($k->jam_pulang_shift, 0, 5) }}
                            </div>
                        </td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">Rp {{ number_format($k->gaji_pokok, 0, ',', '.') }}</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">
                            <div class="action-group">
                                <a href="{{ route('owner.karyawan.edit', $k->id) }}" class="btn-action btn-edit" title="Edit Karyawan">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('owner.karyawan.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan ini? Data presensi dan riwayat gaji mereka akan terhapus secara permanen.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Hapus Karyawan">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                            <i class="fa-regular fa-folder-open" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
                            Belum ada karyawan yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
