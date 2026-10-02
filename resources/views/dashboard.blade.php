<x-app-layout :hideNavigation="true">
    <div class="profile-shell min-h-[100dvh] bg-[#FAF9F5] font-mono">
        <x-profile-sidebar active="dashboard" />

        <main class="min-w-0 flex-1 overflow-y-auto bg-[#FAF9F5] px-4 py-5 sm:px-7 lg:px-9 lg:py-7">
            <div class="mx-auto max-w-[1380px]">
                <header class="mb-5 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold tracking-wide text-[#171410] sm:text-3xl">Dashboard</h1>
                        <p class="mt-1 text-[11px] tracking-wide text-[#4D4943] sm:text-xs">Performa Penjualan Kasir</p>
                        <p class="mt-1 text-[10px] text-[#756D64] sm:text-[11px]">Periode: {{ $periodLabel }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="flex min-w-[210px] items-center gap-3 rounded-full bg-[#E7E6E4] px-3 py-1.5 transition hover:bg-[#DDDAD6] focus:outline-none focus:ring-2 focus:ring-[#9B7B3F] focus:ring-offset-2">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-full bg-[#79615D] text-white">
                            @if ($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-sm font-bold">{{ str($user->name)->substr(0, 1)->upper() }}</span>
                            @endif
                        </div>
                        <span class="leading-tight">
                            <strong class="block text-sm text-[#171410]">{{ $user->name }}</strong>
                            <small class="block text-[10px] text-[#3F3A35]">{{ $user->role ?? 'Kasir' }} Shift {{ $user->shift ?? '1' }}</small>
                        </span>
                    </a>
                </header>

                <div class="grid gap-4 xl:grid-cols-[minmax(0,1.8fr)_minmax(310px,1fr)]">
                    <section class="min-w-0 space-y-4" aria-label="Ringkasan penjualan hari ini">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <article class="rounded-[18px] bg-[#F0E9E1] p-5 lg:min-h-[132px]">
                                <h2 class="text-base font-bold tracking-wide text-[#171410] sm:text-lg">Total Penjualan Hari Ini</h2>
                                <p class="mt-4 text-2xl font-bold tracking-wider text-[#92783F] sm:text-3xl">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</p>
                            </article>

                            <article class="rounded-[18px] bg-[#F0E9E1] p-5 lg:min-h-[132px]">
                                <h2 class="text-base font-bold tracking-wide text-[#171410] sm:text-lg">Total Transaksi</h2>
                                <p class="mt-4 text-2xl font-bold text-[#171410]">{{ $totalTransactions }} <span class="text-sm">/ {{ $dailyTarget }}</span></p>
                                <div class="mt-2 h-4 overflow-hidden rounded-full bg-white" role="progressbar" aria-label="Target transaksi harian" aria-valuenow="{{ $targetPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-[#A52F32] to-[#C88E91]" style="width: {{ $targetPercentage }}%"></div>
                                </div>
                                <div class="mt-2 flex justify-between text-[11px] font-bold"><span>Target Harian</span><span class="text-[#92783F]">{{ number_format($targetPercentage, 1, ',', '.') }}%</span></div>
                            </article>
                        </div>

                        <article class="rounded-[18px] bg-[#F0E9E1] p-4 sm:p-5">
                            @if ($busiestHour)
                                <div class="overflow-x-auto pb-2">
                                    <div class="flex h-[250px] min-w-[620px] items-end gap-2 border-b-2 border-l-2 border-[#514A43] px-2 pt-6 sm:gap-3 sm:px-4" aria-label="Grafik jumlah cup terjual per jam">
                                        @foreach ($hourlySales as $sale)
                                            <div class="group relative flex h-full min-w-0 flex-1 items-end justify-center">
                                                <span class="absolute bottom-[calc(var(--bar-height)+8px)] hidden whitespace-nowrap rounded bg-[#332E29] px-2 py-1 text-[9px] text-white group-hover:block group-focus-within:block">{{ $sale['cups'] }} cup</span>
                                                <div tabindex="0" class="w-full max-w-7 rounded-t-full bg-[#A84142] outline-none ring-[#9B7B3F] focus:ring-2" style="--bar-height: {{ $sale['cups'] > 0 ? max(6, ($sale['cups'] / $chartMaximum) * 88) : 0 }}%; height: var(--bar-height)" aria-label="{{ $sale['hour'] }}, {{ $sale['cups'] }} cup"></div>
                                                <span class="absolute -bottom-6 text-[8px] text-[#765F36] sm:text-[9px]">{{ substr($sale['hour'], 0, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mt-8 rounded-[14px] bg-[#E5D1A5] px-4 py-3 text-center text-xs font-bold sm:text-sm">
                                    Jam Paling Sibuk: {{ $busiestHour['hour'] }} - {{ sprintf('%02d:00', (int) substr($busiestHour['hour'], 0, 2) + 1) }} ({{ $busiestHour['cups'] }} Cup)
                                </div>
                            @else
                                <div class="flex min-h-[295px] flex-col items-center justify-center px-4 text-center">
                                    <div class="flex h-20 w-20 items-end justify-center gap-1 rounded-full bg-[#E5D1A5] p-5 text-[#A84142]" aria-hidden="true"><span class="h-5 w-2 rounded-t-full bg-current"></span><span class="h-9 w-2 rounded-t-full bg-current"></span><span class="h-6 w-2 rounded-t-full bg-current"></span></div>
                                    <h2 class="mt-5 text-lg font-bold text-[#27211C]">{{ $totalTransactions > 0 ? 'Belum ada item terjual' : 'Belum ada penjualan hari ini' }}</h2>
                                    <p class="mt-2 max-w-sm text-xs leading-5 text-[#756D64]">Grafik performa akan tampil setelah item penjualan pertama berhasil disimpan.</p>
                                    <a href="{{ route('menu.index') }}" class="mt-5 rounded-full bg-[#A84142] px-5 py-2.5 text-xs font-bold text-white transition hover:bg-[#8E3233]">Mulai Transaksi</a>
                                </div>
                            @endif
                        </article>
                    </section>

                    <aside class="rounded-[18px] bg-[#F0E9E1] p-4 sm:p-5" aria-labelledby="best-seller-title">
                        <h2 id="best-seller-title" class="text-xl font-bold text-[#171410]">Menu Terlaris</h2>
                        <p class="mt-1 text-xs text-[#4D4943]">Berdasarkan penjualan {{ $periodLabel }}</p>

                        @if ($menuTerlarisHariIni->isNotEmpty())
                            @php($highestSales = max(1, (int) $menuTerlarisHariIni->max('total_terjual')))
                            <div class="mt-4 space-y-3">
                                @foreach ($menuTerlarisHariIni as $menu)
                                    <article class="rounded-[20px] bg-[#DCDDDE] px-4 py-2.5">
                                        <div class="flex items-center gap-3">
                                            <div class="h-11 w-11 shrink-0 rounded-full bg-[#C9A65F]"></div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-start justify-between gap-2 text-xs font-bold sm:text-sm"><h3 class="truncate">{{ $menu->nama_menu }}</h3><span class="shrink-0">Rp {{ number_format($menu->subtotal_penjualan, 0, ',', '.') }}</span></div>
                                                <div class="mt-1 flex justify-between gap-2 text-[10px] text-[#B18C49]"><span class="truncate capitalize">{{ $menu->kategori }}</span><span class="shrink-0">{{ $menu->total_terjual }} Cup Terjual</span></div>
                                            </div>
                                        </div>
                                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-white"><div class="h-full rounded-full bg-[#92783F]" style="width: {{ ((int) $menu->total_terjual / $highestSales) * 100 }}%"></div></div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="flex min-h-[340px] flex-col items-center justify-center px-4 text-center">
                                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#E5D1A5] text-3xl text-[#92783F]" aria-hidden="true">—</div>
                                <h3 class="mt-5 text-base font-bold text-[#27211C]">Belum ada menu terlaris</h3>
                                <p class="mt-2 text-xs leading-5 text-[#756D64]">Peringkat menu akan muncul setelah ada penjualan hari ini.</p>
                            </div>
                        @endif
                    </aside>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
