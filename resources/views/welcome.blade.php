<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Coffe Ridho - Nikmati cita rasa kopi pilihan terbaik. Login sekarang untuk memesan.">
        <title>Coffe Ridho - Halaman Utama</title>

        <!-- Google Fonts: Barlow -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@700;800;900&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            :root {
                --color-bg:         #D4B896;
                --color-frame-bg:   #D4B896;
                --color-btn:        #8B1F2C;
                --color-btn-hover:  #A02030;
                --color-card-border:#9A7B5A;
                --font-brand:       'Barlow', sans-serif;
                --shadow-card:      0 8px 40px rgba(60, 35, 10, 0.28);
                --shadow-btn:       0 4px 18px rgba(139, 31, 44, 0.38);
            }

            html, body {
                width: 100%;
                height: 100%;
                font-family: var(--font-brand);
                background-color: var(--color-bg);
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 1.5rem;
            }

            /* ===== WINDOW FRAME ===== */
            .window-frame {
                position: relative;
                width: 100%;
                max-width: 1000px;
                min-height: 560px;
                background-color: var(--color-frame-bg);
                border-radius: 1.5rem;
                border: none;
                box-shadow: none;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 3.5rem 3rem;
                gap: 3rem;
            }

            /* ===== COFFEE BEAN PATTERN ===== */
            .bg-pattern {
                position: fixed;
                inset: 0;
                background-image: url('/depan/asetbijikopi.png');
                background-repeat: repeat;
                background-size: 140px auto;
                opacity: 0.45;
                pointer-events: none;
                z-index: 0;
            }

            /* ===== HERO ROW ===== */
            .hero {
                position: relative;
                z-index: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: clamp(4rem, 10vw, 10rem);
                width: 100%;
            }

            /* ===== BRAND ===== */
            .brand {
                display: flex;
                align-items: center;
                gap: 1rem;
                flex-shrink: 0;
                animation: fadeLeft 0.75s ease-out both;
            }

            .brand__logo-img {
                width: 72px;
                height: auto;
                object-fit: contain;
            }

            .brand__text {
                display: flex;
                flex-direction: column;
                line-height: 1.05;
            }

            .brand__name {
                font-family: var(--font-brand);
                font-size: clamp(1.9rem, 3.2vw, 2.8rem);
                font-weight: 900;
                letter-spacing: 0.1em;
                color: #1A1208;
                text-transform: uppercase;
            }

            /* ===== PRODUCT CARD ===== */
            .product-card {
                width: clamp(260px, 34vw, 380px);
                aspect-ratio: 4 / 3;
                border-radius: 1.25rem;
                border: 2.5px solid var(--color-card-border);
                box-shadow: var(--shadow-card);
                overflow: hidden;
                flex-shrink: 0;
                animation: fadeRight 0.75s ease-out 0.15s both;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .product-card:hover {
                transform: translateY(-5px) scale(1.01);
                box-shadow: 0 14px 48px rgba(60, 35, 10, 0.32);
            }

            .product-card__img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            /* ===== LOGIN BUTTON ===== */
            .btn-login {
                position: relative;
                z-index: 1;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.9rem 4rem;
                background-color: var(--color-btn);
                color: #fff;
                font-family: var(--font-brand);
                font-size: 0.85rem;
                font-weight: 700;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                text-decoration: none;
                border: none;
                border-radius: 0.4rem;
                cursor: pointer;
                box-shadow: var(--shadow-btn);
                transition: background-color 0.25s ease, transform 0.2s ease, box-shadow 0.25s ease;
                animation: fadeUp 0.75s ease-out 0.3s both;
            }

            .btn-login:hover {
                background-color: var(--color-btn-hover);
                transform: translateY(-2px);
                box-shadow: 0 8px 26px rgba(139, 31, 44, 0.5);
            }

            .btn-login:active { transform: translateY(0); }

            /* ===== ANIMATIONS ===== */
            @keyframes fadeLeft {
                from { opacity: 0; transform: translateX(-36px); }
                to   { opacity: 1; transform: translateX(0); }
            }
            @keyframes fadeRight {
                from { opacity: 0; transform: translateX(36px); }
                to   { opacity: 1; transform: translateX(0); }
            }
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(22px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ===== RESPONSIVE ===== */
            @media (max-width: 768px) {
                body { padding: 1rem; }
                .window-frame { padding: 2.5rem 1.5rem; gap: 2rem; min-height: unset; }
                .hero { flex-direction: column; gap: 2rem; }
                .product-card { width: min(300px, 86vw); }
                .brand__name { font-size: 1.7rem; }
            }
            @media (max-width: 480px) {
                .btn-login { padding: 0.75rem 2.5rem; font-size: 0.8rem; }
                .brand__logo-img { width: 54px; }
            }
        </style>
    </head>

    <body>
        <!-- Coffee Bean Pattern (Full Page) -->
        <div class="bg-pattern" aria-hidden="true"></div>

        <!-- Window Frame -->
        <div class="window-frame" id="main-window">


            <!-- Hero: Logo kiri, Card kanan -->
            <section class="hero" aria-label="Tampilan utama Coffe Ridho">

                <!-- Brand -->
                <div class="brand" id="brand-logo">
                    <img
                        src="{{ asset('depan/logo.png') }}"
                        alt="Logo Coffe Ridho"
                        class="brand__logo-img"
                        width="72"
                        height="72"
                    >
                    <div class="brand__text">
                        <span class="brand__name">COFFE</span>
                        <span class="brand__name">RIDHO</span>
                    </div>
                </div>

                <!-- Product Card -->
                <div class="product-card" id="product-card">
                    <img
                        src="{{ asset('depan/fotoditempatputih.png') }}"
                        alt="Foto produk kopi Ridho"
                        class="product-card__img"
                        width="380"
                        height="285"
                        loading="eager"
                    >
                </div>

            </section>

            <!-- Login Button -->
            <div style="position:relative;z-index:1;">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" id="btn-dashboard" class="btn-login">
                            DASHBOARD
                        </a>
                    @else
                        <a href="{{ route('login') }}" id="btn-login-sekarang" class="btn-login">
                            LOGIN SEKARANG
                        </a>
                    @endauth
                @else
                    <a href="#" id="btn-login-sekarang" class="btn-login">
                        LOGIN SEKARANG
                    </a>
                @endif
            </div>

        </div>
    </body>
</html>
