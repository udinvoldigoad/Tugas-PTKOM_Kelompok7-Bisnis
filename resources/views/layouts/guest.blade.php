<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Coffe Ridho') }} - Login</title>
        <meta name="description" content="Login ke Coffe Ridho - Sistem manajemen kedai kopi terbaik.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800;900&family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            /* ============================================================
               RESET
            ============================================================ */
            *, *::before, *::after {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            /* ============================================================
               DESIGN TOKENS
            ============================================================ */
            :root {
                --color-bg:          #D4B896;
                --color-card:        #F3EDE4;
                --color-card-shadow: rgba(80, 50, 10, 0.15);
                --color-brand:       #1A1A1A;
                --color-label:       #2A1F10;
                --color-input-bg:    #DDD5C8;
                --color-input-focus: #C8B89A;
                --color-placeholder: #9E8E7A;
                --color-btn:         #B08D57;
                --color-btn-hover:   #9A7B48;
                --color-btn-text:    #FFFFFF;
                --color-error:       #C0392B;
                --font-brand:        'Barlow', sans-serif;
                --font-body:         'Outfit', sans-serif;
            }

            /* ============================================================
               PAGE BODY
            ============================================================ */
            html, body {
                width: 100%;
                height: 100%;
            }

            body {
                font-family: var(--font-body);
                background-color: var(--color-bg);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            /* ============================================================
               BACKGROUND: aset biji kopi (sama dengan welcome page)
            ============================================================ */
            .bg-pattern {
                position: fixed;
                inset: 0;
                width: 100%;
                height: 100%;
                background-image: url('/depan/asetbijikopi.png');
                background-repeat: repeat;
                background-size: 280px auto;
                opacity: 0.40;
                pointer-events: none;
                z-index: 0;
            }

            /* ============================================================
               WRAPPER
            ============================================================ */
            .guest-wrapper {
                position: relative;
                z-index: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
                max-width: 500px;
                padding: 0 1.5rem;
                animation: fadeUp 0.65s ease both;
            }

            /* ============================================================
               BRAND HEADER (logo + nama)
            ============================================================ */
            .brand-header {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin-bottom: 1.75rem;
                animation: fadeDown 0.6s ease both;
            }

            .brand-logo-img {
                width: 60px;
                height: 60px;
                object-fit: contain;
            }

            .brand-name {
                font-family: var(--font-brand);
                font-size: 2rem;
                font-weight: 900;
                color: var(--color-brand);
                letter-spacing: 0.08em;
                line-height: 1.05;
                text-transform: uppercase;
            }

            /* ============================================================
               AUTH CARD
            ============================================================ */
            .auth-card {
                width: 100%;
                background: var(--color-card);
                border-radius: 1.25rem;
                padding: 2.25rem 2.5rem 2.5rem;
                box-shadow: 0 8px 40px var(--color-card-shadow);
                animation: fadeUp 0.65s ease 0.1s both;
            }

            /* Login heading */
            .auth-card h2 {
                font-family: var(--font-brand);
                font-size: 1.6rem;
                font-weight: 700;
                color: var(--color-brand);
                margin-bottom: 1.5rem;
                letter-spacing: 0.02em;
            }

            /* ============================================================
               FORM FIELDS
            ============================================================ */
            .field-group {
                margin-bottom: 1.1rem;
            }

            .field-label {
                display: block;
                font-family: var(--font-body);
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--color-label);
                margin-bottom: 0.4rem;
                letter-spacing: 0.01em;
            }

            .field-input-wrap {
                position: relative;
            }

            .field-input {
                width: 100%;
                padding: 0.7rem 2.5rem 0.7rem 0.875rem;
                background: var(--color-input-bg);
                border: 1.5px solid transparent;
                border-radius: 0.5rem;
                font-family: 'Courier New', monospace; /* monospace agar mirip referensi */
                font-size: 0.88rem;
                color: var(--color-label);
                outline: none;
                transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            }

            .field-input::placeholder {
                color: var(--color-placeholder);
                font-family: 'Courier New', monospace;
            }

            .field-input:focus {
                border-color: var(--color-btn);
                background: var(--color-input-focus);
                box-shadow: 0 0 0 3px rgba(176, 141, 87, 0.2);
            }

            /* Eye icon di input password */
            .toggle-password {
                position: absolute;
                right: 0.75rem;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                padding: 0;
                line-height: 1;
                color: var(--color-placeholder);
                transition: color 0.2s;
            }

            .toggle-password:hover { color: var(--color-label); }

            /* ============================================================
               ERROR & SESSION STATUS
            ============================================================ */
            .error-msg {
                color: var(--color-error);
                font-size: 0.78rem;
                margin-top: 0.3rem;
            }

            .session-status {
                background: rgba(176, 141, 87, 0.15);
                border: 1px solid var(--color-btn);
                border-radius: 0.5rem;
                padding: 0.625rem 0.875rem;
                font-size: 0.85rem;
                color: #5a3e1b;
                margin-bottom: 1.1rem;
            }

            /* ============================================================
               LOGIN BUTTON
            ============================================================ */
            .btn-login {
                display: block;
                width: 100%;
                padding: 0.8rem;
                margin-top: 1.75rem;
                background: var(--color-btn);
                color: var(--color-btn-text);
                font-family: var(--font-brand);
                font-size: 1rem;
                font-weight: 700;
                letter-spacing: 0.04em;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                box-shadow: 0 4px 16px rgba(120, 90, 30, 0.28);
                transition: background 0.22s, transform 0.15s, box-shadow 0.22s;
            }

            .btn-login:hover {
                background: var(--color-btn-hover);
                transform: translateY(-1px);
                box-shadow: 0 6px 22px rgba(120, 90, 30, 0.38);
            }

            .btn-login:active {
                transform: translateY(0);
                box-shadow: 0 3px 10px rgba(120, 90, 30, 0.22);
            }

            .btn-login:focus-visible {
                outline: 3px solid rgba(176, 141, 87, 0.55);
                outline-offset: 3px;
            }

            /* ============================================================
               ANIMATIONS
            ============================================================ */
            @keyframes fadeDown {
                from { opacity: 0; transform: translateY(-20px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(22px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ============================================================
               RESPONSIVE
            ============================================================ */
            @media (max-width: 520px) {
                .auth-card { padding: 1.75rem 1.5rem 2rem; }
                .brand-name { font-size: 1.65rem; }
                .brand-logo-img { width: 50px; height: 50px; }
            }
        </style>
    </head>

    <body>

        {{-- Background biji kopi --}}
        <div class="bg-pattern" aria-hidden="true"></div>

        <div class="guest-wrapper">

            {{-- Brand header: logo + nama --}}
            <div class="brand-header" id="brand-logo">
                <img
                    src="{{ asset('depan/logo.png') }}"
                    alt="Logo Coffe Ridho"
                    class="brand-logo-img"
                    width="60"
                    height="60"
                >
                <div class="brand-name">
                    COFFE<br>RIDHO
                </div>
            </div>

            {{-- Auth Card --}}
            <div class="auth-card">
                {{ $slot }}
            </div>

        </div>

        {{-- Toggle password visibility script --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toggleBtn = document.getElementById('toggle-pwd');
                const pwdInput  = document.getElementById('password');

                if (toggleBtn && pwdInput) {
                    toggleBtn.addEventListener('click', function () {
                        const isText = pwdInput.type === 'text';
                        pwdInput.type = isText ? 'password' : 'text';
                        toggleBtn.setAttribute('aria-label', isText ? 'Tampilkan password' : 'Sembunyikan password');
                        // ganti ikon
                        toggleBtn.innerHTML = isText ? eyeIcon() : eyeOffIcon();
                    });
                }

                function eyeIcon() {
                    return `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>`;
                }

                function eyeOffIcon() {
                    return `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>`;
                }
            });
        </script>

    </body>
</html>
