<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Presensi Mur</title>
    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #002855; /* Navy Blue gelap premium */
            --text-color: #1e293b;
            --border-color: #e2e8f0;
            --bg-light: #f8fafc;
            --accent-green: #15803d;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #ffffff;
            color: var(--text-color);
            overflow-x: hidden;
        }

        /* Layout Container Split 2 Kolom */
        .login-container {
            display: flex;
            width: 100%;
        }

        /* Kolom Kiri: Gambar Kebun Teh & Tagline */
        .login-banner {
            flex: 1.2;
            position: relative;
            background-image: url('{{ asset("images/tea-bg.jpg") }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
            padding: 3rem;
            color: #ffffff;
        }

        /* Overlay gelap transparan untuk memperjelas teks */
        .login-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.1) 0%, rgba(0, 40, 85, 0.8) 100%);
            z-index: 1;
        }

        .banner-content {
            position: relative;
            z-index: 2;
            max-width: 550px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 2.5rem;
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
        }

        .banner-content h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 1rem;
        }

        .banner-content p {
            font-size: 1rem;
            line-height: 1.6;
            color: #e2e8f0;
        }

        /* Kolom Kanan: Form Login */
        .login-form-section {
            flex: 0.8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background-color: #ffffff;
        }

        .form-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i.input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.7rem;
            font-size: 0.95rem;
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            background-color: var(--bg-light);
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(0, 40, 85, 0.15);
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: var(--primary-color);
        }

        /* Error Message styling */
        .error-alert {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #b91c1c;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-submit {
            width: 100%;
            padding: 0.875rem;
            background-color: var(--primary-color);
            color: #ffffff;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 40, 85, 0.2);
        }

        .btn-submit:hover {
            background-color: #001d3d;
            transform: translateY(-1px);
            box-shadow: 0 6px 12px -1px rgba(0, 40, 85, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .login-banner {
                display: none; /* Sembunyikan banner di layar tablet/mobile */
            }
            .login-form-section {
                flex: 1;
                padding: 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Kolom Kiri: Banner & Tagline -->
        <div class="login-banner">
            <div class="banner-content">
                <h1>Manajemen Presensi Terintegrasi Untuk Bisnis Multi Cabang.</h1>
                <p>Pantau kehadiran dan optimalkan produktivitas bisnis dengan sistem presensi digital terintegrasi.</p>
            </div>
        </div>

        <!-- Kolom Kanan: Form Login -->
        <div class="login-form-section">
            <div class="form-wrapper">
                <div class="form-header">
                    <h2>Presensi Mur</h2>
                    <p>Sistem Presensi Bisnis UMKM</p>
                </div>

                @if($errors->any())
                    <div class="error-alert">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    <!-- Input Email -->
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <div class="input-wrapper">
                            <i class="fa-regular fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email" class="form-control" placeholder="nama@usaha.com" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <!-- Input Password -->
                    <div class="form-group">
                        <label for="password">Kata Sandi</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                            <i class="fa-regular fa-eye toggle-password" id="togglePassword"></i>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit">
                        <span>Masuk</span>
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JS untuk Show/Hide Password -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            // Toggle type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle eye icon class
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
