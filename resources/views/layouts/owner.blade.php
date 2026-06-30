<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Owner Dashboard') - Presensi Mur</title>
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
            --accent-green-light: #dcfce7;
            --bg-body: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --sidebar-width: 260px;
            --transition: all 0.25s ease-in-out;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
        }

        /* ── SIDEBAR Navigasi ───────────────────────────────── */
        aside {
            width: var(--sidebar-width);
            background-color: var(--primary);
            color: #ffffff;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 100;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-brand h2 i {
            color: #4ade80; /* Daun hijau muda */
        }

        .sidebar-menu {
            list-style: none;
            padding: 1.5rem 0.75rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .sidebar-link:hover, .sidebar-link.active {
            color: #ffffff;
            background-color: var(--primary-light);
        }

        .sidebar-link.active i {
            color: #4ade80;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-logout {
            width: 100%;
            background: none;
            border: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            color: #fca5a5;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            border-radius: 0.5rem;
            transition: var(--transition);
        }

        .btn-logout:hover {
            background-color: rgba(239, 68, 68, 0.1);
            color: #f87171;
        }

        /* ── KONTEN UTAMA AREA ────────────────────────────── */
        main {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        header {
            background-color: #ffffff;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .header-title h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: var(--accent-green-light);
            color: var(--accent-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .user-info span {
            font-weight: 600;
            font-size: 0.9rem;
            display: block;
        }

        .user-info small {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .content-body {
            padding: 2rem;
            flex-grow: 1;
        }

        /* Alert Notification */
        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            aside {
                display: none;
            }
            main {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Menu -->
    <aside>
        <div class="sidebar-brand">
            <h2><i class="fa-solid fa-mug-hot"></i> Presensi Mur</h2>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('owner.dashboard') }}" class="sidebar-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('owner.cabang.index') }}" class="sidebar-link {{ request()->routeIs('owner.cabang.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-store"></i>
                    <span>Kelola Cabang</span>
                </a>
            </li>
            <li>
                <a href="{{ route('owner.karyawan.index') }}" class="sidebar-link {{ request()->routeIs('owner.karyawan.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Kelola Karyawan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('owner.rekap.index') }}" class="sidebar-link {{ request()->routeIs('owner.rekap.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>Rekap Kehadiran</span>
                </a>
            </li>
            <li>
                <a href="{{ route('owner.penggajian.index') }}" class="sidebar-link {{ request()->routeIs('owner.penggajian.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-wallet"></i>
                    <span>Penggajian</span>
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Konten Utama -->
    <main>
        <header>
            <div class="header-title">
                <h1>@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="header-user">
                <div class="user-info">
                    <span>{{ Auth::user()->name }}</span>
                    <small>Owner Bisnis</small>
                </div>
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <div class="content-body">
            @if(session('success'))
                <div class="alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @yield('scripts')
</body>
</html>
