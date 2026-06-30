@extends('layouts.owner')

@section('title', 'Edit Cabang')
@section('page-title', 'Edit Data Cabang')

@section('styles')
<style>
    .form-card {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        max-width: 600px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background-color: #f8fafc;
        outline: none;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: var(--primary);
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(0, 40, 85, 0.1);
    }

    .btn-submit {
        padding: 0.75rem 1.5rem;
        background-color: var(--primary);
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-submit:hover {
        background-color: #001d3d;
    }

    .btn-cancel {
        padding: 0.75rem 1.5rem;
        background-color: #e2e8f0;
        color: #475569;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: background-color 0.2s;
        display: inline-block;
    }

    .btn-cancel:hover {
        background-color: #cbd5e1;
    }

    .help-text {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
        display: block;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('owner.cabang.index') }}" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 0.9rem;">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Cabang
    </a>
</div>

<div class="form-card">
    <form action="{{ route('owner.cabang.update', $cabang->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama_cabang">Nama Cabang</label>
            <input type="text" id="nama_cabang" name="nama_cabang" class="form-control" value="{{ $cabang->nama_cabang }}" required>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat Lengkap</label>
            <textarea id="alamat" name="alamat" class="form-control" rows="3" required>{{ $cabang->alamat }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="number" step="any" id="latitude" name="latitude" class="form-control" value="{{ $cabang->latitude }}" required>
            </div>
            
            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="number" step="any" id="longitude" name="longitude" class="form-control" value="{{ $cabang->longitude }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="radius_meter">Radius Validasi Absen (Meter)</label>
            <input type="number" id="radius_meter" name="radius_meter" class="form-control" value="{{ $cabang->radius_meter }}" min="10" required>
            <small class="help-text">Radius (jarak maksimal) dalam meter dari koordinat cabang yang diperbolehkan untuk absensi karyawan.</small>
        </div>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 2rem 0;">
        <h4 style="margin-bottom: 1rem; color: var(--primary); font-size: 1rem;">Akun & Keamanan Kiosk</h4>

        <div class="form-group">
            <label for="email">Email Login Cabang</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $cabang->user?->email }}" required>
            <small class="help-text">Digunakan oleh cabang untuk login ke terminal kiosk.</small>
        </div>

        <div class="form-group">
            <label for="password">Password Login Cabang (Kosongkan jika tidak diubah)</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password baru jika ingin mengubah...">
        </div>

        <div class="form-group">
            <label for="pin_tambah_karyawan">PIN Kiosk (Untuk Tambah Karyawan)</label>
            <input type="text" maxlength="6" pattern="\d{6}" id="pin_tambah_karyawan" name="pin_tambah_karyawan" class="form-control" value="{{ $cabang->pin_tambah_karyawan }}" required>
            <small class="help-text">PIN 6-digit angka untuk membuka akses pendaftaran karyawan baru di terminal kiosk cabang.</small>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
            <a href="{{ route('owner.cabang.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection
