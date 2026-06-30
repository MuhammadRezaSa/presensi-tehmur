@extends('layouts.owner')

@section('title', 'Tambah Karyawan')
@section('page-title', 'Pendaftaran Karyawan Baru')

@section('styles')
<style>
    .form-card {
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        max-width: 800px;
    }

    .form-section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
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
    <a href="{{ route('owner.karyawan.index') }}" style="text-decoration: none; color: var(--primary); font-weight: 600; font-size: 0.9rem;">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Karyawan
    </a>
</div>

@if($errors->any())
    <div style="background-color: #fef2f2; border: 1px solid #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-size: 0.9rem;">
        <strong>Perhatian!</strong> Ada kesalahan pada pengisian form:
        <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-card">
    <form action="{{ route('owner.karyawan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- SEKSI 1: DATA DIRI KARYAWAN -->
        <div class="form-section-title">
            <i class="fa-solid fa-user"></i> Data Diri Karyawan
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('nama') }}" required>
            </div>
            
            <div class="form-group">
                <label for="email">Alamat Email (Opsional)</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="budi@tehmur.com" value="{{ old('email') }}">
            </div>
        </div>

        <!-- SEKSI 2: DETAIL PROFIL & PEKERJAAN -->
        <div class="form-section-title">
            <i class="fa-solid fa-briefcase"></i> Detail Profil & Penugasan
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label for="nik">NIK Karyawan</label>
                <input type="text" id="nik" name="nik" class="form-control" placeholder="Nomor Induk Karyawan" value="{{ old('nik') }}">
            </div>
            
            <div class="form-group">
                <label for="no_telepon">Nomor Telepon</label>
                <input type="text" id="no_telepon" name="no_telepon" class="form-control" placeholder="08xxxx" value="{{ old('no_telepon') }}">
            </div>

            <div class="form-group">
                <label for="cabang_id">Penempatan Cabang</label>
                <select id="cabang_id" name="cabang_id" class="form-control" required>
                    <option value="" disabled selected>-- Pilih Cabang --</option>
                    @foreach($cabangs as $c)
                        <option value="{{ $c->id }}" {{ old('cabang_id') == $c->id ? 'selected' : '' }}>{{ $c->nama_cabang }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="jabatan">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" class="form-control" placeholder="Contoh: Barista, Kasir" value="{{ old('jabatan') }}">
            </div>
        </div>

        <!-- SEKSI 3: SHIFT & GAJI -->
        <div class="form-section-title">
            <i class="fa-solid fa-clock"></i> Shift & Parameter Gaji
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label for="shift">Shift</label>
                <select id="shift" name="shift" class="form-control" required>
                    <option value="pagi" selected>Pagi</option>
                    <option value="siang">Siang</option>
                    <option value="malam">Malam</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="jam_masuk_shift">Jam Masuk</label>
                    <input type="time" id="jam_masuk_shift" name="jam_masuk_shift" class="form-control" value="08:00" required>
                </div>
                <div class="form-group">
                    <label for="jam_pulang_shift">Jam Pulang</label>
                    <input type="time" id="jam_pulang_shift" name="jam_pulang_shift" class="form-control" value="17:00" required>
                </div>
            </div>

            <div class="form-group">
                <label for="gaji_pokok">Gaji Pokok (Rp)</label>
                <input type="number" id="gaji_pokok" name="gaji_pokok" class="form-control" value="2000000" min="0" required>
            </div>

            <div class="form-group">
                <label for="upah_lembur_per_jam">Upah Lembur per Jam (Rp)</label>
                <input type="number" id="upah_lembur_per_jam" name="upah_lembur_per_jam" class="form-control" value="15000" min="0" required>
            </div>
        </div>

        <!-- SEKSI 4: VERIFIKASI WAJAH -->
        <div class="form-section-title">
            <i class="fa-solid fa-face-smile"></i> Foto Verifikasi Wajah (Face Recognition)
        </div>
        <div class="form-group">
            <label for="foto_wajah">Pilih Foto Wajah</label>
            <input type="file" id="foto_wajah" name="foto_wajah" class="form-control" accept="image/*">
            <small class="help-text">Unggah foto wajah karyawan yang jelas menghadap ke depan. Foto ini akan digunakan sebagai data acuan verifikasi absensi wajah (Face Recognition).</small>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-submit">Daftarkan Karyawan</button>
            <a href="{{ route('owner.karyawan.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>
@endsection
