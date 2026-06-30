<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Kiosk Terminal') - Presensi Teh Mur</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #002855;
            --primary-light: #003b7a;
            --accent-green: #15803d;
            --accent-green-hover: #166534;
            --bg-body: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --card-bg: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Karyawan (Bright - selaras Owner) */
        header {
            background-color: var(--primary);
            color: #ffffff;
            padding: 1rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 6px 20px rgba(0, 40, 85, 0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            background-color: rgba(74, 222, 128, 0.15);
            border: 1px solid rgba(74, 222, 128, 0.35);
            color: #4ade80;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            animation: pulse 2.2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.35); }
            70% { box-shadow: 0 0 0 12px rgba(74, 222, 128, 0); }
            100% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0); }
        }

        .header-text h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .header-text p {
            font-size: 0.8rem;
            color: #cbd5e1;
            margin-top: 0.15rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Digital Clock */
        .digital-clock-box {
            background-color: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 0.5rem 1rem;
            border-radius: 0.6rem;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .digital-clock {
            font-family: monospace;
            font-size: 1.15rem;
            font-weight: 700;
            color: #86efac;
        }

        .digital-date {
            font-size: 0.72rem;
            color: #cbd5e1;
            margin-top: 0.1rem;
        }

        /* Logout Button */
        .btn-logout {
            color: #b91c1c;
            background-color: #ffffff;
            border: 1px solid #fee2e2;
            width: 40px;
            height: 40px;
            border-radius: 0.55rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-logout:hover {
            background-color: #fee2e2;
            color: #991b1b;
            transform: translateY(-1px);
        }

        .container {
            padding: 2rem 1.5rem;
            flex-grow: 1;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @media (max-width: 768px) {
            header {
                padding: 0.85rem 1rem;
            }

            .header-container {
                gap: 0.75rem;
                align-items: flex-start;
                flex-direction: column;
            }

            .header-right {
                width: 100%;
                justify-content: space-between;
            }

            .container {
                padding: 1.25rem 0.95rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header Kiosk Terminal -->
    <header>
        <div class="header-container">
            <div class="header-info">
                <div class="header-icon-box">
                    <i class="fa-solid fa-shop"></i>
                </div>
                <div class="header-text">
                    <h3>{{ Auth::user()->cabang->nama_cabang ?? 'Kiosk Terminal' }}</h3>
                    <p><i class="fa-solid fa-location-dot" style="margin-right: 0.25rem;"></i> Terminal Absensi Resmi Teh Mur</p>
                </div>
            </div>
            
            <div class="header-right">
                <!-- Real-time Digital Clock -->
                <div class="digital-clock-box">
                    <div class="digital-clock" id="kiosk-clock">00:00:00</div>
                    <div class="digital-date" id="kiosk-date">Senin, 01 Januari 2026</div>
                </div>

                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout" title="Logout dari Kiosk">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Konten Halaman Kiosk -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Digital Clock Script -->
    <script>
        function updateClock() {
            const clockEl = document.getElementById('kiosk-clock');
            const dateEl = document.getElementById('kiosk-date');
            
            if (!clockEl || !dateEl) return;
            
            const now = new Date();
            
            // Format Jam (HH:MM:SS)
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            clockEl.textContent = `${hours}:${minutes}:${seconds}`;
            
            // Format Hari & Tanggal Indonesia
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const dayNum = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            
            dateEl.textContent = `${dayName}, ${dayNum} ${monthName} ${year}`;
        }
        
        setInterval(updateClock, 1000);
        updateClock(); // Jalankan sekali di awal
    </script>

    @yield('scripts')
</body>
</html>
