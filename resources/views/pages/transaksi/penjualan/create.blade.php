@extends('layouts.app')

@section('title', 'Point of Sale')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
@endpush

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Point of Sale</h1>
        <p class="text-muted mb-0">Transaksi penjualan cepat untuk kasir.</p>
    </div>
    <a href="{{ route('transaksi.penjualan.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('transaksi.penjualan.store') }}" method="POST" id="pos-form">
    @csrf
    <div class="pos-shell">
        <div class="pos-panel">
            <div class="panel-header">
                <div>
                    <h5 class="mb-0">Daftar Produk</h5>
                    <small class="text-muted">Klik tambah untuk masuk ke keranjang.</small>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable" id="product-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produk as $item)
                                @php
                                    $stok = optional($item->stokProduk->first())->jumlah ?? 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->nama }}</div>
                                        <div class="text-muted small">{{ $item->kode }}</div>
                                    </td>
                                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                                    <td>{{ number_format($stok, 2, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <button type="button"
                                            class="btn btn-sm btn-primary add-to-cart"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama }}"
                                            data-kode="{{ $item->kode }}"
                                            data-harga="{{ $item->harga_jual }}"
                                            data-pajak="{{ $item->persentase_pajak ?? 0 }}"
                                            data-stok="{{ $stok }}">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="pos-panel">
            <div class="panel-header">
                <div>
                    <h5 class="mb-0">Keranjang</h5>
                    <small class="text-muted">Atur jumlah dan harga jika diperlukan.</small>
                </div>
            </div>
            <div class="panel-body">
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label">Pelanggan</label>
                        <select name="pelanggan_id" class="form-select">
                            <option value="">Umum</option>
                            @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-select" required>
                            <option value="TUNAI">Tunai</option>
                            <option value="DEBIT">Debit</option>
                            <option value="KREDIT">Kredit</option>
                            <option value="TRANSFER">Transfer</option>
                            <option value="EWALLET">E-Wallet</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered cart-table" id="cart-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Diskon (%)</th>
                                <th>Pajak (%)</th>
                                <th class="text-end">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-body">
                            <tr id="cart-empty">
                                <td colspan="7" class="text-center text-muted">Belum ada item.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="cart-summary">
                    <div class="summary-line">
                        <span>Subtotal</span>
                        <strong id="summary-subtotal">Rp 0</strong>
                    </div>
                    <div class="summary-line">
                        <span>Diskon Item</span>
                        <strong id="summary-diskon-item">Rp 0</strong>
                    </div>
                    <div class="summary-line">
                        <span>Diskon Transaksi</span>
                        <strong id="summary-diskon">Rp 0</strong>
                    </div>
                    <div class="summary-line">
                        <span>Pajak</span>
                        <strong id="summary-pajak">Rp 0</strong>
                    </div>
                    <hr>
                    <div class="summary-line">
                        <span>Total</span>
                        <strong id="summary-total">Rp 0</strong>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Diskon</label>
                        <select name="jenis_diskon" id="jenis_diskon" class="form-select">
                            <option value="PERSENTASE">Persentase</option>
                            <option value="NOMINAL">Nominal</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nilai Diskon</label>
                        <input type="number" name="nilai_diskon" id="nilai_diskon" class="form-control" value="0" min="0" step="0.01">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pajak Transaksi (%)</label>
                        <input type="number" name="pajak_transaksi" id="pajak_transaksi" class="form-control" value="0" min="0" step="0.01">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah Bayar</label>
                        <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control" value="0" min="0" step="0.01" required>
                        <small class="text-muted">Kembalian: <span id="summary-kembalian">Rp 0</span></small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="pos-actions mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Transaksi
                    </button>
                    <a href="{{ route('transaksi.penjualan.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const cartBody = document.getElementById('cart-body');
    const cartEmpty = document.getElementById('cart-empty');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryDiskonItem = document.getElementById('summary-diskon-item');
    const summaryDiskon = document.getElementById('summary-diskon');
    const summaryPajak = document.getElementById('summary-pajak');
    const summaryTotal = document.getElementById('summary-total');
    const summaryKembalian = document.getElementById('summary-kembalian');

    const jenisDiskonInput = document.getElementById('jenis_diskon');
    const nilaiDiskonInput = document.getElementById('nilai_diskon');
    const pajakTransaksiInput = document.getElementById('pajak_transaksi');
    const jumlahBayarInput = document.getElementById('jumlah_bayar');

    const formatRupiah = (value) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value || 0);
    };

    const recalcTotals = () => {
        let subtotal = 0;
        let diskonItem = 0;
        let pajakItem = 0;

        const rows = cartBody.querySelectorAll('tr[data-produk-id]');
        rows.forEach((row) => {
            const qty = parseFloat(row.querySelector('.cart-qty').value) || 0;
            const harga = parseFloat(row.querySelector('.cart-price').value) || 0;
            const diskon = parseFloat(row.querySelector('.cart-diskon').value) || 0;
            const pajak = parseFloat(row.querySelector('.cart-pajak').value) || 0;

            const lineSubtotal = qty * harga;
            const lineDiskon = lineSubtotal * (diskon / 100);
            const linePajak = (lineSubtotal - lineDiskon) * (pajak / 100);
            const lineTotal = lineSubtotal - lineDiskon + linePajak;

            subtotal += lineSubtotal;
            diskonItem += lineDiskon;
            pajakItem += linePajak;

            row.querySelector('.line-subtotal').textContent = formatRupiah(lineTotal);
        });

        const jenisDiskon = jenisDiskonInput.value;
        const nilaiDiskon = parseFloat(nilaiDiskonInput.value) || 0;
        const diskonGlobal = jenisDiskon === 'PERSENTASE' ? subtotal * (nilaiDiskon / 100) : nilaiDiskon;
        const pajakTransaksi = parseFloat(pajakTransaksiInput.value) || 0;
        const pajakGlobal = (subtotal - diskonItem - diskonGlobal) * (pajakTransaksi / 100);

        const totalDiskon = diskonItem + diskonGlobal;
        const totalPajak = pajakItem + pajakGlobal;
        const totalAkhir = (subtotal - totalDiskon) + totalPajak;

        summarySubtotal.textContent = formatRupiah(subtotal);
        summaryDiskonItem.textContent = formatRupiah(diskonItem);
        summaryDiskon.textContent = formatRupiah(diskonGlobal);
        summaryPajak.textContent = formatRupiah(totalPajak);
        summaryTotal.textContent = formatRupiah(totalAkhir);

        const bayar = parseFloat(jumlahBayarInput.value) || 0;
        const kembalian = Math.max(0, bayar - totalAkhir);
        summaryKembalian.textContent = formatRupiah(kembalian);
    };

    const reindexCart = () => {
        const rows = cartBody.querySelectorAll('tr[data-produk-id]');
        rows.forEach((row, index) => {
            row.querySelector('.input-produk-id').name = `items[${index}][produk_id]`;
            row.querySelector('.cart-qty').name = `items[${index}][jumlah]`;
            row.querySelector('.cart-price').name = `items[${index}][harga_satuan]`;
            row.querySelector('.cart-diskon').name = `items[${index}][persentase_diskon]`;
            row.querySelector('.cart-pajak').name = `items[${index}][persentase_pajak]`;
        });
    };

    const addToCart = (data) => {
        let row = cartBody.querySelector(`tr[data-produk-id='${data.id}']`);
        if (row) {
            const qtyInput = row.querySelector('.cart-qty');
            qtyInput.value = (parseFloat(qtyInput.value) || 0) + 1;
            recalcTotals();
            return;
        }

        if (cartEmpty) {
            cartEmpty.remove();
        }

        row = document.createElement('tr');
        row.dataset.produkId = data.id;
        row.innerHTML = `
            <td>
                <div class="fw-semibold">${data.nama}</div>
                <div class="text-muted small">${data.kode}</div>
                <input type="hidden" class="input-produk-id" value="${data.id}">
            </td>
            <td><input type="number" class="form-control form-control-sm cart-qty" value="1" min="0.01" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm cart-price" value="${data.harga}" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm cart-diskon" value="0" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm cart-pajak" value="${data.pajak}" min="0" step="0.01"></td>
            <td class="text-end line-subtotal">${formatRupiah(data.harga)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-item"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        cartBody.appendChild(row);
        reindexCart();
        recalcTotals();
    };

    document.querySelectorAll('.add-to-cart').forEach((btn) => {
        btn.addEventListener('click', () => {
            addToCart({
                id: btn.dataset.id,
                nama: btn.dataset.nama,
                kode: btn.dataset.kode,
                harga: btn.dataset.harga,
                pajak: btn.dataset.pajak,
            });
        });
    });

    cartBody.addEventListener('input', (event) => {
        if (event.target.matches('.cart-qty, .cart-price, .cart-diskon, .cart-pajak')) {
            recalcTotals();
        }
    });

    cartBody.addEventListener('click', (event) => {
        const removeBtn = event.target.closest('.remove-item');
        if (!removeBtn) return;
        const row = removeBtn.closest('tr');
        row.remove();
        if (!cartBody.querySelector('tr[data-produk-id]')) {
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'cart-empty';
            emptyRow.innerHTML = '<td colspan="7" class="text-center text-muted">Belum ada item.</td>';
            cartBody.appendChild(emptyRow);
        }
        reindexCart();
        recalcTotals();
    });

    jenisDiskonInput.addEventListener('change', recalcTotals);
    nilaiDiskonInput.addEventListener('input', recalcTotals);
    pajakTransaksiInput.addEventListener('input', recalcTotals);
    jumlahBayarInput.addEventListener('input', recalcTotals);

    recalcTotals();
</script>
@endpush
