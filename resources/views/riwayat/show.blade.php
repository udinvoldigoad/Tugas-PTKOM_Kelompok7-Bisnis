<div>
    <!-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant -->
</div>
<x-app-layout>
    <main class="p-6">
        <a href="{{ route('transactions.index') }}">Kembali ke riwayat</a>
        <h1>Detail Transaksi #{{ $transaksi->id }}</h1>
        <p>Kasir: {{ $transaksi->user->name }}</p>
        <p>Tanggal: {{ $transaksi->tanggal }}</p>
        <p>Metode pembayaran: {{ strtoupper($transaksi->metode_pembayaran ?? '-') }}</p>

        <ul>
            @foreach ($transaksi->detailTransaksi as $detail)
                <li>
                    {{ $detail->menu->nama_menu }} × {{ $detail->jumlah }}
                    — Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                </li>
            @endforeach
        </ul>

        <strong>Total: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong>
    </main>
</x-app-layout>
