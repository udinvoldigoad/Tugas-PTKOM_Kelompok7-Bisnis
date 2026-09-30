<div>
    <!-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca -->
</div>
<x-app-layout>
    <main class="p-6">
        <h1>Riwayat Transaksi</h1>

        @forelse ($transaksis as $transaksi)
            <article>
                <a href="{{ route('transactions.show', $transaksi) }}">
                    Transaksi #{{ $transaksi->id }}
                </a>
                <p>{{ $transaksi->tanggal }} - {{ $transaksi->user->name }}</p>
                <p>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
            </article>
        @empty
            <p>Belum ada transaksi.</p>
        @endforelse

        {{ $transaksis->links() }}
    </main>
</x-app-layout>
