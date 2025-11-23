<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->transaction_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 12px; padding: 10px; max-width: 300px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .header h1 { font-size: 18px; margin-bottom: 5px; }
        .info { margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .info div { margin-bottom: 3px; }
        .items { margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .item { margin-bottom: 5px; }
        .item-name { font-weight: bold; }
        .item-detail { display: flex; justify-content: space-between; font-size: 11px; }
        .summary { margin-bottom: 10px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 3px; }
        .total { font-weight: bold; font-size: 14px; border-top: 1px solid #000; padding-top: 5px; }
        .footer { text-align: center; margin-top: 10px; font-size: 10px; border-top: 1px dashed #000; padding-top: 10px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RITEL ABC</h1>
        <div>Jl. Raya No. 123, Jakarta</div>
        <div>Telp: 021-12345678</div>
    </div>
    
    <div class="info">
        <div><strong>No. Transaksi:</strong> {{ $transaction->transaction_code }}</div>
        <div><strong>Tanggal:</strong> {{ $transaction->transaction_date->format('d/m/Y H:i') }}</div>
        <div><strong>Kasir:</strong> {{ $transaction->cashier->full_name }}</div>
        @if($transaction->customer)
        <div><strong>Pelanggan:</strong> {{ $transaction->customer->name }}</div>
        @endif
    </div>
    
    <div class="items">
        @foreach($transaction->details as $detail)
        <div class="item">
            <div class="item-name">{{ $detail->product->name }}</div>
            <div class="item-detail">
                <span>{{ $detail->quantity }} x Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</span>
                <span>Rp {{ number_format($detail->total, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
        </div>
        @if($transaction->discount_amount > 0)
        <div class="summary-row">
            <span>Diskon ({{ $transaction->discount_percentage }}%):</span>
            <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="summary-row total">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Bayar:</span>
            <span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Kembali:</span>
            <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
        </div>
    </div>
    
    <div class="footer">
        <div>Terima Kasih Atas Kunjungan Anda</div>
        <div>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</div>
    </div>
    
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Cetak
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>
    
    <script>
        // Auto print on load
        window.onload = function() {
            setTimeout(() => window.print(), 500);
        }
    </script>
</body>
</html>