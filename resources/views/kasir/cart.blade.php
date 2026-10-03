<!DOCTYPE html>
<html lang="id">
<head>
    <x-sidebar-state />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-favicon />
    <title>Keranjang Kasir - Coffe Ridho</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[#f6f3ed] text-[#1a1208]" style="font-family: 'Barlow', sans-serif">
    @php
        $subtotal = collect($cart)->sum('subtotal');
        $totalItems = collect($cart)->sum('jumlah');
        $tax = (int) round($subtotal * 0.1);
        $grandTotal = $subtotal + $tax;
    @endphp

    <div class="profile-shell min-h-screen">
        <x-profile-sidebar active="kasir" />

        <main class="min-w-0 flex-1 px-5 py-6 sm:px-8 lg:px-10 lg:py-9">
            <div class="mx-auto max-w-6xl">
                <header class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[.2em] text-[#a07855]">Kasir · Keranjang</p>
                        <h1 class="mt-1 text-3xl font-extrabold sm:text-4xl" style="font-family: 'Space Mono', monospace">Pesanan Saat Ini</h1>
                        <p class="mt-2 text-sm text-[#68594e]">{{ $totalItems }} item dalam keranjang.</p>
                    </div>
                    <a href="{{ route('menu.index') }}" class="inline-flex w-fit items-center gap-2 rounded-xl bg-[#e06328] px-5 py-3 text-sm font-bold text-white shadow-[3px_3px_0_#a6411f] transition hover:-translate-y-0.5 hover:bg-[#c9521c]">
                        <span aria-hidden="true">＋</span> Tambah Menu
                    </a>
                </header>

                @if (session('success'))
                    <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">{{ $errors->first() }}</div>
                @endif

                @if (empty($cart))
                    <section class="grid min-h-[28rem] place-items-center rounded-3xl border-2 border-dashed border-[#d5c3a7] bg-white p-8 text-center">
                        <div>
                            <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-[#eadbc1] text-4xl" aria-hidden="true">🛒</div>
                            <h2 class="mt-5 text-2xl font-extrabold">Keranjang masih kosong</h2>
                            <p class="mx-auto mt-2 max-w-md text-sm text-[#78695d]">Pilih menu terlebih dahulu untuk mulai mencatat pesanan pelanggan.</p>
                            <a href="{{ route('menu.index') }}" class="mt-6 inline-flex rounded-xl bg-[#e06328] px-5 py-3 text-sm font-bold text-white">Pilih Menu</a>
                        </div>
                    </section>
                @else
                    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
                        <section class="overflow-hidden rounded-3xl border border-[#e2d9cc] bg-white shadow-sm">
                            <div class="border-b border-[#eee7dc] px-5 py-4 sm:px-6">
                                <h2 class="text-lg font-extrabold">Daftar Pesanan</h2>
                            </div>
                            <div class="divide-y divide-[#eee7dc]">
                                @foreach ($cart as $id => $item)
                                    <article class="grid gap-4 p-5 sm:grid-cols-[minmax(0,1fr)_auto_auto] sm:items-center sm:px-6">
                                        <div class="min-w-0">
                                            <h3 class="truncate text-lg font-extrabold">{{ $item['nama_menu'] }}</h3>
                                            <p class="mt-1 text-sm font-semibold text-[#8b7767]">Rp {{ number_format($item['harga'], 0, ',', '.') }} per item</p>
                                        </div>

                                        <form method="POST" action="{{ route('cart.update', $id) }}" class="flex items-center gap-2">
                                            @csrf
                                            <label for="jumlah-{{ $id }}" class="sr-only">Jumlah {{ $item['nama_menu'] }}</label>
                                            <input id="jumlah-{{ $id }}" name="jumlah" type="number" min="1" value="{{ $item['jumlah'] }}" class="w-20 rounded-xl border-[#d8cbb8] bg-[#f8f5ef] px-3 py-2 text-center text-sm font-bold focus:border-[#e06328] focus:ring-[#e06328]">
                                            <button type="submit" class="rounded-xl border border-[#d8cbb8] px-3 py-2 text-xs font-bold text-[#67564a] transition hover:bg-[#f0e7da]">Ubah</button>
                                        </form>

                                        <div class="flex items-center justify-between gap-4 sm:justify-end">
                                            <p class="min-w-28 text-right font-extrabold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                            <form method="POST" action="{{ route('cart.remove', $id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="grid h-9 w-9 place-items-center rounded-full bg-[#8b2626] text-white transition hover:bg-[#6f1d1d]" aria-label="Hapus {{ $item['nama_menu'] }}">×</button>
                                            </form>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>

                        <aside class="h-fit rounded-3xl bg-[#e4ceb1] p-6 shadow-[5px_6px_0_rgb(70_48_30_/_15%)] lg:sticky lg:top-6">
                            <h2 class="text-xl font-extrabold" style="font-family: 'Space Mono', monospace">Ringkasan</h2>
                            <dl class="mt-6 space-y-3 text-sm font-semibold">
                                <div class="flex justify-between gap-4"><dt>Subtotal ({{ $totalItems }} item)</dt><dd>Rp {{ number_format($subtotal, 0, ',', '.') }}</dd></div>
                                <div class="flex justify-between gap-4"><dt>PPN (10%)</dt><dd>Rp {{ number_format($tax, 0, ',', '.') }}</dd></div>
                                <div class="my-4 border-t-2 border-dashed border-[#8f785e]/35"></div>
                                <div class="flex justify-between gap-4 text-lg font-extrabold"><dt>Total</dt><dd>Rp {{ number_format($grandTotal, 0, ',', '.') }}</dd></div>
                            </dl>
                            <form id="transaction-form" method="POST" action="{{ route('transactions.store') }}" class="mt-6">
                                @csrf
                                <input type="hidden" name="idempotency_key" value="{{ (string) \Illuminate\Support\Str::uuid() }}">
                                <label for="metode-pembayaran" class="mb-2 block text-xs font-bold">Metode pembayaran</label>
                                <select id="metode-pembayaran" name="metode_pembayaran" required class="mb-4 w-full rounded-xl border-[#c7ad89] bg-white px-3 py-2.5 text-sm font-bold focus:border-[#e06328] focus:ring-[#e06328]">
                                    <option value="cash">Cash</option>
                                    <option value="qris">QRIS</option>
                                </select>
                                <button id="transaction-submit" type="submit" class="w-full rounded-xl bg-[#e06328] px-4 py-3 font-bold text-white shadow-[3px_3px_0_#a6411f] transition hover:-translate-y-0.5 hover:bg-[#c9521c] disabled:cursor-wait disabled:opacity-60">Proses & Simpan Transaksi</button>
                            </form>
                            <p class="mt-3 text-center text-xs font-semibold text-[#745f4e]">Harga dan status menu akan diperiksa ulang sebelum disimpan.</p>
                        </aside>
                    </div>
                @endif
            </div>
        </main>
    </div>
    <script>
        document.getElementById('transaction-form')?.addEventListener('submit', () => {
            const submitButton = document.getElementById('transaction-submit');
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';
        });
    </script>
</body>
</html>
