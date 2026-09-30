@php
    $subtotal = (int) $transaksi->detailTransaksi->sum('subtotal');
    $tax = max(0, (int) $transaksi->total_harga - $subtotal);
@endphp

<x-app-layout :hideNavigation="true">
    <div class="profile-shell min-h-[100dvh] bg-[#FAF9F5] font-mono">
        <x-profile-sidebar active="riwayat" />

        <main class="min-w-0 flex-1 bg-[#FAF9F5] px-4 py-5 sm:px-7 lg:px-9 lg:py-7">
            <div class="mx-auto max-w-5xl">
                <header class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <a href="{{ route('transactions.index') }}" class="mb-2 inline-flex items-center gap-1 text-[10px] font-bold text-[#92764D] hover:underline">← Kembali ke riwayat</a>
                        <h1 class="text-2xl font-bold tracking-wide text-[#1E1B18] sm:text-3xl">Detail Transaksi</h1>
                        <p class="mt-1 text-[11px] text-[#6C655D]">TRX-{{ str_pad($transaksi->id, 3, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <span class="rounded-full bg-[#CFF0D5] px-3 py-1.5 text-[10px] font-bold text-[#287E36]">Selesai</span>
                </header>

                <div class="grid gap-5 lg:grid-cols-[1fr_300px]">
                    <section class="overflow-hidden rounded-2xl border border-[#E3DED6] bg-white shadow-sm">
                        <div class="grid gap-4 bg-[#F2ECE3] p-5 text-[11px] sm:grid-cols-2">
                            <div><span class="block text-[9px] text-[#7F776E]">Tanggal dan waktu</span><strong>{{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y, H:i') }}</strong></div>
                            <div><span class="block text-[9px] text-[#7F776E]">Kasir</span><strong>{{ $transaksi->user->name }}</strong></div>
                            <div><span class="block text-[9px] text-[#7F776E]">Metode pembayaran</span><strong>{{ strtoupper($transaksi->metode_pembayaran ?? '-') }}</strong></div>
                            <div><span class="block text-[9px] text-[#7F776E]">Jumlah item</span><strong>{{ $transaksi->detailTransaksi->sum('jumlah') }} item</strong></div>
                        </div>

                        <div class="p-5">
                            <h2 class="mb-3 text-sm font-bold">Daftar Item</h2>
                            <div class="space-y-2">
                                @foreach ($transaksi->detailTransaksi as $detail)
                                    <article class="flex items-center justify-between gap-4 rounded-xl border border-[#E8E2DA] p-3">
                                        <div class="min-w-0">
                                            <strong class="block truncate text-[11px]">{{ $detail->menu?->nama_menu ?? 'Menu terhapus' }}</strong>
                                            <span class="text-[9px] text-[#777067]">{{ $detail->jumlah }} × Rp {{ number_format($detail->jumlah > 0 ? intdiv((int) $detail->subtotal, (int) $detail->jumlah) : 0, 0, ',', '.') }}</span>
                                        </div>
                                        <strong class="shrink-0 text-[11px]">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <aside class="h-fit rounded-2xl border border-dashed border-[#CFC5B8] bg-[#FFFCF7] p-5 shadow-sm">
                        <div class="flex items-center gap-3 border-b border-dashed border-[#CFC5B8] pb-4">
                            <img src="{{ asset('depan/logo.png') }}" alt="Coffe Ridho" class="h-10 w-10 object-contain">
                            <div><strong class="block text-xs">COFFE RIDHO</strong><span class="text-[9px] text-[#777067]">Ringkasan pembayaran</span></div>
                        </div>
                        <div class="mt-4 space-y-2 text-[10px]">
                            <p class="flex justify-between gap-3"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></p>
                            <p class="flex justify-between gap-3"><span>PPN 10%</span><span>Rp {{ number_format($tax, 0, ',', '.') }}</span></p>
                            <p class="flex justify-between gap-3 border-t border-dashed border-[#CFC5B8] pt-3 text-sm font-bold"><span>Total</span><span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span></p>
                        </div>
                        <button type="button" onclick="window.print()" class="mt-5 w-full rounded-xl bg-[#A88B5D] py-2.5 text-[10px] font-bold text-white hover:bg-[#967B4E]">Cetak Struk</button>
                    </aside>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
