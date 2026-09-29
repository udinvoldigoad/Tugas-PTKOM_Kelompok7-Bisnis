<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Coffe Ridho</title>
    <meta name="description" content="Daftar menu Coffe Ridho beserta harga dan status ketersediaannya.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,500;0,600;0,700;0,800;0,900;1,600&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[#20201F] text-[#5B302B] md:bg-[#E8CF98]" style="font-family: 'Barlow Condensed', sans-serif">
    @php
        $availableMenus = $menus->where('status_ketersediaan', 'tersedia');
        $promoMenus = $availableMenus->take(2);
        $coffeeMenus = $menus->where('kategori', 'Kopi');
        $nonCoffeeMenus = $menus->whereIn('kategori', ['Non-Kopi', 'Minuman']);
        $foodMenus = $menus->whereIn('kategori', ['Makanan', 'Dessert']);
        $featuredMenu = $availableMenus->first() ?? $menus->first();
    @endphp

    <div class="mx-auto min-h-screen w-full overflow-hidden bg-[#E8CF98] shadow-[0_0_60px_rgba(0,0,0,0.35)] md:max-w-none md:shadow-none">
        <header class="flex h-16 items-center justify-between border-b border-[#6E4439]/20 bg-[#F8F2E8] px-5 sm:px-8 lg:h-[72px] lg:px-16 xl:px-24">
            <a href="{{ url('/') }}" class="flex items-center gap-3" aria-label="Kembali ke halaman utama">
                <img src="{{ asset('depan/logo.png') }}" alt="Logo Coffe Ridho" class="h-10 w-10 object-contain">
                <span class="text-base font-black uppercase leading-[0.85] tracking-wider text-[#321B18]">Coffe<br>Ridho</span>
            </a>
            <div class="flex items-center gap-4">
                <span class="hidden text-sm font-bold uppercase tracking-[0.18em] text-[#8B594A] sm:block">Menu Web</span>
                <a href="{{ route('login') }}" class="rounded-lg bg-[#E9694A] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#C84E37] active:translate-y-px">Login Kasir</a>
            </div>
        </header>

        <main>
            <section class="relative isolate min-h-[280px] overflow-hidden bg-[#2D2623] sm:min-h-[320px] lg:min-h-[330px]">
                <img src="{{ asset('depan/fotoditempatputih.png') }}" alt="Es kopi susu Coffe Ridho" class="absolute inset-0 h-full w-full object-cover opacity-90">
                <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(38,25,21,0.82)_0%,rgba(38,25,21,0.18)_52%,rgba(38,25,21,0.72)_100%)]"></div>
                <div class="relative flex min-h-[280px] items-center justify-between gap-5 px-6 py-10 text-[#FFF8EC] sm:min-h-[320px] sm:px-12 lg:min-h-[330px] lg:px-16 xl:px-24">
                    <div class="max-w-[11rem] sm:max-w-xs">
                        <p class="text-4xl font-black uppercase leading-[0.82] tracking-tight sm:text-6xl">Coffee</p>
                        <p class="mt-3 text-sm font-semibold uppercase tracking-[0.18em] text-[#F1D49A]">Diracik saat dipesan</p>
                    </div>
                    <div class="max-w-[9rem] text-right sm:max-w-xs">
                        <p class="text-4xl font-black uppercase leading-[0.82] tracking-tight sm:text-6xl">Now</p>
                        <p class="mt-3 text-lg font-semibold italic leading-[1.15] text-[#F1D49A] sm:text-2xl">Saatnya ngopi</p>
                    </div>
                </div>
            </section>

            @if ($menus->isEmpty())
                <section class="px-5 py-20 text-center sm:px-10">
                    <div class="mx-auto max-w-xl rounded-xl border-2 border-dashed border-[#A57B5A] bg-[#F7EBD1] px-6 py-14">
                        <h1 class="text-3xl font-black uppercase text-[#5B302B]">Menu belum tersedia</h1>
                        <p class="mt-2 text-base font-semibold text-[#825B4D]">Silakan kembali lagi setelah katalog diperbarui.</p>
                    </div>
                </section>
            @else
                @if ($promoMenus->isNotEmpty())
                    <section class="relative mx-auto max-w-7xl px-5 pb-8 pt-7 sm:px-8 lg:px-12 lg:pb-10 lg:pt-9">
                        <div class="absolute -left-5 top-5 h-14 w-14 rounded-full bg-[#C9AA6F]/70"></div>
                        <div class="relative grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @foreach ($promoMenus as $index => $menu)
                                <article class="grid grid-cols-[1fr_92px] items-center overflow-hidden rounded-lg border-2 border-[#8A5D4A] bg-[#F8F1E5] shadow-[3px_3px_0_#B79562] sm:grid-cols-[1fr_120px] lg:grid-cols-[1fr_150px]">
                                    <div class="p-4 sm:p-5">
                                        <span class="inline-block rounded-md bg-[#F06D67] px-2 py-1 text-xs font-black uppercase tracking-wide text-white">{{ $index === 0 ? 'New' : 'Pilihan' }}</span>
                                        <h1 class="mt-4 text-2xl font-black leading-none text-[#9D3F3C]">{{ $menu->nama_menu }}</h1>
                                        <p class="mt-2 text-lg font-black text-[#5B302B]">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex h-full min-h-36 items-center justify-center bg-[#EAD7B1]">
                                        @if ($menu->foto)
                                            <img src="{{ asset('storage/'.$menu->foto) }}" alt="{{ $menu->nama_menu }}" class="h-full w-full object-cover">
                                        @else
                                            <span class="text-3xl font-black text-[#A97F59]">{{ strtoupper(substr($menu->nama_menu, 0, 2)) }}</span>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                <div class="mx-auto max-w-7xl md:px-8 xl:grid xl:grid-cols-2 xl:gap-8 xl:px-12 xl:py-10">
                    @foreach ([
                        ['title' => 'Menu Kopi', 'menus' => $coffeeMenus],
                        ['title' => 'Menu Non Kopi', 'menus' => $nonCoffeeMenus],
                    ] as $section)
                        @if ($section['menus']->isNotEmpty())
                            <section class="px-5 py-7 sm:px-0 xl:py-0">
                            <div class="mb-4 flex items-end gap-3 border-b-2 border-[#9E7652] pb-2">
                                <h2 class="text-3xl font-black uppercase leading-none text-[#FFF9ED] [text-shadow:2px_2px_0_#9E7652]">{{ $section['title'] }}</h2>
                                <span class="pb-0.5 text-lg font-semibold italic text-[#74493D]">Coffe Ridho</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 md:grid-cols-2">
                                @foreach ($section['menus'] as $menu)
                                    <article class="overflow-hidden rounded-lg border-2 border-[#A07753] bg-[#F8EED9] shadow-[2px_2px_0_#B79661] md:grid md:grid-cols-[104px_1fr] {{ $menu->status_ketersediaan === 'habis' ? 'opacity-70' : '' }}">
                                        <div class="relative h-28 bg-[#E6D2AA] sm:h-36 md:h-full md:min-h-32">
                                            @if ($menu->foto)
                                                <img src="{{ asset('storage/'.$menu->foto) }}" alt="{{ $menu->nama_menu }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full items-center justify-center text-2xl font-black text-[#B28B62]">{{ strtoupper(substr($menu->nama_menu, 0, 2)) }}</div>
                                            @endif
                                        </div>
                                        <div class="p-3 md:flex md:flex-col md:justify-center">
                                            <div class="flex items-start justify-between gap-2">
                                                <h3 class="text-base font-black leading-tight text-[#8E403A] sm:text-lg">{{ $menu->nama_menu }}</h3>
                                                <span class="rounded-md px-1.5 py-0.5 text-[10px] font-black uppercase text-white {{ $menu->status_ketersediaan === 'habis' ? 'bg-[#8B2626]' : 'bg-[#E9694A]' }}">{{ $menu->status_ketersediaan === 'habis' ? 'Habis' : 'Ready' }}</span>
                                            </div>
                                            <p class="mt-2 font-black text-[#5B302B]">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                            </section>
                        @endif
                    @endforeach
                </div>

                @if ($featuredMenu)
                    <section class="mx-auto max-w-7xl px-5 py-8 sm:px-8 lg:px-12 lg:py-10">
                        <div class="grid overflow-hidden rounded-lg border-2 border-[#8A5D4A] bg-[#F5E9D6] shadow-[4px_4px_0_#B79562] sm:grid-cols-[1.1fr_1fr]">
                            <div class="min-h-56 bg-[#E5CE9F]">
                                @if ($featuredMenu->foto)
                                    <img src="{{ asset('storage/'.$featuredMenu->foto) }}" alt="{{ $featuredMenu->nama_menu }}" class="h-full w-full object-cover">
                                @else
                                    <img src="{{ asset('depan/fotoditempatputih.png') }}" alt="Minuman pilihan Coffe Ridho" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <div class="flex flex-col items-center justify-center p-8 text-center">
                                <p class="text-3xl font-black uppercase text-[#B08A50]">Pilihan hari ini</p>
                                <h2 class="mt-5 rounded-lg border-2 border-[#9E7652] bg-[#E3C886] px-5 py-3 text-2xl font-black uppercase text-[#7C583B]">{{ $featuredMenu->nama_menu }}</h2>
                                <p class="mt-4 text-xl font-black">Rp {{ number_format($featuredMenu->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </section>
                @endif

                @if ($foodMenus->isNotEmpty())
                    <section class="mx-auto max-w-7xl px-5 pb-12 pt-7 sm:px-8 lg:px-12 lg:pb-14 lg:pt-10">
                        <div class="mb-4 flex items-end gap-3 border-b-2 border-[#9E7652] pb-2">
                            <h2 class="text-3xl font-black uppercase leading-none text-[#FFF9ED] [text-shadow:2px_2px_0_#9E7652]">Makanan & Dessert</h2>
                        </div>
                        <div class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-4">
                            @foreach ($foodMenus as $menu)
                                <article class="rounded-lg border-2 border-[#A07753] bg-[#F8EED9] p-3 shadow-[2px_2px_0_#B79661] {{ $menu->status_ketersediaan === 'habis' ? 'opacity-70' : '' }}">
                                    <div class="mb-3 h-24 overflow-hidden rounded-md bg-[#E6D2AA]">
                                        @if ($menu->foto)
                                            <img src="{{ asset('storage/'.$menu->foto) }}" alt="{{ $menu->nama_menu }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full items-center justify-center text-xl font-black text-[#B28B62]">{{ strtoupper(substr($menu->nama_menu, 0, 2)) }}</div>
                                        @endif
                                    </div>
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h3 class="text-base font-black leading-tight text-[#8E403A]">{{ $menu->nama_menu }}</h3>
                                            <p class="mt-1 font-black text-[#5B302B]">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <span class="rounded-md px-1.5 py-0.5 text-[10px] font-black uppercase text-white {{ $menu->status_ketersediaan === 'habis' ? 'bg-[#8B2626]' : 'bg-[#E9694A]' }}">{{ $menu->status_ketersediaan === 'habis' ? 'Habis' : 'Ready' }}</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif
        </main>

        <footer class="border-t border-[#6E4439]/20 bg-[#F8F2E8] px-5 py-5 text-center text-sm font-bold text-[#74493D]">
            Coffe Ridho, kopi enak untuk hari yang lebih hangat.
        </footer>
    </div>
</body>
</html>
