@php
    $historyPayload = $transaksis->getCollection()->map(function ($transaksi) {
        $subtotal = (int) $transaksi->detailTransaksi->sum('subtotal');

        return [
            'id' => $transaksi->id,
            'code' => sprintf('TRX-%03d', $transaksi->id),
            'date' => \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y, H:i'),
            'cashier' => $transaksi->user->name,
            'payment' => strtoupper($transaksi->metode_pembayaran ?? '-'),
            'subtotal' => $subtotal,
            'tax' => max(0, (int) $transaksi->total_harga - $subtotal),
            'total' => (int) $transaksi->total_harga,
            'url' => route('transactions.show', $transaksi),
            'items' => $transaksi->detailTransaksi->map(fn ($detail) => [
                'name' => $detail->menu?->nama_menu ?? 'Menu terhapus',
                'quantity' => (int) $detail->jumlah,
                'price' => $detail->jumlah > 0 ? intdiv((int) $detail->subtotal, (int) $detail->jumlah) : 0,
                'subtotal' => (int) $detail->subtotal,
            ])->values(),
        ];
    })->values();
@endphp

<x-app-layout :hideNavigation="true">
    <div class="profile-shell min-h-[100dvh] bg-[#FAF9F5] font-mono"
         x-data="historyPage(@js($historyPayload))">
        <x-profile-sidebar active="riwayat" />

        <main class="min-w-0 flex-1 overflow-x-hidden bg-[#FAF9F5] px-4 py-5 sm:px-7 lg:px-9 lg:py-7">
            <div class="mx-auto max-w-[1380px]">
                <header class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold tracking-wide text-[#1E1B18] sm:text-3xl">Riwayat</h1>
                        <p class="mt-1 text-[11px] text-[#5F5A52] sm:text-xs">Laporan Penjualan / Riwayat transaksi</p>
                    </div>
                    <div class="flex items-center gap-2 rounded-full bg-[#ECE9E3] px-3 py-2 sm:min-w-[170px]">
                        <span class="grid h-7 w-7 place-items-center rounded-full bg-[#77635A] text-[10px] font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 1)) }}
                        </span>
                        <span class="hidden min-w-0 sm:block">
                            <strong class="block truncate text-[11px] text-[#1E1B18]">{{ auth()->user()->name }}</strong>
                            <small class="block text-[9px] text-[#625D56]">{{ auth()->user()->role ?? 'Kasir' }} Shift {{ auth()->user()->shift ?? '1' }}</small>
                        </span>
                    </div>
                </header>

                <form method="GET" action="{{ route('transactions.index') }}" class="mb-5 rounded-2xl bg-[#EFE7DD] p-3 shadow-sm sm:p-4">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="mr-1 text-[10px] font-bold text-[#5D554C]">Periode</span>
                        @foreach ([
                            '' => 'Semua',
                            'today' => 'Hari ini',
                            'yesterday' => 'Kemarin',
                            'week' => '1 Minggu',
                            'month' => 'Bulan ini',
                            'custom' => 'Custom Tanggal',
                        ] as $value => $label)
                            <a href="{{ request()->fullUrlWithQuery(['period' => $value, 'page' => null]) }}"
                                class="rounded-lg border px-3 py-1.5 text-[10px] font-bold transition {{ request('period', '') === $value ? 'border-[#A88B5D] bg-[#D9C5A5] text-[#1E1B18]' : 'border-[#DED6CB] bg-white text-[#625D56] hover:border-[#BDA780]' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    <input type="hidden" name="period" value="{{ request('period') }}">
                    <div class="grid gap-2 sm:grid-cols-2 xl:grid-cols-[minmax(260px,1.5fr)_minmax(150px,.65fr)_minmax(150px,.65fr)_auto]">
                        <label class="relative block">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[#8C847A]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                            </span>
                            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari No. Struk atau kasir"
                                class="h-9 w-full rounded-lg border-[#DDD5CA] bg-white pl-9 pr-3 text-[10px] placeholder:text-[#948D84] focus:border-[#A88B5D] focus:ring-[#A88B5D]">
                        </label>

                        <select name="payment" class="h-9 rounded-lg border-[#DDD5CA] bg-white px-3 text-[10px] focus:border-[#A88B5D] focus:ring-[#A88B5D]">
                            <option value="">Metode Pembayaran</option>
                            <option value="cash" @selected(request('payment') === 'cash')>Cash</option>
                            <option value="qris" @selected(request('payment') === 'qris')>QRIS</option>
                        </select>

                        <select name="cashier" class="h-9 rounded-lg border-[#DDD5CA] bg-white px-3 text-[10px] focus:border-[#A88B5D] focus:ring-[#A88B5D]">
                            <option value="">Semua kasir</option>
                            @foreach ($cashiers as $cashier)
                                <option value="{{ $cashier->id }}" @selected((string) request('cashier') === (string) $cashier->id)>{{ $cashier->name }}</option>
                            @endforeach
                        </select>

                        <div class="flex gap-2">
                            <button class="h-9 flex-1 rounded-lg bg-[#A88B5D] px-4 text-[10px] font-bold text-white hover:bg-[#967B4E]">Terapkan</button>
                            @if (request()->query())
                                <a href="{{ route('transactions.index') }}" class="grid h-9 w-9 place-items-center rounded-lg border border-[#D6CDC1] bg-white text-[#6E675F]" aria-label="Reset filter">×</a>
                            @endif
                        </div>
                    </div>

                    <div x-show="'{{ request('period') }}' === 'custom'" class="mt-2 grid gap-2 sm:grid-cols-2" x-cloak>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="h-9 rounded-lg border-[#DDD5CA] bg-white text-[10px]">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="h-9 rounded-lg border-[#DDD5CA] bg-white text-[10px]">
                    </div>
                </form>

                <section class="overflow-hidden rounded-2xl border border-[#E3DED6] bg-white shadow-sm">
                    <div class="flex items-end justify-between gap-4 border-b border-[#E8E3DC] px-4 py-4 sm:px-5">
                        <div>
                            <h2 class="text-sm font-bold text-[#1E1B18] sm:text-base">Total {{ $transaksis->total() }} Transaksi</h2>
                            <p class="mt-0.5 text-[9px] text-[#827B72]">Menampilkan riwayat transaksi terbaru</p>
                        </div>
                        <button type="button" @click="exportOpen = true" class="inline-flex items-center gap-2 rounded-lg border border-[#D9D2C9] bg-white px-3 py-2 text-[10px] font-bold text-[#49443E] hover:bg-[#F6F3EE]">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>
                            Export
                        </button>
                    </div>

                    @if ($transaksis->isEmpty())
                        <div class="grid min-h-[360px] place-items-center px-6 py-12 text-center">
                            <div>
                                <span class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-[#F1E8DA] text-[#A88B5D]">
                                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 3v5h5"/><path d="M4 13a8 8 0 1 0 2-7"/><path d="M12 7v5l3 2"/></svg>
                                </span>
                                <h3 class="mt-4 text-sm font-bold text-[#26211C]">Belum ada transaksi</h3>
                                <p class="mx-auto mt-1 max-w-sm text-[11px] leading-5 text-[#777067]">Transaksi yang berhasil disimpan akan tampil di halaman riwayat.</p>
                                @if (request()->query())
                                    <a href="{{ route('transactions.index') }}" class="mt-4 inline-flex rounded-lg bg-[#A88B5D] px-4 py-2 text-[10px] font-bold text-white">Hapus filter</a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="hidden overflow-x-auto md:block">
                            <table class="w-full min-w-[800px] text-left text-[10px]">
                                <thead class="bg-[#E6E4E0] text-[#403B35]">
                                    <tr>
                                        <th class="px-5 py-2.5 font-bold">No. Struk</th>
                                        <th class="px-3 py-2.5 font-bold">Tanggal dan waktu</th>
                                        <th class="px-3 py-2.5 font-bold">Kasir</th>
                                        <th class="px-3 py-2.5 font-bold">Total</th>
                                        <th class="px-3 py-2.5 font-bold">Metode Pembayaran</th>
                                        <th class="px-3 py-2.5 font-bold">Status</th>
                                        <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#EEEAE4]">
                                    @foreach ($transaksis as $transaksi)
                                        <tr class="odd:bg-white even:bg-[#FAF9F7] hover:bg-[#F5EFE6]">
                                            <td class="px-5 py-2 font-bold text-[#28231E]">TRX-{{ str_pad($transaksi->id, 3, '0', STR_PAD_LEFT) }}</td>
                                            <td class="px-3 py-2 text-[#554F48]">{{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d M Y, H:i') }}</td>
                                            <td class="px-3 py-2">{{ $transaksi->user->name }}</td>
                                            <td class="px-3 py-2 font-bold">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                            <td class="px-3 py-2 uppercase">{{ $transaksi->metode_pembayaran ?? '-' }}</td>
                                            <td class="px-3 py-2"><span class="rounded-full bg-[#CFF0D5] px-2 py-1 text-[9px] font-bold text-[#287E36]">Selesai</span></td>
                                            <td class="px-5 py-2 text-right">
                                                <a href="{{ route('transactions.show', $transaksi) }}" @click.prevent="openDetail({{ $transaksi->id }})" class="rounded-full border border-[#BFB8AF] bg-white px-3 py-1 text-[9px] font-bold hover:bg-[#EFE9E0]">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="divide-y divide-[#ECE7E0] md:hidden">
                            @foreach ($transaksis as $transaksi)
                                <a href="{{ route('transactions.show', $transaksi) }}" @click.prevent="openDetail({{ $transaksi->id }})" class="block p-4 hover:bg-[#F8F4EE]">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <strong class="text-xs text-[#25211C]">TRX-{{ str_pad($transaksi->id, 3, '0', STR_PAD_LEFT) }}</strong>
                                            <p class="mt-1 text-[9px] text-[#777067]">{{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d M Y, H:i') }}</p>
                                        </div>
                                        <span class="rounded-full bg-[#CFF0D5] px-2 py-1 text-[8px] font-bold text-[#287E36]">Selesai</span>
                                    </div>
                                    <div class="mt-3 flex items-end justify-between gap-3">
                                        <div class="text-[9px] text-[#6A635B]">
                                            <p>{{ $transaksi->user->name }} · {{ strtoupper($transaksi->metode_pembayaran ?? '-') }}</p>
                                            <strong class="mt-1 block text-xs text-[#2C2721]">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong>
                                        </div>
                                        <span class="text-[9px] font-bold text-[#92764D]">Lihat detail →</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($transaksis->hasPages())
                        @php
                            $startPage = max(1, $transaksis->currentPage() - 1);
                            $endPage = min($transaksis->lastPage(), $transaksis->currentPage() + 1);

                            if ($transaksis->currentPage() === 1) {
                                $endPage = min($transaksis->lastPage(), 3);
                            }

                            if ($transaksis->currentPage() === $transaksis->lastPage()) {
                                $startPage = max(1, $transaksis->lastPage() - 2);
                            }
                        @endphp

                        <nav class="flex flex-col gap-3 border-t border-[#E8E3DC] px-4 py-4 sm:flex-row sm:items-center sm:justify-between" aria-label="Pagination riwayat transaksi">
                            <p class="text-[10px] text-[#6A635B]">
                                Menampilkan
                                <strong class="text-[#2C2721]">{{ $transaksis->firstItem() }}</strong>
                                -
                                <strong class="text-[#2C2721]">{{ $transaksis->lastItem() }}</strong>
                                dari
                                <strong class="text-[#2C2721]">{{ $transaksis->total() }}</strong>
                                transaksi
                            </p>

                            <div class="flex flex-wrap items-center gap-1.5">
                                @if ($transaksis->onFirstPage())
                                    <span class="grid h-8 w-8 cursor-not-allowed place-items-center rounded-lg border border-[#E1DAD1] bg-[#F4F0EA] text-[11px] font-bold text-[#B6ADA3]">&lsaquo;</span>
                                @else
                                    <a href="{{ $transaksis->previousPageUrl() }}" class="grid h-8 w-8 place-items-center rounded-lg border border-[#D7CFC4] bg-white text-[11px] font-bold text-[#4E463E] transition hover:bg-[#F5EFE6]">&lsaquo;</a>
                                @endif

                                @if ($startPage > 1)
                                    <a href="{{ $transaksis->url(1) }}" class="grid h-8 min-w-8 place-items-center rounded-lg border border-[#D7CFC4] bg-white px-2 text-[11px] font-bold text-[#4E463E] transition hover:bg-[#F5EFE6]">1</a>
                                    @if ($startPage > 2)
                                        <span class="grid h-8 min-w-8 place-items-center px-1 text-[11px] font-bold text-[#8A8178]">...</span>
                                    @endif
                                @endif

                                @foreach ($transaksis->getUrlRange($startPage, $endPage) as $page => $url)
                                    @if ($page === $transaksis->currentPage())
                                        <span class="grid h-8 min-w-8 place-items-center rounded-lg bg-[#A88B5D] px-2 text-[11px] font-bold text-white">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="grid h-8 min-w-8 place-items-center rounded-lg border border-[#D7CFC4] bg-white px-2 text-[11px] font-bold text-[#4E463E] transition hover:bg-[#F5EFE6]">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if ($endPage < $transaksis->lastPage())
                                    @if ($endPage < $transaksis->lastPage() - 1)
                                        <span class="grid h-8 min-w-8 place-items-center px-1 text-[11px] font-bold text-[#8A8178]">...</span>
                                    @endif
                                    <a href="{{ $transaksis->url($transaksis->lastPage()) }}" class="grid h-8 min-w-8 place-items-center rounded-lg border border-[#D7CFC4] bg-white px-2 text-[11px] font-bold text-[#4E463E] transition hover:bg-[#F5EFE6]">{{ $transaksis->lastPage() }}</a>
                                @endif

                                @if ($transaksis->hasMorePages())
                                    <a href="{{ $transaksis->nextPageUrl() }}" class="grid h-8 w-8 place-items-center rounded-lg border border-[#D7CFC4] bg-white text-[11px] font-bold text-[#4E463E] transition hover:bg-[#F5EFE6]">&rsaquo;</a>
                                @else
                                    <span class="grid h-8 w-8 cursor-not-allowed place-items-center rounded-lg border border-[#E1DAD1] bg-[#F4F0EA] text-[11px] font-bold text-[#B6ADA3]">&rsaquo;</span>
                                @endif
                            </div>
                        </nav>
                    @endif
                </section>
            </div>
        </main>

        <div x-show="detailOpen" x-cloak @keydown.escape.window="detailOpen = false" class="fixed inset-0 z-50 grid place-items-center bg-black/40 p-4 backdrop-blur-[2px]">
            <div @click.outside="detailOpen = false" class="max-h-[92dvh] w-full max-w-[720px] overflow-y-auto rounded-lg bg-white shadow-2xl">
                <template x-if="selected">
                    <div>
                        <div class="flex items-start justify-between px-8 pb-3 pt-6">
                            <div>
                                <h2 class="text-lg font-bold">Detail Transaksi</h2>
                                <p class="mt-1 text-[11px] text-[#6F675F]">informasi lengkap transaksi penjualan</p>
                            </div>
                            <button @click="detailOpen = false" class="text-xl leading-none text-[#2C2721]">&times;</button>
                        </div>
                        <div class="grid gap-6 px-8 pb-4 pt-3 sm:grid-cols-[minmax(0,1fr)_210px]">
                            <div class="min-w-0">
                                <div class="rounded-xl bg-[#F7F1E8] p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-[#DFC793] text-[#9B804E]">
                                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M6 3h12v18H6V3Z" stroke="currentColor" stroke-width="2" />
                                                <path d="M9 7h6M9 11h6M9 15h3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-[11px] text-[#6F675F]">No. Struk</p>
                                                    <strong class="block text-base" x-text="selected.code"></strong>
                                                </div>
                                                <span class="rounded-full bg-[#CFF2D8] px-3 py-1 text-[9px] font-bold text-[#287E36]">selesai</span>
                                            </div>
                                            <p class="mt-2 text-right text-[10px] text-[#4F4841]" x-text="selected.date"></p>
                                        </div>
                                    </div>
                                </div>

                                <dl class="mt-3 grid gap-y-2 text-[11px] text-[#2C2721] [grid-template-columns:24px_128px_minmax(0,1fr)]">
                                    <dt class="text-[#2C2721]"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 4h10M7 20h10M6 7h12v10H6V7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></dt>
                                    <dt class="text-[#6F675F]">Periode Tanggal</dt>
                                    <dd class="font-semibold" x-text="selected.date"></dd>
                                    <dt class="text-[#2C2721]"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM4 21a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></dt>
                                    <dt class="text-[#6F675F]">Kasir</dt>
                                    <dd class="font-semibold" x-text="selected.cashier"></dd>
                                    <dt class="text-[#2C2721]"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM16 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3 20a5 5 0 0 1 10 0M11 20a5 5 0 0 1 10 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></dt>
                                    <dt class="text-[#6F675F]">Pelanggan</dt>
                                    <dd class="font-semibold">Pelanggan Umum</dd>
                                    <dt class="text-[#2C2721]"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7h18v10H3V7ZM3 11h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></dt>
                                    <dt class="text-[#6F675F]">Metode Pembayaran</dt>
                                    <dd class="font-semibold" x-text="selected.payment"></dd>
                                    <dt class="text-[#2C2721]"><svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8 12.5 10.5 15 16 9M5 4h14v16H5V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></dt>
                                    <dt class="text-[#6F675F]">status</dt>
                                    <dd><span class="rounded-full bg-[#CFF2D8] px-3 py-0.5 text-[9px] font-bold text-[#287E36]">selesai</span></dd>
                                </dl>

                                <h3 class="mb-1 mt-4 border-t border-[#D8D0C6] pt-2 text-base font-bold leading-none">Daftar Item</h3>
                                <div class="overflow-hidden border border-[#E4DED6]">
                                    <table class="w-full text-left text-[10px]">
                                        <thead class="bg-[#DCD8D1]">
                                            <tr>
                                                <th class="w-9 px-2 py-1.5">No.</th>
                                                <th class="px-2 py-1.5">Nama Barang</th>
                                                <th class="px-2 py-1.5">Qty</th>
                                                <th class="px-2 py-1.5">Harga</th>
                                                <th class="px-2 py-1.5 text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-[#EEEAE4]">
                                            <template x-for="item in selected.items" :key="item.name">
                                                <tr>
                                                    <td class="px-2 py-1.5" x-text="selected.items.indexOf(item) + 1"></td>
                                                    <td class="px-2 py-1.5" x-text="item.name"></td>
                                                    <td class="px-2 py-1.5" x-text="item.quantity"></td>
                                                    <td class="px-2 py-1.5" x-text="rupiah(item.price)"></td>
                                                    <td class="px-2 py-1.5 text-right" x-text="rupiah(item.subtotal)"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <aside class="min-h-[342px] border-[6px] border-[#E5E2DD] bg-[#FFFCF7] p-4 text-[8.5px] shadow-sm">
                                <div class="mb-3 border-b border-dashed border-[#2C2721] pb-3 text-center">
                                    <img src="{{ asset('depan/logo.png') }}" class="mx-auto h-11 object-contain" alt="Coffe Ridho">
                                    <p class="mt-1 text-[7px] leading-tight text-[#6F675F]">Jl. way huwi No. 12, Lampung</p>
                                    <p class="text-[7px] leading-tight text-[#6F675F]">No Telp. 0894 0841 4151</p>
                                </div>
                                <h4 class="text-center text-[9px] font-bold">Struk Penjualan</h4>
                                <dl class="mt-2 grid grid-cols-[44px_1fr] gap-y-1 text-[7px]">
                                    <dt>No. Struk</dt><dd>: <span x-text="selected.code"></span></dd>
                                    <dt>Tanggal</dt><dd>: <span x-text="selected.date"></span></dd>
                                    <dt>Kasir</dt><dd>: <span x-text="selected.cashier"></span></dd>
                                    <dt>Pembayaran</dt><dd>: <span x-text="selected.payment"></span></dd>
                                </dl>
                                <div class="my-2 border-t border-dashed border-[#2C2721]"></div>
                                <template x-for="item in selected.items" :key="`receipt-${item.name}`">
                                    <div class="mb-1 flex justify-between gap-2">
                                        <span><span x-text="item.quantity"></span>x <span x-text="item.name"></span></span>
                                        <span x-text="rupiah(item.subtotal)"></span>
                                    </div>
                                </template>
                                <div class="mt-2 space-y-1 border-t border-dashed border-[#2C2721] pt-2">
                                    <p class="flex justify-between"><span>Subtotal</span><span x-text="rupiah(selected.subtotal)"></span></p>
                                    <p class="flex justify-between"><span>Pajak (PPN 10%)</span><span x-text="rupiah(selected.tax)"></span></p>
                                    <p class="flex justify-between border-t border-dashed border-[#2C2721] pt-1 text-[11px] font-bold"><span>TOTAL</span><span x-text="rupiah(selected.total)"></span></p>
                                </div>
                            </aside>
                        </div>
                        <div class="flex flex-wrap justify-end gap-3 px-8 pb-6 pt-1">
                            <button @click="window.print()" class="inline-flex items-center gap-2 rounded-md border border-[#D8D0C6] bg-white px-5 py-2 text-[11px] font-bold text-[#2C2721]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 9V3h12v6M6 17H4a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2M6 14h12v7H6v-7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                Cetak Struk
                            </button>
                            <a :href="selected.url" class="inline-flex items-center gap-2 rounded-md border border-[#D8D0C6] bg-white px-5 py-2 text-[11px] font-bold text-[#2C2721]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v12M8 11l4 4 4-4M5 21h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Download PDF
                            </a>
                            <button @click="detailOpen = false" class="rounded-md bg-[#B5955D] px-8 py-2 text-[11px] font-bold text-white">Tutup</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div x-show="exportOpen" x-cloak @keydown.escape.window="exportOpen = false" class="fixed inset-0 z-50 grid place-items-center bg-black/40 p-4 backdrop-blur-[2px]">
            <div @click.outside="exportOpen = false" class="w-full max-w-[480px] rounded-md bg-white p-6 shadow-2xl">
                <div>
                    <h2 class="text-base font-bold">Export Riwayat Transaksi</h2>
                    <p class="mt-2 text-[11px] text-[#6F675F]">Pilih format file dan rentang data yang ingin diekspor</p>
                </div>

                <div class="mt-5">
                    <p class="mb-2 text-[10px] font-bold text-[#2C2721]">Format File</p>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex min-h-[64px] cursor-pointer items-center gap-3 rounded-md border border-[#D8D0C6] bg-white p-3">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded bg-[#5CD85F] text-white shadow-sm">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M6 3h8l4 4v14H6V3Z" fill="currentColor" opacity=".22" />
                                    <path d="M14 3v5h5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                    <path d="m8 10 4 6M12 10l-4 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </span>
                            <span>
                                <strong class="block text-[11px]">Excel (XLSX)</strong>
                                <span class="block text-[8px] leading-tight text-[#6F675F]">Cocok untuk olah data lebih lanjut</span>
                            </span>
                            <input type="radio" name="export_format_preview" class="absolute right-2 top-2 h-3 w-3 border-[#D8D0C6] text-[#B5955D] focus:ring-[#B5955D]" checked>
                        </label>
                        <label class="relative flex min-h-[64px] cursor-pointer items-center gap-3 rounded-md border border-[#D8D0C6] bg-white p-3">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded bg-[#EF4E47] text-white shadow-sm">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M6 3h8l4 4v14H6V3Z" fill="currentColor" opacity=".25" />
                                    <path d="M14 3v5h5M8 16c3-6 4-7 5-2 1 4 2 3 3 1M8 16c2-.7 5-1 8-1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <span>
                                <strong class="block text-[11px]">PDF</strong>
                                <span class="block text-[8px] leading-tight text-[#6F675F]">Siap untuk dicetak</span>
                            </span>
                            <input type="radio" name="export_format_preview" class="absolute right-2 top-2 h-3 w-3 border-[#D8D0C6] text-[#B5955D] focus:ring-[#B5955D]">
                        </label>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="mb-2 text-[10px] font-bold text-[#2C2721]">Rentang Data</p>
                    <label class="flex items-center gap-2 text-[10px] text-[#2C2721]">
                        <input type="radio" name="export_range_preview" class="h-3.5 w-3.5 border-[#D8D0C6] text-[#B5955D] focus:ring-[#B5955D]">
                        <span>Data yang sedang ditampilkan ({{ $transaksis->count() }} transaksi)</span>
                    </label>
                    <label class="mt-2 flex items-center gap-2 text-[10px] text-[#2C2721]">
                        <input type="radio" name="export_range_preview" class="h-3.5 w-3.5 border-[#D8D0C6] text-[#B5955D] focus:ring-[#B5955D]">
                        <span>Semua data sesuai filter ({{ $transaksis->total() }} transaksi)</span>
                    </label>
                </div>

                <div class="mt-4 flex gap-2 bg-[#FFF2DA] p-3 text-[9px] leading-relaxed text-[#6A5530]">
                    <span class="grid h-4 w-4 shrink-0 place-items-center rounded-full border border-[#C9A766] text-[10px] font-bold">!</span>
                    <p>File akan diekspor akan mengikuti filter yang sedang aktif (periode, metode pembayaran, dan kasir)</p>
                </div>

                <div class="mt-4 flex justify-end gap-3">
                    <button @click="exportOpen = false" class="rounded-md border border-[#D8D0C6] bg-white px-6 py-2 text-[10px] font-bold text-[#2C2721]">Batal</button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-md bg-[#B5955D] px-6 py-2 text-[10px] font-bold text-white">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v12M8 11l4 4 4-4M5 21h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function historyPage(transactions) {
            return {
                transactions,
                detailOpen: false,
                exportOpen: false,
                selected: null,
                openDetail(id) {
                    this.selected = this.transactions.find((transaction) => transaction.id === id);
                    this.detailOpen = Boolean(this.selected);
                },
                rupiah(value) {
                    return `Rp ${Number(value || 0).toLocaleString('id-ID')}`;
                },
            };
        }
    </script>
</x-app-layout>
