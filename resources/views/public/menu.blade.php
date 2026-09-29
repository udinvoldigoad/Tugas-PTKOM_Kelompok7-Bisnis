<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Coffe Ridho</title>
    <meta name="description" content="Daftar menu Coffe Ridho beserta harga dan ketersediaannya.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;800;900&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[#F7F3EC] text-[#1A1208]" style="font-family: 'Barlow', sans-serif">
    <header class="border-b border-[#CDBA9F] bg-[#DEC99F]">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-5 sm:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3" aria-label="Kembali ke halaman utama">
                <img src="{{ asset('depan/logo.png') }}" alt="Logo Coffe Ridho" class="h-11 w-11 object-contain">
                <span class="text-lg font-black leading-4 tracking-wider">COFFE<br>RIDHO</span>
            </a>
            <a href="{{ route('login') }}" class="rounded-xl bg-[#E06328] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-[#C9521C]">Login Kasir</a>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-5 py-10 sm:px-8 sm:py-14">
        <div class="mb-8 max-w-2xl">
            <p class="mb-2 text-sm font-bold uppercase tracking-[0.2em] text-[#A07842]">Menu kami</p>
            <h1 class="text-3xl font-black sm:text-4xl" style="font-family: 'Space Mono', monospace">Pilih menu favoritmu</h1>
            <p class="mt-3 text-base text-[#5E5143]">Harga dan status ketersediaan ditampilkan langsung dari sistem.</p>
        </div>

        @if ($menus->isEmpty())
            <div class="rounded-2xl border border-dashed border-[#CDBA9F] bg-white px-6 py-16 text-center">
                <p class="font-bold text-[#5E5143]">Menu belum tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($menus as $menu)
                    <article class="overflow-hidden rounded-2xl border border-[#DED4C5] bg-white shadow-sm {{ $menu->status_ketersediaan === 'habis' ? 'opacity-75' : '' }}">
                        <div class="relative flex h-48 items-center justify-center bg-[#E7E0D6]">
                            @if ($menu->foto)
                                <img src="{{ asset('storage/'.$menu->foto) }}" alt="{{ $menu->nama_menu }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-2xl font-black text-[#A99780]">{{ strtoupper(substr($menu->nama_menu, 0, 2)) }}</span>
                            @endif

                            @if ($menu->status_ketersediaan === 'habis')
                                <span class="absolute left-4 top-4 rounded-full bg-[#8B2626] px-3 py-1 text-xs font-bold text-white">Habis</span>
                            @else
                                <span class="absolute left-4 top-4 rounded-full bg-[#497C45] px-3 py-1 text-xs font-bold text-white">Tersedia</span>
                            @endif
                        </div>

                        <div class="p-5">
                            <p class="text-xs font-bold uppercase tracking-wider text-[#A07842]">{{ $menu->kategori }}</p>
                            <div class="mt-2 flex items-start justify-between gap-4">
                                <h2 class="text-lg font-extrabold">{{ $menu->nama_menu }}</h2>
                                <p class="shrink-0 font-bold">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
