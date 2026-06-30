@extends('layouts.karyawan')

@section('title', 'Kiosk Absensi')

@section('styles')
<style>
    /* Premium Glassmorphism Grid */
    .kiosk-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .kiosk-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 1.25rem;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .kiosk-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px -10px rgba(16, 185, 129, 0.3);
        border-color: rgba(16, 185, 129, 0.4);
    }

    .card-photo-wrapper {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 1rem;
    }

    .card-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--border-color);
        transition: border-color 0.3s;
    }

    .kiosk-card:hover .card-photo {
        border-color: var(--accent-green);
    }

    .card-status-dot {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 2px solid var(--card-bg);
    }

    .dot-belum { background-color: #ef4444; }
    .dot-masuk { background-color: #f59e0b; }
    .dot-pulang { background-color: var(--accent-green); }

    .card-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-role {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.15rem;
    }

    .card-shift {
        display: inline-block;
        font-size: 0.7rem;
        padding: 0.15rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 0.5rem;
        background-color: rgba(255, 255, 255, 0.05);
        color: var(--text-muted);
        border: 1px solid var(--border-color);
    }

    /* Kiosk Action Bar */
    .kiosk-action-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .kiosk-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .btn-register-emp {
        padding: 0.75rem 1.5rem;
        background-color: var(--accent-green);
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .btn-register-emp:hover {
        background-color: var(--accent-green-hover);
        transform: translateY(-2px);
    }

    /* Modal Layout (Premium Dark Blur) */
    .kiosk-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(2, 6, 23, 0.8);
        backdrop-filter: blur(12px);
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
        padding: 1rem;
    }

    .kiosk-modal.show {
        display: flex;
        opacity: 1;
    }

    .modal-content {
        background-color: #0f172a;
        border: 1px solid var(--border-color);
        border-radius: 1.25rem;
        padding: 2rem;
        width: 100%;
        max-width: 500px;
        position: relative;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .kiosk-modal.show .modal-content {
        transform: scale(1);
    }

    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 1.25rem;
        cursor: pointer;
        transition: color 0.2s;
    }

    .modal-close:hover {
        color: #ef4444;
    }

    .modal-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Form Fields inside Modal */
    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.35rem;
    }

    .form-control {
        width: 100%;
        background-color: rgba(255,255,255,0.03);
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        padding: 0.65rem 0.85rem;
        color: var(--text-dark);
        outline: none;
        font-size: 0.9rem;
    }

    .form-control:focus {
        border-color: var(--accent-green);
        background-color: rgba(255,255,255,0.05);
    }

    .btn-action-modal {
        width: 100%;
        padding: 0.75rem;
        background-color: var(--accent-green);
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        margin-top: 1rem;
        transition: background-color 0.2s;
    }

    .btn-action-modal:hover {
        background-color: var(--accent-green-hover);
    }

    /* Absen Action Grid */
    .absen-btn-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-absen-option {
        padding: 1rem;
        border-radius: 0.75rem;
        border: 1px solid var(--border-color);
        background-color: rgba(255,255,255,0.02);
        color: var(--text-dark);
        font-weight: 700;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .btn-absen-option:hover:not(:disabled) {
        border-color: var(--accent-green);
        background-color: rgba(16, 185, 129, 0.05);
        transform: translateY(-2px);
    }

    .btn-absen-option:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .btn-absen-option i {
        font-size: 1.5rem;
    }

    .option-masuk i { color: #f59e0b; }
    .option-pulang i { color: var(--accent-green); }

    /* Camera CSS */
    .camera-box {
        width: 100%;
        aspect-ratio: 3/4;
        max-width: 320px;
        background-color: #000;
        border-radius: 0.75rem;
        overflow: hidden;
        position: relative;
        margin: 1.5rem auto 0;
        border: 2px solid var(--border-color);
        display: none;
    }

    #webcam {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }

    #canvas-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transform: scaleX(-1);
        z-index: 10;
        pointer-events: none;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.7rem;
        font-weight: 700;
        margin-top: 0.5rem;
        display: inline-block;
    }

    .badge-belum { background-color: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .badge-masuk { background-color: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
    .badge-pulang { background-color: rgba(16, 185, 129, 0.15); color: var(--accent-green); border: 1px solid rgba(16, 185, 129, 0.2); }
</style>
@endsection

@section('content')
<div class="kiosk-action-bar">
    <div>
        <h2 class="kiosk-title">Pilih Nama Anda</h2>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.25rem;">Sentuh kartu nama Anda untuk melakukan presensi.</p>
    </div>
    <div>
        <button class="btn-register-emp" id="open-pin-btn">
            <i class="fa-solid fa-user-plus"></i> Tambah Karyawan Baru
        </button>
    </div>
</div>

<!-- Status Global GPS Kiosk -->
<div id="gps-global-warning" style="display: none; background-color: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600; text-align: center;">
    <i class="fa-solid fa-triangle-exclamation" style="margin-right: 0.5rem;"></i> GPS kiosk tidak terdeteksi. Silakan izinkan akses lokasi pada browser untuk dapat menggunakan kiosk absensi.
</div>

<!-- Grid Karyawan -->
<div class="kiosk-grid">
    @forelse($karyawanList as $k)
        @php
            $presensi = $presensiToday->get($k->id);
            $statusClass = 'belum';
            $statusText = 'Belum Absen';
            if ($presensi) {
                if ($presensi->jam_pulang) {
                    $statusClass = 'pulang';
                    $statusText = 'Selesai (' . substr($presensi->jam_pulang, 0, 5) . ')';
                } else {
                    $statusClass = 'masuk';
                    $statusText = 'Masuk (' . substr($presensi->jam_masuk, 0, 5) . ')';
                }
            }
        @endphp
        <div class="kiosk-card" onclick="openAbsenModal({{ json_encode($k) }}, '{{ $statusClass }}', '{{ $presensi ? 'true' : 'false' }}', '{{ $presensi && $presensi->jam_pulang ? 'true' : 'false' }}')">
            <div class="card-photo-wrapper">
                <img src="{{ asset($k->foto_wajah ? $k->foto_wajah : 'profiles/default.jpg') }}" alt="{{ $k->nama }}" class="card-photo" onerror="this.src='/profiles/default.jpg'">
                <div class="card-status-dot dot-{{ $statusClass }}"></div>
            </div>
            <div class="card-name">{{ $k->nama }}</div>
            <div class="card-role">{{ $k->jabatan ?? 'Barista' }}</div>
            <div>
                <span class="status-badge badge-{{ $statusClass }}">{{ $statusText }}</span>
            </div>
            <div>
                <span class="card-shift">Shift: {{ $k->shift }}</span>
            </div>
        </div>
    @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 4rem; color: var(--text-muted);">
            <i class="fa-regular fa-folder-open" style="font-size: 3rem; display: block; margin-bottom: 1rem;"></i>
            Belum ada karyawan terdaftar di cabang ini. Klik tombol di atas untuk mendaftarkan karyawan.
        </div>
    @endforelse
</div>

<!-- MODAL 1: PIN VERIFIKASI -->
<div class="kiosk-modal" id="pin-modal">
    <div class="modal-content" style="max-width: 400px;">
        <button class="modal-close" onclick="closeModal('pin-modal')"><i class="fa-solid fa-xmark"></i></button>
        <div class="modal-title">
            <i class="fa-solid fa-lock" style="color: var(--accent-green);"></i> Otorisasi Kiosk
        </div>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">Masukkan PIN Kiosk Cabang Anda untuk mendaftarkan karyawan baru.</p>
        
        <div class="form-group">
            <label for="kiosk_pin">PIN Kiosk (6 Angka)</label>
            <input type="password" id="kiosk_pin" maxlength="6" pattern="\d{6}" class="form-control" style="text-align: center; font-size: 1.5rem; letter-spacing: 0.5rem;" placeholder="******">
        </div>
        <button class="btn-action-modal" id="submit-pin-btn">Verifikasi PIN</button>
    </div>
</div>

<!-- MODAL 2: TAMBAH KARYAWAN -->
<div class="kiosk-modal" id="register-modal">
    <div class="modal-content" style="max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <button class="modal-close" onclick="closeModal('register-modal')"><i class="fa-solid fa-xmark"></i></button>
        <div class="modal-title">
            <i class="fa-solid fa-user-plus" style="color: var(--accent-green);"></i> Daftarkan Karyawan Baru
        </div>
        <form id="register-employee-form" enctype="multipart/form-data">
            @csrf
            <!-- Hidden PIN for authentication on backend -->
            <input type="hidden" name="pin" id="hidden-pin">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="new_nama">Nama Lengkap</label>
                    <input type="text" id="new_nama" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" required>
                </div>
                <div class="form-group">
                    <label for="new_nik">NIK Karyawan (Opsional)</label>
                    <input type="text" id="new_nik" name="nik" class="form-control" placeholder="Nomor Induk Karyawan">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="new_email">Alamat Email (Opsional)</label>
                    <input type="email" id="new_email" name="email" class="form-control" placeholder="budi@tehmur.com">
                </div>
                <div class="form-group">
                    <label for="new_no_telepon">Nomor Telepon</label>
                    <input type="text" id="new_no_telepon" name="no_telepon" class="form-control" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="new_jabatan">Jabatan</label>
                    <input type="text" id="new_jabatan" name="jabatan" class="form-control" placeholder="Contoh: Barista, Kasir" value="Barista">
                </div>
                <div class="form-group">
                    <label for="new_shift">Shift Kerja</label>
                    <select id="new_shift" name="shift" class="form-control">
                        <option value="pagi" selected>Pagi</option>
                        <option value="siang">Siang</option>
                        <option value="malam">Malam</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="new_jam_masuk">Jam Masuk Shift</label>
                    <input type="time" id="new_jam_masuk" name="jam_masuk_shift" class="form-control" value="08:00" required>
                </div>
                <div class="form-group">
                    <label for="new_jam_pulang">Jam Pulang Shift</label>
                    <input type="time" id="new_jam_pulang" name="jam_pulang_shift" class="form-control" value="17:00" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="new_gaji">Gaji Pokok (Rp)</label>
                    <input type="number" id="new_gaji" name="gaji_pokok" class="form-control" value="2000000" min="0" required>
                </div>
                <div class="form-group">
                    <label for="new_lembur">Upah Lembur per Jam (Rp)</label>
                    <input type="number" id="new_lembur" name="upah_lembur_per_jam" class="form-control" value="15000" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label for="new_foto">Pilih Foto Wajah Acuan (Sangat Disarankan Foto Jelas)</label>
                <input type="file" id="new_foto" name="foto_wajah" class="form-control" accept="image/*" required>
                <small style="font-size: 0.7rem; color: var(--text-muted); display: block; margin-top: 0.25rem;">Foto ini akan digunakan sebagai patokan kecocokan wajah saat absensi.</small>
            </div>

            <button type="submit" class="btn-action-modal" id="submit-register-btn" style="margin-top: 1.5rem;">Daftarkan Staff</button>
        </form>
    </div>
</div>

<!-- MODAL 3: MENU ABSENSI & VERIFIKASI WAJAH -->
<div class="kiosk-modal" id="absen-modal">
    <div class="modal-content" style="max-width: 450px; text-align: center;">
        <button class="modal-close" id="btn-close-absen" onclick="closeAbsenModalClean()"><i class="fa-solid fa-xmark"></i></button>
        
        <img id="absen-emp-photo" src="" alt="Wajah" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--border-color); margin-bottom: 0.75rem;">
        <h3 id="absen-emp-name" style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; color: var(--text-dark);">Nama Karyawan</h3>
        <p id="absen-emp-role" style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem;">Jabatan</p>

        <!-- Pilihan Masuk / Pulang -->
        <div id="absen-options-box">
            <h4 style="font-size: 0.9rem; color: var(--text-dark); text-align: left; margin-bottom: 0.75rem;">Sentuh Pilihan Presensi:</h4>
            <div class="absen-btn-grid">
                <button class="btn-absen-option option-masuk" id="btn-option-masuk" onclick="selectAbsenType('masuk')">
                    <i class="fa-solid fa-circle-arrow-right"></i>
                    <span>Presensi Masuk</span>
                </button>
                <button class="btn-absen-option option-pulang" id="btn-option-pulang" onclick="selectAbsenType('pulang')">
                    <i class="fa-solid fa-circle-arrow-left"></i>
                    <span>Presensi Pulang</span>
                </button>
            </div>
        </div>

        <!-- Tampilan Kamera Real-Time -->
        <div id="absen-camera-box" style="display: none; margin-top: 1.5rem;">
            <h4 id="camera-section-title" style="font-size: 0.95rem; color: var(--accent-green); margin-bottom: 0.5rem; text-align: center;">Mengaktifkan Kamera Absensi...</h4>
            
            <div class="camera-box" id="webcam-container" style="display: block;">
                <video id="webcam" autoplay playsinline></video>
                <canvas id="canvas-overlay"></canvas>
            </div>

            <div style="background-color: rgba(255,255,255,0.02); border: 1px solid var(--border-color); padding: 0.75rem; border-radius: 0.5rem; margin-top: 1rem; font-size: 0.8rem; color: var(--text-muted); text-align: left;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                    <span>Sistem Pencocokan Wajah:</span>
                    <strong id="face-match-status" style="color: #ef4444;"><i class="fa-solid fa-spinner fa-spin"></i> Loading AI...</strong>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <span>GPS Kiosk Terminal:</span>
                    <strong id="gps-match-status" style="color: var(--accent-green);">Mencari...</strong>
                </div>
            </div>

            <button class="btn-action-modal" id="btn-submit-absen" style="background-color: #ef4444;" disabled>Posisikan Wajah Anda</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Load face-api.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

<script>
    // Variables
    let latitude = null;
    let longitude = null;
    let currentEmployee = null;
    let selectedAbsenType = null;
    let modelsLoaded = false;
    let faceMatcher = null;
    let webcamStream = null;
    let faceDetectionInterval = null;
    let isSubmitting = false;

    // GPS Geolocation check on Load
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                latitude = pos.coords.latitude;
                longitude = pos.coords.longitude;
                document.getElementById('gps-global-warning').style.display = 'none';
                console.log(`GPS OK: ${latitude}, ${longitude}`);
            },
            (err) => {
                document.getElementById('gps-global-warning').style.display = 'block';
                console.error(err);
            },
            { enableHighAccuracy: true }
        );
    } else {
        document.getElementById('gps-global-warning').style.display = 'block';
    }

    // Modal Helpers
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('show');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('show');
    }

    // PIN Otorisasi
    document.getElementById('open-pin-btn').addEventListener('click', () => {
        document.getElementById('kiosk_pin').value = '';
        openModal('pin-modal');
    });

    document.getElementById('submit-pin-btn').addEventListener('click', () => {
        const pin = document.getElementById('kiosk_pin').value;
        if (pin.length !== 6 || isNaN(pin)) {
            alert('PIN Kiosk harus berupa 6 digit angka.');
            return;
        }

        // Simpan pin di input form registrasi dan buka form jika pin diinput
        document.getElementById('hidden-pin').value = pin;
        closeModal('pin-modal');
        openModal('register-modal');
    });

    // Pendaftaran Karyawan AJAX
    document.getElementById('register-employee-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submit-register-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Mendaftarkan...';

        const formData = new FormData(this);

        fetch("{{ route('karyawan.tambah-karyawan') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Pendaftaran Gagal: ' + data.message);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Daftarkan Staff';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan server!');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Daftarkan Staff';
        });
    });

    // Absen Modal Logic
    function openAbsenModal(employee, statusClass, hasMasuk, hasPulang) {
        currentEmployee = employee;
        selectedAbsenType = null;
        
        document.getElementById('absen-emp-photo').src = employee.foto_wajah ? '/' + employee.foto_wajah : '/profiles/default.jpg';
        document.getElementById('absen-emp-name').textContent = employee.nama;
        document.getElementById('absen-emp-role').textContent = (employee.jabatan || 'Barista') + ' | Shift ' + employee.shift;

        const btnMasuk = document.getElementById('btn-option-masuk');
        const btnPulang = document.getElementById('btn-option-pulang');

        // Reset display
        document.getElementById('absen-options-box').style.display = 'block';
        document.getElementById('absen-camera-box').style.display = 'none';

        // Atur tombol masuk/pulang berdasarkan status
        btnMasuk.disabled = hasMasuk === 'true';
        btnPulang.disabled = hasMasuk === 'false' || hasPulang === 'true';

        openModal('absen-modal');
    }

    function closeAbsenModalClean() {
        closeModal('absen-modal');
        stopCamera();
    }

    function stopCamera() {
        if (webcamStream) {
            webcamStream.getTracks().forEach(track => track.stop());
            webcamStream = null;
        }
        if (faceDetectionInterval) {
            clearInterval(faceDetectionInterval);
            faceDetectionInterval = null;
        }
        
        const video = document.getElementById('webcam');
        if (video) video.srcObject = null;
        
        const canvas = document.getElementById('canvas-overlay');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        
        isSubmitting = false;
    }

    // Memulai Proses Pengenalan Wajah & GPS Absen
    async function selectAbsenType(type) {
        selectedAbsenType = type;
        
        document.getElementById('absen-options-box').style.display = 'none';
        document.getElementById('absen-camera-box').style.display = 'block';
        document.getElementById('camera-section-title').textContent = `Kamera: Absen ${type.toUpperCase()}`;

        // Reset statuses
        const faceStatusEl = document.getElementById('face-match-status');
        faceStatusEl.textContent = 'Memuat AI...';
        faceStatusEl.style.color = '#f59e0b';
        
        const gpsStatusEl = document.getElementById('gps-match-status');
        if (latitude !== null) {
            gpsStatusEl.textContent = `GPS Aktif (${latitude.toFixed(4)}, ${longitude.toFixed(4)})`;
            gpsStatusEl.style.color = 'var(--accent-green)';
        } else {
            gpsStatusEl.textContent = 'Menunggu GPS...';
            gpsStatusEl.style.color = '#ef4444';
        }

        const submitAbsenBtn = document.getElementById('btn-submit-absen');
        submitAbsenBtn.disabled = true;
        submitAbsenBtn.textContent = 'Menginisialisasi Kamera & AI...';
        submitAbsenBtn.style.backgroundColor = '#475569';

        await initFaceApiAndCamera();
    }

    // Muat face-api.js model & inisiasi kamera
    async function initFaceApiAndCamera() {
        try {
            const faceStatusEl = document.getElementById('face-match-status');
            const submitAbsenBtn = document.getElementById('btn-submit-absen');
            
            // Muat model jika belum dimuat
            if (!modelsLoaded) {
                const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights/';
                await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
                await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                modelsLoaded = true;
            }

            faceStatusEl.textContent = 'AI Siap. Menganalisis Foto Karyawan...';
            
            // Buat Deskriptor Wajah Karyawan
            const referenceImage = await faceapi.fetchImage('/' + currentEmployee.foto_wajah);
            const detections = await faceapi.detectSingleFace(referenceImage)
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detections) {
                faceStatusEl.textContent = 'Gagal! Foto terdaftar tidak jelas.';
                faceStatusEl.style.color = '#ef4444';
                submitAbsenBtn.textContent = 'Foto Profil Bermasalah';
                return;
            }

            faceMatcher = new faceapi.FaceMatcher(detections.descriptor, 0.5); // Threshold 0.5

            faceStatusEl.textContent = 'AI Siap. Membuka Kamera...';
            
            // Jalankan Webcam
            startWebcam();
        } catch (err) {
            console.error(err);
            document.getElementById('face-match-status').textContent = 'AI Error: ' + err.message;
            document.getElementById('face-match-status').style.color = '#ef4444';
        }
    }

    function startWebcam() {
        navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480, facingMode: 'user' } })
            .then(stream => {
                webcamStream = stream;
                const video = document.getElementById('webcam');
                video.srcObject = stream;
                video.addEventListener('play', onPlayDetectFaces);
            })
            .catch(err => {
                console.error("Gagal membuka kamera: ", err);
                alert("Harap berikan izin akses kamera pada terminal kiosk.");
            });
    }

    // Melakukan Deteksi Wajah real-time saat video webcam menyala
    async function onPlayDetectFaces() {
        const video = document.getElementById('webcam');
        const overlayCanvas = document.getElementById('canvas-overlay');
        const faceStatusEl = document.getElementById('face-match-status');
        const submitAbsenBtn = document.getElementById('btn-submit-absen');

        const displaySize = { width: video.videoWidth || 640, height: video.videoHeight || 480 };
        faceapi.matchDimensions(overlayCanvas, displaySize);

        faceStatusEl.textContent = 'Mendeteksi Wajah Anda...';
        faceStatusEl.style.color = '#f59e0b';

        faceDetectionInterval = setInterval(async () => {
            if (!modelsLoaded || isSubmitting) return;

            const detections = await faceapi.detectSingleFace(video, new faceapi.SsdMobilenetv1Options())
                .withFaceLandmarks()
                .withFaceDescriptor();

            // Clear canvas overlay
            const ctx = overlayCanvas.getContext('2d');
            ctx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);

            if (detections) {
                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                
                // Bandingkan wajah
                const result = faceMatcher.findBestMatch(resizedDetections.descriptor);
                const isMatch = result.label !== 'unknown';
                
                // Draw Box
                const box = resizedDetections.detection.box;
                ctx.strokeStyle = isMatch ? '#10b981' : '#ef4444';
                ctx.lineWidth = 4;
                ctx.strokeRect(box.x, box.y, box.width, box.height);
                
                // Draw Text
                ctx.fillStyle = isMatch ? '#10b981' : '#ef4444';
                ctx.font = '16px Inter';
                ctx.fillText(isMatch ? 'Cocok (Karyawan Terverifikasi)' : 'Wajah Tidak Dikenali', box.x, box.y - 10);

                if (isMatch) {
                    faceStatusEl.textContent = 'Wajah Cocok 100%';
                    faceStatusEl.style.color = 'var(--accent-green)';
                    
                    if (latitude !== null) {
                        submitAbsenBtn.disabled = false;
                        submitAbsenBtn.textContent = `Tekan untuk Absen ${selectedAbsenType.toUpperCase()}`;
                        submitAbsenBtn.style.backgroundColor = 'var(--accent-green)';
                    } else {
                        submitAbsenBtn.disabled = true;
                        submitAbsenBtn.textContent = 'Menunggu GPS...';
                        submitAbsenBtn.style.backgroundColor = '#475569';
                    }
                } else {
                    faceStatusEl.textContent = 'Wajah Tidak Sesuai!';
                    faceStatusEl.style.color = '#ef4444';
                    submitAbsenBtn.disabled = true;
                    submitAbsenBtn.textContent = 'Verifikasi Wajah Gagal';
                    submitAbsenBtn.style.backgroundColor = '#ef4444';
                }
            } else {
                faceStatusEl.textContent = 'Posisikan wajah Anda di depan kamera';
                faceStatusEl.style.color = '#64748b';
                submitAbsenBtn.disabled = true;
                submitAbsenBtn.textContent = 'Wajah Tidak Terdeteksi';
                submitAbsenBtn.style.backgroundColor = '#475569';
            }
        }, 600);
    }

    // Submit Absensi AJAX
    document.getElementById('btn-submit-absen').addEventListener('click', () => {
        if (isSubmitting || latitude === null || !currentEmployee || !selectedAbsenType) return;
        isSubmitting = true;

        const submitAbsenBtn = document.getElementById('btn-submit-absen');
        submitAbsenBtn.disabled = true;
        submitAbsenBtn.textContent = 'Mengirim absensi...';

        const video = document.getElementById('webcam');

        // Ambil frame gambar saat ini dari video webcam
        const canvasTemp = document.createElement('canvas');
        canvasTemp.width = video.videoWidth;
        canvasTemp.height = video.videoHeight;
        const ctxTemp = canvasTemp.getContext('2d');
        // Balikkan cermin kembali
        ctxTemp.translate(canvasTemp.width, 0);
        ctxTemp.scale(-1, 1);
        ctxTemp.drawImage(video, 0, 0, canvasTemp.width, canvasTemp.height);
        const dataUrl = canvasTemp.toDataURL('image/jpeg');

        // Kirim absen
        const postUrl = selectedAbsenType === 'masuk' ? "{{ route('karyawan.presensi.masuk') }}" : "{{ route('karyawan.presensi.pulang') }}";

        fetch(postUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                karyawan_id: currentEmployee.id,
                image: dataUrl,
                lat: latitude,
                lng: longitude
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Absen Gagal: ' + data.message);
                isSubmitting = false;
                submitAbsenBtn.disabled = false;
                submitAbsenBtn.textContent = `Coba Lagi Absen ${selectedAbsenType.toUpperCase()}`;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Koneksi server terputus.');
            isSubmitting = false;
            submitAbsenBtn.disabled = false;
        });
    });
</script>
@endsection
