@extends('layouts.karyawan')

@section('title', 'Ambil Presensi')

@section('styles')
<style>
    .presence-shell {
        max-width: 640px;
        margin: 0 auto;
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        padding: 1.25rem;
    }

    .presence-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.25rem;
        text-align: center;
    }

    .presence-subtitle {
        font-size: 0.82rem;
        color: var(--text-muted);
        text-align: center;
        margin-bottom: 1.1rem;
    }

    .camera-container {
        width: 100%;
        max-width: 450px;
        margin: 0 auto;
        position: relative;
        background-color: #0f172a;
        border-radius: 1rem;
        overflow: hidden;
        aspect-ratio: 3/4;
        box-shadow: 0 10px 18px rgba(2, 6, 23, 0.12);
        border: 4px solid #ffffff;
    }

    #webcam {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1); /* Mirror effect */
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

    .status-grid {
        margin-top: 1rem;
        display: grid;
        gap: 0.75rem;
    }

    .status-overlay {
        background-color: #ffffff;
        border-radius: 0.8rem;
        padding: 0.8rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.04);
        border: 1px solid #e2e8f0;
    }

    .status-ok { color: #15803d; }
    .status-error { color: #b91c1c; }
    .status-loading { color: #d97706; }

    .capture-area {
        text-align: center;
        margin-top: 1.2rem;
    }

    .btn-capture {
        width: 72px;
        height: 72px;
        background-color: #ffffff;
        border: 5px solid var(--accent-green);
        border-radius: 50%;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 6px 14px rgba(21, 128, 61, 0.18);
        transition: transform 0.12s ease, box-shadow 0.2s ease;
    }

    .btn-capture:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(21, 128, 61, 0.24);
    }

    .btn-capture:active:not(:disabled) {
        transform: scale(0.94);
    }

    .btn-capture:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .btn-capture-inner {
        width: 50px;
        height: 50px;
        background-color: var(--accent-green);
        border-radius: 50%;
    }

    .btn-label {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
        font-weight: 600;
    }

    .presence-done {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
        border-radius: 0.8rem;
        padding: 1rem;
        text-align: center;
        margin-top: 1.1rem;
        font-weight: 700;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="presence-shell">
    <div class="presence-title">
        Presensi: {{ !$presensi ? 'Masuk Kerja' : ($presensi->jam_pulang ? 'Sudah Selesai' : 'Pulang Kerja') }}
    </div>
    <p class="presence-subtitle">
        Arahkan kamera ke wajah Anda & pastikan GPS Anda aktif.
    </p>

    <!-- Tampilan Kamera Web -->
    <div class="camera-container">
        <video id="webcam" autoplay playsinline></video>
        <canvas id="canvas-overlay"></canvas>
    </div>

    <div class="status-grid">
        <!-- Status GPS -->
        <div class="status-overlay" id="gps-status-box">
            <i class="fa-solid fa-spinner fa-spin status-loading" id="gps-icon"></i>
            <span id="gps-text">Mencari lokasi GPS Anda...</span>
        </div>

        <!-- Status Wajah (Face-API) -->
        <div class="status-overlay" id="face-status-box">
            <i class="fa-solid fa-spinner fa-spin status-loading" id="face-icon"></i>
            <span id="face-text">Memuat kecerdasan pengenalan wajah...</span>
        </div>
    </div>

    <!-- Tombol Capture -->
    @if(!$presensi || !$presensi->jam_pulang)
        <div class="capture-area">
            <button class="btn-capture" id="capture-btn" disabled>
                <div class="btn-capture-inner"></div>
            </button>
            <p class="btn-label" id="btn-label">Tunggu verifikasi...</p>
        </div>
    @else
        <div class="presence-done">
            <i class="fa-solid fa-circle-check"></i> Anda telah menyelesaikan presensi hari ini.
        </div>
    @endif
</div>

@endsection

@section('scripts')
<!-- Load face-api.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>

<script>
    const video = document.getElementById('webcam');
    const overlayCanvas = document.getElementById('canvas-overlay');
    const captureButton = document.getElementById('capture-btn');
    const btnLabel = document.getElementById('btn-label');
    
    // Status Elements
    const gpsIcon = document.getElementById('gps-icon');
    const gpsText = document.getElementById('gps-text');
    const faceIcon = document.getElementById('face-icon');
    const faceText = document.getElementById('face-text');

    let latitude = null;
    let longitude = null;
    let modelsLoaded = false;
    let faceMatcher = null;
    let isProcessing = false;

    // 1. Ambil Foto Referensi Wajah Terdaftar Karyawan
    const referencePhotoUrl = "{{ asset($karyawan->foto_wajah) }}";

    // 2. Dapatkan Lokasi GPS Karyawan
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                latitude = position.coords.latitude;
                longitude = position.coords.longitude;
                
                gpsIcon.className = "fa-solid fa-circle-check status-ok";
                gpsText.textContent = `GPS Terverifikasi (${latitude.toFixed(5)}, ${longitude.toFixed(5)})`;
                checkReadyState();
            },
            (error) => {
                gpsIcon.className = "fa-solid fa-circle-xmark status-error";
                gpsText.textContent = "Gagal mengambil lokasi GPS! Pastikan izin lokasi aktif.";
                console.error(error);
            },
            { enableHighAccuracy: true }
        );
    } else {
        gpsIcon.className = "fa-solid fa-circle-xmark status-error";
        gpsText.textContent = "Browser Anda tidak mendukung Geolocation.";
    }

    // 3. Muat Library Face-API.js & Model AI
    async function loadFaceApi() {
        try {
            // Muat model AI dari CDN Github milik pembuat face-api.js
            const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights/';
            await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            
            faceText.textContent = "Model AI dimuat. Menganalisis foto terdaftar...";
            
            // Buat Deskriptor Wajah Referensi dari foto profil Karyawan
            const referenceImage = await faceapi.fetchImage(referencePhotoUrl);
            const detections = await faceapi.detectSingleFace(referenceImage)
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detections) {
                faceIcon.className = "fa-solid fa-circle-xmark status-error";
                faceText.textContent = "Foto referensi wajah terdaftar tidak terdeteksi wajah!";
                return;
            }

            faceMatcher = new faceapi.FaceMatcher(detections.descriptor, 0.5); // Threshold jarak 0.5
            modelsLoaded = true;
            
            faceIcon.className = "fa-solid fa-circle-check status-ok";
            faceText.textContent = "Sistem Pengenalan Wajah Siap!";
            
            startVideo();
            checkReadyState();
        } catch (err) {
            faceIcon.className = "fa-solid fa-circle-xmark status-error";
            faceText.textContent = "Gagal memuat sistem pengenalan wajah: " + err.message;
            console.error(err);
        }
    }

    // 4. Nyalakan Kamera
    function startVideo() {
        navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480, facingMode: 'user' } })
            .then(stream => {
                video.srcObject = stream;
                video.addEventListener('play', onPlay);
            })
            .catch(err => {
                console.error("Gagal menyalakan kamera: ", err);
                alert("Harap izinkan akses kamera untuk melakukan presensi.");
            });
    }

    // 5. Jalankan Deteksi Wajah Real-Time dari Kamera
    async function onPlay() {
        const displaySize = { width: video.videoWidth || 640, height: video.videoHeight || 480 };
        faceapi.matchDimensions(overlayCanvas, displaySize);

        setInterval(async () => {
            if (!modelsLoaded || isProcessing) return;

            const detections = await faceapi.detectSingleFace(video, new faceapi.SsdMobilenetv1Options())
                .withFaceLandmarks()
                .withFaceDescriptor();

            // Clear canvas overlay
            const ctx = overlayCanvas.getContext('2d');
            ctx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);

            if (detections) {
                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                
                // Cari kecocokan wajah
                const result = faceMatcher.findBestMatch(resizedDetections.descriptor);
                const isMatch = result.label !== 'unknown';
                
                // Gambar kotak wajah
                const box = resizedDetections.detection.box;
                ctx.strokeStyle = isMatch ? '#22c55e' : '#ef4444'; // Hijau jika cocok, merah jika tidak
                ctx.lineWidth = 4;
                ctx.strokeRect(box.x, box.y, box.width, box.height);
                
                // Gambar label
                ctx.fillStyle = isMatch ? '#22c55e' : '#ef4444';
                ctx.font = '16px Inter';
                ctx.fillText(isMatch ? 'Cocok' : 'Wajah Tidak Dikenali', box.x, box.y - 10);

                if (isMatch && latitude !== null) {
                    captureButton.disabled = false;
                    btnLabel.textContent = "Silakan klik tombol di bawah untuk absen!";
                    btnLabel.style.color = "var(--accent-green)";
                } else {
                    captureButton.disabled = true;
                    btnLabel.textContent = isMatch ? "Menunggu GPS..." : "Posisikan wajah Anda dengan benar!";
                    btnLabel.style.color = "var(--text-muted)";
                }
            } else {
                captureButton.disabled = true;
                btnLabel.textContent = "Posisikan wajah Anda di depan kamera!";
                btnLabel.style.color = "var(--text-muted)";
            }
        }, 800);
    }

    function checkReadyState() {
        if (latitude !== null && modelsLoaded) {
            console.log("GPS & Face-API siap.");
        }
    }

    // Panggil fungsi inisialisasi Face API
    loadFaceApi();

    // 6. Proses Klik Tombol Presensi (Capture & Kirim Ajax)
    if (captureButton) {
        captureButton.addEventListener('click', async () => {
            if (isProcessing) return;
            isProcessing = true;
            captureButton.disabled = true;
            btnLabel.textContent = "Mengirim presensi...";

            // Ambil frame gambar saat ini dari video webcam
            const canvasTemp = document.createElement('canvas');
            canvasTemp.width = video.videoWidth;
            canvasTemp.height = video.videoHeight;
            const ctxTemp = canvasTemp.getContext('2d');
            // Gambar cermin dibalik kembali agar orientasinya normal
            ctxTemp.translate(canvasTemp.width, 0);
            ctxTemp.scale(-1, 1);
            ctxTemp.drawImage(video, 0, 0, canvasTemp.width, canvasTemp.height);
            const dataUrl = canvasTemp.toDataURL('image/jpeg');

            // Tentukan rute tujuan absen (masuk / pulang)
            const isPulang = "{{ $presensi && !$presensi->jam_pulang ? 'true' : 'false' }}" === 'true';
            const postUrl = isPulang ? "{{ route('karyawan.presensi.pulang') }}" : "{{ route('karyawan.presensi.masuk') }}";

            // Kirim data ke backend Laravel
            fetch(postUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    image: dataUrl,
                    lat: latitude,
                    lng: longitude
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = "{{ route('karyawan.dashboard') }}";
                } else {
                    alert("Absen Gagal: " + data.message);
                    isProcessing = false;
                    captureButton.disabled = false;
                    btnLabel.textContent = "Silakan coba lagi.";
                }
            })
            .catch(err => {
                alert("Error koneksi server!");
                console.error(err);
                isProcessing = false;
                captureButton.disabled = false;
            });
        });
    }
</script>
@endsection
