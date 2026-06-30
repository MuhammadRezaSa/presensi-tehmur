@extends('layouts.owner')

@section('title', 'Kelola Cabang')
@section('page-title', 'Kelola Cabang')

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
</style>
@endsection

@section('content')
<div class="action-header">
    <p style="color: var(--text-muted);">Daftar outlet cabang aktif Teh Mur di Kota Pekanbaru.</p>
    <a href="{{ route('owner.cabang.create') }}" class="btn-add">
        <i class="fa-solid fa-plus"></i> Tambah Cabang
    </a>
</div>

<div class="table-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Nama Cabang</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Alamat</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Koordinat (Lat, Lng)</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;">Radius Absen</th>
                    <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0; width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cabang as $c)
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;"><strong>{{ $c->nama_cabang }}</strong></td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9; color: var(--text-muted); max-width: 250px;">{{ $c->alamat }}</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9; font-family: monospace; font-size: 0.85rem;">
                            {{ $c->latitude }}, {{ $c->longitude }}
                        </td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">{{ $c->radius_meter }} Meter</td>
                        <td style="padding: 1rem; border-bottom: 1px solid #f1f5f9;">
                            <div class="action-group">
                                <a href="{{ route('owner.cabang.edit', $c->id) }}" class="btn-action btn-edit" title="Edit Cabang">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('owner.cabang.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini? Semua data karyawan di cabang ini juga akan ikut terpengaruh.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Hapus Cabang">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                            <i class="fa-regular fa-folder-open" style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;"></i>
                            Belum ada data cabang yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
