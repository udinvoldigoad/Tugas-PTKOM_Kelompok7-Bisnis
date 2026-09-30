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
                        <div class="border-t border-[#E8E3DC] px-4 py-3">{{ $transaksis->links() }}</div>
                    @endif
                </section>
            </div>
        </main>

        <div x-show="detailOpen" x-cloak @keydown.escape.window="detailOpen = false" class="fixed inset-0 z-50 grid place-items-center bg-black/40 p-4 backdrop-blur-[2px]">
            <div @click.outside="detailOpen = false" class="max-h-[90dvh] w-full max-w-[640px] overflow-y-auto rounded-lg bg-white shadow-2xl">
                <template x-if="selected">
                    <div>
                        <div class="flex items-start justify-between border-b border-[#E8E3DC] px-4 py-3">
                            <div><h2 class="text-sm font-bold">Detail Transaksi</h2><p class="mt-0.5 text-[8px] text-[#777067]">Informasi lengkap transaksi penjualan</p></div>
                            <button @click="detailOpen = false" class="grid h-7 w-7 place-items-center rounded-full bg-[#F1EEE9] text-sm">×</button>
                        </div>
                        <div class="grid gap-4 p-4 sm:grid-cols-[minmax(0,1fr)_180px]">
                            <div>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2 rounded-lg bg-[#F6F2EB] p-3 text-[8px]">
                                    <p><span class="block text-[#817A72]">No. Struk</span><strong x-text="selected.code"></strong></p>
                                    <p><span class="block text-[#817A72]">Status</span><strong class="text-[#287E36]">Selesai</strong></p>
                                    <p><span class="block text-[#817A72]">Tanggal</span><strong x-text="selected.date"></strong></p>
                                    <p><span class="block text-[#817A72]">Kasir</span><strong x-text="selected.cashier"></strong></p>
                                    <p><span class="block text-[#817A72]">Metode Pembayaran</span><strong x-text="selected.payment"></strong></p>
                                </div>
                                <h3 class="mb-1.5 mt-3 text-[10px] font-bold">Daftar Item</h3>
                                <div class="overflow-hidden rounded-lg border border-[#E4DED6]">
                                    <table class="w-full text-left text-[8px]">
                                        <thead class="bg-[#E9E6E1]"><tr><th class="px-3 py-2">Item</th><th class="px-2 py-2">Qty</th><th class="px-2 py-2">Harga</th><th class="px-3 py-2 text-right">Subtotal</th></tr></thead>
                                        <tbody class="divide-y divide-[#EEEAE4]">
                                            <template x-for="item in selected.items" :key="item.name">
                                                <tr><td class="px-3 py-1.5" x-text="item.name"></td><td class="px-2 py-1.5" x-text="item.quantity"></td><td class="px-2 py-1.5" x-text="rupiah(item.price)"></td><td class="px-3 py-1.5 text-right" x-text="rupiah(item.subtotal)"></td></tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <aside class="rounded-lg border border-dashed border-[#CFC5B8] bg-[#FFFCF7] p-3 text-[7px]">
                                <div class="mb-3 flex items-center gap-2 border-b border-dashed border-[#CFC5B8] pb-2"><img src="{{ asset('depan/logo.png') }}" class="h-7 w-7 object-contain" alt=""><div><strong class="text-[8px]">COFFE RIDHO</strong><p class="text-[#7A736B]">Struk transaksi</p></div></div>
                                <template x-for="item in selected.items" :key="`receipt-${item.name}`"><div class="mb-1.5 flex justify-between gap-2"><span><span x-text="item.quantity"></span>× <span x-text="item.name"></span></span><span x-text="rupiah(item.subtotal)"></span></div></template>
                                <div class="mt-3 space-y-1.5 border-t border-dashed border-[#CFC5B8] pt-2"><p class="flex justify-between"><span>Subtotal</span><span x-text="rupiah(selected.subtotal)"></span></p><p class="flex justify-between"><span>PPN 10%</span><span x-text="rupiah(selected.tax)"></span></p><p class="flex justify-between text-[10px] font-bold"><span>Total</span><span x-text="rupiah(selected.total)"></span></p></div>
                            </aside>
                        </div>
                        <div class="flex flex-wrap justify-end gap-2 border-t border-[#E8E3DC] px-4 py-3">
                            <a :href="selected.url" class="rounded-md border border-[#D8D0C6] px-3 py-1.5 text-[8px] font-bold">Buka halaman detail</a>
                            <button @click="window.print()" class="rounded-md bg-[#A88B5D] px-3 py-1.5 text-[8px] font-bold text-white">Cetak Struk</button>
                            <button @click="detailOpen = false" class="rounded-md bg-[#D7C099] px-4 py-1.5 text-[8px] font-bold text-white">Tutup</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div x-show="exportOpen" x-cloak @keydown.escape.window="exportOpen = false" class="fixed inset-0 z-50 grid place-items-center bg-black/40 p-4 backdrop-blur-[2px]">
            <div @click.outside="exportOpen = false" class="w-full max-w-[380px] rounded-lg bg-white p-4 shadow-2xl">
                <div class="flex items-start justify-between"><div><h2 class="text-xs font-bold">Export Riwayat Transaksi</h2><p class="mt-0.5 text-[8px] text-[#777067]">Pilih format file yang ingin disiapkan.</p></div><button @click="exportOpen = false" class="text-sm">×</button></div>
                <div class="mt-4 grid grid-cols-2 gap-2"><button disabled class="rounded-lg border border-[#E2DDD5] bg-[#F7F4EF] p-3 text-left opacity-60"><strong class="block text-[10px]">CSV</strong><span class="text-[8px]">Data transaksi</span></button><button disabled class="rounded-lg border border-[#E2DDD5] bg-[#F7F4EF] p-3 text-left opacity-60"><strong class="block text-[10px]">PDF</strong><span class="text-[8px]">Siap dicetak</span></button></div>
                <button @click="exportOpen = false" class="mt-4 w-full rounded-md bg-[#A88B5D] py-1.5 text-[8px] font-bold text-white">Tutup</button>
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
