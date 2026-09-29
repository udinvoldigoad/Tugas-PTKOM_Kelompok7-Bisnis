<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Coffe Ridho</title>
    <meta name="description" content="Nikmati pilihan kopi, minuman, dan makanan dari Coffe Ridho.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ingrid+Darling&family=Oswald:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root { --sand:#dec799; --cream:#f7f0e8; --shelf:#e1bd74; --ink:#2c211d; --red:#a93432; --orange:#e96527; }
        html { scroll-behavior: smooth; }
        body { margin: 0; background: var(--sand); color: var(--ink); font-family: 'Space Mono', monospace; }
        html { scrollbar-color: var(--orange) #f3e4c3; scrollbar-width: thin; }
        ::-webkit-scrollbar { width: 12px; }
        ::-webkit-scrollbar-track { background: #f3e4c3; }
        ::-webkit-scrollbar-thumb { border: 3px solid #f3e4c3; border-radius: 999px; background: var(--orange); }
        ::-webkit-scrollbar-thumb:hover { background: var(--red); }
        .display { font-family: 'Oswald', sans-serif; }
        .script { font-family: 'Ingrid Darling', cursive; }
        .texture { background-image: radial-gradient(circle at 8% 14%, #c2a05f 0 48px, transparent 49px), radial-gradient(circle at 96% 43%, #c2a05f 0 64px, transparent 65px); }
        .menu-card { box-shadow: 4px 5px 0 rgb(62 43 31 / 18%); }
        .menu-card:hover { transform: translateY(-4px); box-shadow: 5px 9px 0 rgb(62 43 31 / 20%); }
        .promo-art { transform: scale(1.55); }
        .menu-intro { position: fixed; inset: 0; z-index: 100; display: grid; place-items: center; overflow: hidden; pointer-events: none; background: var(--sand); transition: opacity .45s ease, visibility .45s ease; }
        .menu-intro::before, .menu-intro::after { position: absolute; width: 16rem; height: 16rem; border-radius: 999px; background: #c2a05f; content: ''; opacity: .55; }
        .menu-intro::before { top: -7rem; left: -7rem; }
        .menu-intro::after { right: -7rem; bottom: -7rem; }
        .menu-intro__content { position: relative; z-index: 1; display: flex; flex-direction: column; align-items: center; animation: introContent .9s cubic-bezier(.22,1,.36,1) both; }
        .menu-intro__logo { width: 5.5rem; height: 5.5rem; object-fit: contain; animation: introLogo .9s cubic-bezier(.22,1,.36,1) both; }
        .menu-intro__name { margin-top: 1rem; font-family: 'Oswald', sans-serif; font-size: 2rem; font-weight: 700; line-height: .9; letter-spacing: .08em; text-align: center; }
        .menu-intro__line { width: 0; height: 3px; margin-top: 1.25rem; border-radius: 999px; background: var(--orange); animation: introLine .75s .25s cubic-bezier(.22,1,.36,1) forwards; }
        .menu-intro.is-leaving { visibility: hidden; opacity: 0; }
        @keyframes introLogo { from { transform: translateY(1rem) scale(.72); opacity: 0; } to { transform: none; opacity: 1; } }
        @keyframes introContent { from { opacity: 0; } to { opacity: 1; } }
        @keyframes introLine { to { width: 7rem; } }
        @media (prefers-reduced-motion: reduce) { .menu-intro { display: none; } }
        .hero-shell { width: calc(100% - 1rem); margin: .75rem auto 0; border: 2px solid rgb(44 33 29 / 70%); border-radius: 24px; box-shadow: 6px 7px 0 rgb(92 67 44 / 20%); }
        .hero-shade { background: linear-gradient(90deg, rgb(17 12 9 / 72%) 0%, rgb(17 12 9 / 8%) 52%, rgb(17 12 9 / 68%) 100%); }
        @media (min-width: 1024px) {
            .hero-shell { width: calc(100% - 3rem); max-width: 1440px; min-height: 620px; margin: 2rem auto 0; border: 2px solid rgb(44 33 29 / 70%); border-radius: 32px; box-shadow: 9px 11px 0 rgb(92 67 44 / 22%); }
            .hero-shell > img { object-position: center 48%; transform: scale(1.025); }
            .hero-shade { background: linear-gradient(90deg, rgb(17 12 9 / 78%) 0%, rgb(17 12 9 / 20%) 42%, rgb(17 12 9 / 8%) 58%, rgb(17 12 9 / 72%) 100%); }
            .hero-content { min-height: 620px; align-items: center; padding-bottom: 2rem; }
            .hero-copy { max-width: 30rem; }
            .hero-now { align-self: center; padding-bottom: 0; }
        }
    </style>
</head>
<body>
    <div id="menu-intro" class="menu-intro" aria-hidden="true">
        <div class="menu-intro__content">
            <img src="{{ asset('depan/logo.png') }}" alt="" class="menu-intro__logo">
            <p class="menu-intro__name">COFFE<br>RIDHO</p>
            <span class="menu-intro__line"></span>
        </div>
    </div>

    @php
        $availableMenus = $menus->where('status_ketersediaan', 'tersedia');
        $coffeeMenus = $menus->where('kategori', 'Kopi')->values();
        $otherMenus = $menus->whereIn('kategori', ['Non-Kopi', 'Minuman', 'Makanan', 'Dessert'])->values();
        $featuredMenu = $availableMenus->first() ?? $menus->first();

        $photo = static function ($menu, string $fallback): string {
            return $menu?->foto ? asset('storage/'.$menu->foto) : asset('menu-web/'.$fallback);
        };
    @endphp

    <header class="sticky top-0 z-50 border-b border-black/10 bg-[#f7f0e8]/95 backdrop-blur">
        <nav class="mx-auto flex h-20 max-w-7xl items-center justify-between px-5 sm:px-8 lg:h-24">
            <a href="{{ url('/') }}" class="flex items-center gap-3 text-black" aria-label="Coffe Ridho">
                <img src="{{ asset('depan/logo.png') }}" alt="" class="h-11 w-11 object-contain lg:h-14 lg:w-14">
                <span class="display text-xl font-semibold italic leading-[.9] lg:text-2xl">COFFE<br>RIDHO</span>
            </a>

            <div class="hidden items-center gap-8 text-sm font-bold md:flex">
                <a href="#kopi" class="transition hover:text-[#a93432]">Menu Kopi</a>
                <a href="#non-kopi" class="transition hover:text-[#a93432]">Menu Lainnya</a>
            </div>

            <a href="{{ route('login') }}" class="group flex items-center gap-2 rounded-xl border border-[#2c211d]/20 bg-white/45 px-3 py-2 text-xs font-bold text-[#5f5048] transition hover:-translate-y-0.5 hover:border-[#a93432]/40 hover:bg-white hover:text-[#a93432] sm:px-4 sm:py-2.5 sm:text-sm" aria-label="Masuk ke halaman kasir">
                <svg class="h-4 w-4 transition group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                    <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                </svg>
                <span>Akses Kasir</span>
            </a>
        </nav>
    </header>

    <main>
        <section class="hero-shell relative isolate min-h-[520px] overflow-hidden sm:min-h-[610px] lg:min-h-[620px]">
            <img src="{{ asset('menu-web/image0_233_1585.png') }}" alt="Es kopi susu Coffe Ridho" class="absolute inset-0 h-full w-full object-cover object-center">
            <div class="hero-shade absolute inset-0 bg-gradient-to-r from-black/55 via-black/10 to-black/50"></div>
            <div class="hero-content relative mx-auto flex min-h-[520px] max-w-7xl items-end justify-between gap-4 px-5 pb-16 text-white sm:min-h-[610px] sm:px-8 sm:pb-20 lg:min-h-[620px] lg:px-14">
                <div class="hero-copy">
                    <span class="mb-3 block w-fit rounded-full border border-white/35 bg-white/10 px-3 py-1.5 text-[9px] font-bold uppercase tracking-[.12em] text-[#f5d88e] backdrop-blur-md sm:text-[10px] lg:mb-6 lg:px-4 lg:py-2 lg:text-xs lg:tracking-[.18em]">Freshly brewed · Bandar Lampung</span>
                    <p class="display text-6xl font-bold leading-none tracking-[-.04em] sm:text-8xl lg:text-[8rem]">COFFEE</p>
                    <p class="mt-4 max-w-sm text-sm font-bold uppercase tracking-[.2em] text-[#f5d88e] sm:text-base">Diracik segar saat dipesan</p>
                    <a href="#kopi" class="mt-5 flex w-fit items-center gap-2 rounded-lg bg-[#e96527] px-4 py-2 text-xs font-bold text-white shadow-[3px_3px_0_#8e321c] transition hover:-translate-y-1 hover:bg-[#f07636] sm:text-sm lg:mt-8 lg:gap-3 lg:rounded-xl lg:px-6 lg:py-3 lg:text-base lg:shadow-[4px_4px_0_#8e321c]">
                        Lihat Menu <span aria-hidden="true">↓</span>
                    </a>
                </div>
                <div class="hero-now pb-2 text-right">
                    <p class="display text-6xl font-bold leading-none tracking-[-.04em] sm:text-8xl lg:text-[8rem]">NOW</p>
                    <p class="script -mt-1 text-4xl sm:text-6xl lg:text-7xl">Tabik Pun</p>
                </div>
            </div>
        </section>

        @if ($menus->isEmpty())
            <section class="px-5 py-24 text-center">
                <h1 class="display text-4xl font-bold">Menu belum tersedia</h1>
                <p class="mt-3">Silakan kembali lagi setelah katalog diperbarui.</p>
            </section>
        @else
            <section class="texture px-5 py-16 sm:px-8 sm:py-20">
                <div class="mx-auto max-w-7xl">
                    <div class="grid gap-6 md:grid-cols-2">
                        @foreach ($availableMenus->take(2) as $index => $menu)
                            <article class="menu-card relative grid min-h-64 grid-cols-[1fr_42%] overflow-hidden rounded-3xl border-2 border-[#2c211d] bg-[#f7f0e8] sm:min-h-72">
                                <div class="flex flex-col justify-end p-6 sm:p-8">
                                    <span class="absolute left-5 top-4 z-10 rounded-xl border-2 border-[#2c211d] bg-[#ff6d71] px-3 py-1.5 text-base font-bold leading-none text-white shadow-[2px_2px_0_rgb(44_33_29_/_18%)] sm:left-7 sm:top-6 sm:px-4 sm:py-2 sm:text-xl">
                                        {{ $index === 0 ? 'NEW' : 'PILIHAN' }}
                                    </span>
                                    <h1 class="display text-3xl font-semibold text-[#a93432] sm:text-4xl">{{ $menu->nama_menu }}</h1>
                                    <p class="mt-2 font-bold">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center justify-center bg-[#e9d4a7] p-4">
                                    <img src="{{ $photo($menu, 'image2_233_1585.png') }}" alt="{{ $menu->nama_menu }}" class="promo-art h-52 w-full object-contain sm:h-60">
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            @foreach ([
                ['id' => 'kopi', 'title' => 'MENU', 'suffix' => 'Coffe', 'menus' => $coffeeMenus, 'fallback' => 'image2_233_1585.png'],
                ['id' => 'non-kopi', 'title' => 'MENU', 'suffix' => 'Non Coffe', 'menus' => $otherMenus, 'fallback' => 'image1_233_1585.png'],
            ] as $section)
                @if ($section['menus']->isNotEmpty())
                    <section id="{{ $section['id'] }}" class="scroll-mt-24 px-5 py-14 sm:px-8 sm:py-20">
                        <div class="mx-auto max-w-7xl">
                            <div class="mb-8 flex items-baseline gap-4 sm:mb-10">
                                <h2 class="display text-5xl font-bold tracking-[-.04em] text-white [text-shadow:3px_4px_0_#9e8250] sm:text-7xl">{{ $section['title'] }}</h2>
                                <span class="script text-4xl text-black sm:text-6xl">{{ $section['suffix'] }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
                                @foreach ($section['menus'] as $menu)
                                    <article class="menu-card flex min-w-0 flex-col overflow-hidden rounded-2xl border-2 border-[#2c211d] bg-[#e1bd74] transition duration-200">
                                        <div class="relative flex aspect-[4/3] items-center justify-center bg-[#f7f0e8] p-4">
                                            <img src="{{ $photo($menu, $section['fallback']) }}" alt="{{ $menu->nama_menu }}" class="h-full w-full object-contain {{ $menu->status_ketersediaan === 'habis' ? 'grayscale opacity-50' : '' }}">
                                            <span class="absolute bottom-3 right-3 rounded-xl px-3 py-1.5 font-bold text-white {{ $menu->status_ketersediaan === 'habis' ? 'bg-[#8b2626]' : 'bg-[#e96527]' }}">
                                                {{ $menu->status_ketersediaan === 'habis' ? 'Habis' : 'Rp '.number_format($menu->harga, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="flex min-h-20 items-center justify-center p-3 text-center">
                                            <h3 class="display line-clamp-2 text-xl font-semibold text-[#a93432] sm:text-2xl">{{ $menu->nama_menu }}</h3>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif

                @if ($loop->first && $featuredMenu)
                    <section class="px-5 py-10 sm:px-8 sm:py-16">
                        <div class="mx-auto grid max-w-7xl overflow-hidden rounded-3xl border-2 border-[#2c211d] bg-[#f7f0e8] shadow-[6px_7px_0_rgb(62_43_31_/_18%)] md:grid-cols-2">
                            <div class="flex min-h-72 items-center justify-center bg-[#ead7af] p-8 sm:min-h-96">
                                <img src="{{ $photo($featuredMenu, 'image3_233_1585.png') }}" alt="{{ $featuredMenu->nama_menu }}" class="h-72 w-full object-contain sm:h-96">
                            </div>
                            <div class="flex flex-col items-center justify-center p-8 text-center sm:p-12">
                                <p class="display text-5xl font-bold text-[#aa8848] [text-shadow:3px_3px_0_rgb(0_0_0_/_16%)] sm:text-7xl">BEST SELLER</p>
                                <p class="mt-7 rounded-2xl border-2 border-[#2c211d] bg-[#e1bd74] px-6 py-3 text-2xl font-bold text-[#866b37] shadow-[4px_4px_0_rgb(0_0_0_/_18%)] sm:text-4xl">{{ $featuredMenu->nama_menu }}</p>
                            </div>
                        </div>
                    </section>
                @endif
            @endforeach
        @endif
    </main>

    <footer class="mt-10 border-t-2 border-black/10 bg-[#f7f0e8] px-5 py-8 text-center text-sm font-bold">
        <p>Coffe Ridho · Kopi hangat untuk setiap cerita.</p>
    </footer>
    <script>
        (() => {
            const intro = document.getElementById('menu-intro');
            let hasSeenIntro = false;

            try {
                hasSeenIntro = sessionStorage.getItem('coffe-ridho-menu-intro') === 'seen';
            } catch (error) {
                hasSeenIntro = false;
            }

            if (hasSeenIntro || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                intro.remove();
                return;
            }

            try {
                sessionStorage.setItem('coffe-ridho-menu-intro', 'seen');
            } catch (error) {
                // Animasi tetap berjalan jika penyimpanan browser tidak tersedia.
            }
            window.setTimeout(() => intro.classList.add('is-leaving'), 1100);
            window.setTimeout(() => intro.remove(), 1600);
        })();
    </script>
</body>
</html>
