<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota - {{ $penjualan->nomor_penjualan }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.2;
            width: 80mm;
            margin: 0;
            padding: 5mm;
        }
        
        .nota-header {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .nota-header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .nota-header p {
            font-size: 10px;
        }
        
        .nota-info {
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        
        .nota-info-row {
            display: flex;
            justify-content: space-between;
        }
        
        .nota-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        
        .nota-items th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding: 3px 0;
            font-weight: bold;
        }
        
        .nota-items td {
            padding: 3px 0;
            vertical-align: top;
        }
        
        .col-item {
            width: 45%;
        }
        
        .col-qty {
            width: 10%;
            text-align: center;
        }
        
        .col-price {
            width: 20%;
            text-align: right;
        }
        
        .col-total {
            width: 25%;
            text-align: right;
        }
        
        .nota-summary {
            border-top: 1px dashed #000;
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .nota-summary-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }
        
        .nota-summary-row.total {
            font-weight: bold;
            font-size: 13px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 5px;
        }
        
        .nota-footer {
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 10px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        @media print {
            body {
                width: 80mm !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="nota-header">
        <h1>APOCARE</h1>
        <p>Apotek Modern</p>
        <p>Jl. Contoh No. 123</p>
    </div>
    
    <div class="nota-info">
        <div class="nota-info-row">
            <span>No: {{ $penjualan->nomor_penjualan }}</span>
            <span>{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d/m/y H:i') }}</span>
        </div>
        <div class="nota-info-row">
            <span>Pelanggan:</span>
            <span>{{ $penjualan->pelanggan->nama ?? 'Umum' }}</span>
        </div>
        <div class="nota-info-row">
            <span>Kasir:</span>
            <span>{{ $penjualan->dibuat_oleh ?? '-' }}</span>
        </div>
    </div>
    
    <table class="nota-items">
        <thead>
            <tr>
                <th class="col-item">Item</th>
                <th class="col-qty">Qty</th>
                <th class="col-price">Harga</th>
                <th class="col-total">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan->details as $detail)
            <tr>
                <td>
                    {{ $detail->produk->nama ?? '-' }}
                    @if($detail->diskon > 0)
                    <br><span style="font-size:9px">Disc: Rp {{ number_format($detail->diskon, 0, ',', '.') }}</span>
                    @endif
                </td>
                <td class="text-center">{{ $detail->jumlah }}</td>
                <td class="text-right">{{ number_format($detail->harga_satuan - $detail->diskon, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($detail->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="nota-summary">
        <div class="nota-summary-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($penjualan->subtotal, 0, ',', '.') }}</span>
        </div>
        @if($penjualan->jumlah_diskon > 0)
        <div class="nota-summary-row">
            <span>Diskon</span>
            <span>- Rp {{ number_format($penjualan->jumlah_diskon, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($penjualan->jumlah_pajak > 0)
        <div class="nota-summary-row">
            <span>Pajak</span>
            <span>Rp {{ number_format($penjualan->jumlah_pajak, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="nota-summary-row total">
            <span>TOTAL</span>
            <span>Rp {{ number_format($penjualan->total_akhir, 0, ',', '.') }}</span>
        </div>
        <div class="nota-summary-row">
            <span>Bayar</span>
            <span>Rp {{ number_format($penjualan->jumlah_bayar, 0, ',', '.') }}</span>
        </div>
        <div class="nota-summary-row">
            <span>Kembalian</span>
            <span>Rp {{ number_format($penjualan->jumlah_kembalian, 0, ',', '.') }}</span>
        </div>
    </div>
    
    <div class="nota-footer">
        <p>Terima kasih atas kunjungan Anda</p>
        <p>Silahkan datang kembali</p>
        <p class="no-print" style="margin-top: 10px;">
            <button onclick="window.print()" style="padding: 5px 10px;">PRINT</button>
        </p>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
