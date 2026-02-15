@extends('layouts.app')

@section('title', 'Retur Penjualan')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Retur Penjualan</h1>
        <p class="text-muted mb-0">Buat retur dari transaksi penjualan.</p>
    </div>
    <a href="{{ route('transaksi.retur.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('transaksi.retur.penjualan.store') }}" method="POST" id="retur-penjualan-form">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Pilih Penjualan</label>
                    <select name="penjualan_id" id="penjualan-select" class="form-select" required>
                        <option value="">Pilih penjualan</option>
                        @foreach($penjualan as $p)
                            <option value="{{ $p->id }}">{{ $p->nomor_penjualan }} - {{ $p->pelanggan->nama ?? 'Umum' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Retur</label>
                    <input type="date" name="tanggal_retur" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="PENDING">PENDING</option>
                        <option value="DISETUJUI">DISETUJUI</option>
                        <option value="DITOLAK">DITOLAK</option>
                        <option value="SELESAI">SELESAI</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Metode Refund</label>
                    <select name="metode_refund" class="form-select">
                        <option value="">-</option>
                        <option value="TUNAI">Tunai</option>
                        <option value="TRANSFER">Transfer</option>
                        <option value="NOTA_KREDIT">Nota Kredit</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="catatan" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Alasan</label>
                    <textarea name="alasan" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h6 class="text-uppercase text-muted">Item Retur</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="retur-penjualan-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty Retur</th>
                            <th>Harga</th>
                            <th>Pajak (%)</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="retur-penjualan-body">
                        <tr id="retur-penjualan-empty">
                            <td colspan="5" class="text-center text-muted">Pilih penjualan terlebih dahulu.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Simpan Retur</button>
            </div>
        </div>
    </div>
</form>

@php
    $payload = $penjualan->map(function ($p) {
        return [
            'id' => $p->id,
            'nomor' => $p->nomor_penjualan,
            'pelanggan' => $p->pelanggan->nama ?? 'Umum',
            'details' => $p->details->map(function ($d) {
                return [
                    'detail_id' => $d->id,
                    'produk_id' => $d->produk_id,
                    'produk_nama' => $d->produk->nama ?? '-',
                    'harga' => $d->harga_satuan,
                    'pajak' => $d->persentase_pajak,
                    'jumlah' => $d->jumlah,
                ];
            })->values(),
        ];
    })->values();
@endphp

@endsection

@push('scripts')
<script>
    const penjualanData = @json($payload);
    const selectPenjualan = document.getElementById('penjualan-select');
    const itemsBody = document.getElementById('retur-penjualan-body');

    const formatRupiah = (value) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value || 0);
    };

    const renderItems = (penjualanId) => {
        itemsBody.innerHTML = '';
        const selected = penjualanData.find((item) => item.id == penjualanId);
        if (!selected) {
            itemsBody.innerHTML = '<tr id="retur-penjualan-empty"><td colspan="5" class="text-center text-muted">Pilih penjualan terlebih dahulu.</td></tr>';
            return;
        }

        if (!selected.details.length) {
            itemsBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Tidak ada item untuk retur.</td></tr>';
            return;
        }

        selected.details.forEach((detail, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    ${detail.produk_nama}
                    <input type="hidden" name="items[${index}][detail_penjualan_id]" value="${detail.detail_id}">
                    <input type="hidden" name="items[${index}][produk_id]" value="${detail.produk_id}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm qty-retur" name="items[${index}][jumlah]" value="0" min="0" step="0.01">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm harga-retur" name="items[${index}][harga_satuan]" value="${detail.harga}" min="0" step="0.01">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm pajak-retur" name="items[${index}][persentase_pajak]" value="${detail.pajak}" min="0" step="0.01">
                </td>
                <td class="text-end line-total">${formatRupiah(0)}</td>
            `;
            itemsBody.appendChild(row);
        });
    };

    itemsBody.addEventListener('input', (event) => {
        if (!event.target.matches('.qty-retur, .harga-retur, .pajak-retur')) return;
        const row = event.target.closest('tr');
        const qty = parseFloat(row.querySelector('.qty-retur').value) || 0;
        const harga = parseFloat(row.querySelector('.harga-retur').value) || 0;
        const pajak = parseFloat(row.querySelector('.pajak-retur').value) || 0;
        const subtotal = qty * harga;
        const total = subtotal + (subtotal * pajak / 100);
        row.querySelector('.line-total').textContent = formatRupiah(total);
    });

    selectPenjualan.addEventListener('change', (event) => {
        renderItems(event.target.value);
    });
    document.getElementById(''retur-penjualan-form'').addEventListener(''submit'', (event) => {
        const rows = Array.from(itemsBody.querySelectorAll(''tr''));
        let validCount = 0;
        rows.forEach((row) => {
            const qtyInput = row.querySelector(''.qty-retur'');
            if (!qtyInput) return;
            const qty = parseFloat(qtyInput.value) || 0;
            if (qty <= 0) {
                row.querySelectorAll(''input'').forEach((input) => {
                    input.disabled = true;
                });
            } else {
                validCount += 1;
            }
        });

        if (validCount === 0) {
            event.preventDefault();
            showAlert('warning', 'Peringatan', 'Pilih minimal satu item untuk diretur.');
        }
    });
</script>
@endpush

