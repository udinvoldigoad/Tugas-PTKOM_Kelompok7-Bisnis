<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <style>
        @page { size: A4 landscape; margin: 14mm; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #211d19; font: 12px Arial, sans-serif; }
        header { display: flex; align-items: end; justify-content: space-between; margin-bottom: 20px; }
        h1 { margin: 0 0 5px; font-size: 22px; }
        p { margin: 0; color: #70685f; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d9d1c7; padding: 8px; text-align: left; }
        th { background: #e8dcc8; }
        td:nth-child(n+5) { text-align: right; }
        .empty { padding: 36px; text-align: center; }
        .actions { margin-bottom: 16px; text-align: right; }
        button { border: 0; border-radius: 6px; background: #a88b5d; padding: 9px 18px; color: white; font-weight: bold; cursor: pointer; }
        @media print { .actions { display: none; } }
    </style>
</head>
<body>
    <div class="actions"><button type="button" onclick="window.print()">Simpan / Cetak PDF</button></div>
    <header>
        <div><h1>Riwayat Transaksi</h1><p>Coffe Ridho</p></div>
        <p>Dicetak {{ now()->translatedFormat('d F Y, H:i') }}</p>
    </header>

    <table>
        <thead><tr><th>No. Struk</th><th>Tanggal</th><th>Kasir</th><th>Metode</th><th>Subtotal</th><th>PPN 10%</th><th>Total</th></tr></thead>
        <tbody>
            @forelse ($transactions as $transaction)
                @php($subtotal = (int) $transaction->detailTransaksi->sum('subtotal'))
                <tr>
                    <td>TRX-{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->translatedFormat('d M Y, H:i') }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td>{{ strtoupper($transaction->metode_pembayaran ?? '-') }}</td>
                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format(max(0, (int) $transaction->total_harga - $subtotal), 0, ',', '.') }}</td>
                    <td><strong>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</strong></td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty">Tidak ada transaksi sesuai filter.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
