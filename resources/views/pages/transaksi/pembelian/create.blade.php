@extends('layouts.app')

@section('title', 'Purchase Order')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
@endpush

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Purchase Order</h1>
        <p class="text-muted mb-0">Buat PO dan penerimaan barang.</p>
    </div>
    <a href="{{ route('transaksi.pembelian.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('transaksi.pembelian.store') }}" method="POST" id="po-form">
    @csrf
    <div class="pos-shell">
        <div class="pos-panel">
            <div class="panel-header">
                <div>
                    <h5 class="mb-0">Daftar Produk</h5>
                    <small class="text-muted">Klik tambah untuk masuk ke PO.</small>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Satuan</th>
                                <th>Harga Beli</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produk as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->nama }}</div>
                                        <div class="text-muted small">{{ $item->kode }}</div>
                                    </td>
                                    <td>{{ $item->satuan->nama ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <button type="button"
                                            class="btn btn-sm btn-primary add-to-po"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama }}"
                                            data-kode="{{ $item->kode }}"
                                            data-harga="{{ $item->harga_beli }}">
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
                    <h5 class="mb-0">Detail PO</h5>
                    <small class="text-muted">Lengkapi supplier dan item pembelian.</small>
                </div>
            </div>
            <div class="panel-body">
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label">Pemasok</label>
                        <select name="pemasok_id" class="form-select" required>
                            <option value="">Pilih pemasok</option>
                            @foreach($pemasok as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor PO (Opsional)</label>
                        <input type="text" name="nomor_po" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal PO</label>
                        <input type="date" name="tanggal_pembelian" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="date" name="tanggal_jatuh_tempo" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="DRAFT">DRAFT</option>
                            <option value="DIPESAN">DIPESAN</option>
                            <option value="SEBAGIAN">SEBAGIAN</option>
                            <option value="DITERIMA">DITERIMA</option>
                            <option value="SELESAI">SELESAI</option>
                            <option value="BATAL">BATAL</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-select">
                            <option value="">-</option>
                            <option value="TUNAI">Tunai</option>
                            <option value="TRANSFER">Transfer</option>
                            <option value="KREDIT">Kredit</option>
                            <option value="GIRO">Giro</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="po-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty Pesan</th>
                                <th>Qty Terima</th>
                                <th>Harga</th>
                                <th>Diskon (%)</th>
                                <th>Pajak (%)</th>
                                <th>Batch</th>
                                <th>Produksi</th>
                                <th>Exp</th>
                                <th class="text-end">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="po-body">
                            <tr id="po-empty">
                                <td colspan="11" class="text-center text-muted">Belum ada item.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="cart-summary">
                    <div class="summary-line">
                        <span>Subtotal</span>
                        <strong id="po-subtotal">Rp 0</strong>
                    </div>
                    <div class="summary-line">
                        <span>Diskon</span>
                        <strong id="po-diskon">Rp 0</strong>
                    </div>
                    <div class="summary-line">
                        <span>Pajak</span>
                        <strong id="po-pajak">Rp 0</strong>
                    </div>
                    <div class="summary-line">
                        <span>Biaya Kirim</span>
                        <strong id="po-biaya-kirim">Rp 0</strong>
                    </div>
                    <div class="summary-line">
                        <span>Biaya Lain</span>
                        <strong id="po-biaya-lain">Rp 0</strong>
                    </div>
                    <hr>
                    <div class="summary-line">
                        <span>Total</span>
                        <strong id="po-total">Rp 0</strong>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Diskon</label>
                        <select name="jenis_diskon" id="po-jenis-diskon" class="form-select">
                            <option value="PERSENTASE">Persentase</option>
                            <option value="NOMINAL">Nominal</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nilai Diskon</label>
                        <input type="number" name="nilai_diskon" id="po-nilai-diskon" class="form-control" value="0" min="0" step="0.01">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pajak Transaksi (%)</label>
                        <input type="number" name="pajak_transaksi" id="po-pajak-transaksi" class="form-control" value="0" min="0" step="0.01">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Biaya Kirim</label>
                        <input type="number" name="biaya_kirim" id="po-input-biaya-kirim" class="form-control" value="0" min="0" step="0.01">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Biaya Lain</label>
                        <input type="number" name="biaya_lain" id="po-input-biaya-lain" class="form-control" value="0" min="0" step="0.01">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah Bayar</label>
                        <input type="number" name="jumlah_bayar" class="form-control" value="0" min="0" step="0.01">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="pos-actions mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan PO
                    </button>
                    <a href="{{ route('transaksi.pembelian.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const poBody = document.getElementById('po-body');
    const poEmpty = document.getElementById('po-empty');

    const poSubtotal = document.getElementById('po-subtotal');
    const poDiskon = document.getElementById('po-diskon');
    const poPajak = document.getElementById('po-pajak');
    const poBiayaKirim = document.getElementById('po-biaya-kirim');
    const poBiayaLain = document.getElementById('po-biaya-lain');
    const poTotal = document.getElementById('po-total');

    const jenisDiskon = document.getElementById('po-jenis-diskon');
    const nilaiDiskon = document.getElementById('po-nilai-diskon');
    const pajakTransaksi = document.getElementById('po-pajak-transaksi');
    const biayaKirim = document.getElementById('po-input-biaya-kirim');
    const biayaLain = document.getElementById('po-input-biaya-lain');

    const formatRupiah = (value) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value || 0);
    };

    const recalcPO = () => {
        let subtotal = 0;
        let diskonItem = 0;
        let pajakItem = 0;

        poBody.querySelectorAll('tr[data-produk-id]').forEach((row) => {
            const qty = parseFloat(row.querySelector('.po-qty').value) || 0;
            const harga = parseFloat(row.querySelector('.po-harga').value) || 0;
            const diskon = parseFloat(row.querySelector('.po-diskon').value) || 0;
            const pajak = parseFloat(row.querySelector('.po-pajak').value) || 0;

            const lineSubtotal = qty * harga;
            const lineDiskon = lineSubtotal * (diskon / 100);
            const linePajak = (lineSubtotal - lineDiskon) * (pajak / 100);
            const lineTotal = lineSubtotal - lineDiskon + linePajak;

            subtotal += lineSubtotal;
            diskonItem += lineDiskon;
            pajakItem += linePajak;

            row.querySelector('.po-line-subtotal').textContent = formatRupiah(lineTotal);
        });

        const jenis = jenisDiskon.value;
        const nilai = parseFloat(nilaiDiskon.value) || 0;
        const diskonGlobal = jenis === 'PERSENTASE' ? subtotal * (nilai / 100) : nilai;
        const pajakGlobal = (subtotal - diskonItem - diskonGlobal) * ((parseFloat(pajakTransaksi.value) || 0) / 100);

        const totalDiskon = diskonItem + diskonGlobal;
        const totalPajak = pajakItem + pajakGlobal;
        const biayaK = parseFloat(biayaKirim.value) || 0;
        const biayaL = parseFloat(biayaLain.value) || 0;
        const totalAkhir = (subtotal - totalDiskon) + totalPajak + biayaK + biayaL;

        poSubtotal.textContent = formatRupiah(subtotal);
        poDiskon.textContent = formatRupiah(totalDiskon);
        poPajak.textContent = formatRupiah(totalPajak);
        poBiayaKirim.textContent = formatRupiah(biayaK);
        poBiayaLain.textContent = formatRupiah(biayaL);
        poTotal.textContent = formatRupiah(totalAkhir);
    };

    const reindexPO = () => {
        poBody.querySelectorAll('tr[data-produk-id]').forEach((row, index) => {
            row.querySelector('.input-produk-id').name = `items[${index}][produk_id]`;
            row.querySelector('.po-qty').name = `items[${index}][jumlah_pesan]`;
            row.querySelector('.po-qty-terima').name = `items[${index}][jumlah_terima]`;
            row.querySelector('.po-harga').name = `items[${index}][harga_satuan]`;
            row.querySelector('.po-diskon').name = `items[${index}][persentase_diskon]`;
            row.querySelector('.po-pajak').name = `items[${index}][persentase_pajak]`;
            row.querySelector('.po-batch').name = `items[${index}][nomor_batch]`;
            row.querySelector('.po-prod').name = `items[${index}][tanggal_produksi]`;
            row.querySelector('.po-exp').name = `items[${index}][tanggal_kadaluarsa]`;
        });
    };

    const addPOItem = (data) => {
        let row = poBody.querySelector(`tr[data-produk-id='${data.id}']`);
        if (row) {
            const qtyInput = row.querySelector('.po-qty');
            qtyInput.value = (parseFloat(qtyInput.value) || 0) + 1;
            recalcPO();
            return;
        }

        if (poEmpty) {
            poEmpty.remove();
        }

        row = document.createElement('tr');
        row.dataset.produkId = data.id;
        row.innerHTML = `
            <td>
                <div class="fw-semibold">${data.nama}</div>
                <div class="text-muted small">${data.kode}</div>
                <input type="hidden" class="input-produk-id" value="${data.id}">
            </td>
            <td><input type="number" class="form-control form-control-sm po-qty" value="1" min="0.01" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm po-qty-terima" value="0" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm po-harga" value="${data.harga}" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm po-diskon" value="0" min="0" step="0.01"></td>
            <td><input type="number" class="form-control form-control-sm po-pajak" value="0" min="0" step="0.01"></td>
            <td><input type="text" class="form-control form-control-sm po-batch" placeholder="Batch"></td>
            <td><input type="date" class="form-control form-control-sm po-prod"></td>
            <td><input type="date" class="form-control form-control-sm po-exp"></td>
            <td class="text-end po-line-subtotal">${formatRupiah(data.harga)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-action-delete remove-po"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;

        poBody.appendChild(row);
        reindexPO();
        recalcPO();
    };

    document.querySelectorAll('.add-to-po').forEach((btn) => {
        btn.addEventListener('click', () => {
            addPOItem({
                id: btn.dataset.id,
                nama: btn.dataset.nama,
                kode: btn.dataset.kode,
                harga: btn.dataset.harga,
            });
        });
    });

    poBody.addEventListener('input', (event) => {
        if (event.target.matches('.po-qty, .po-harga, .po-diskon, .po-pajak')) {
            recalcPO();
        }
    });

    poBody.addEventListener('click', (event) => {
        const btn = event.target.closest('.remove-po');
        if (!btn) return;
        btn.closest('tr').remove();
        if (!poBody.querySelector('tr[data-produk-id]')) {
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'po-empty';
            emptyRow.innerHTML = '<td colspan="11" class="text-center text-muted">Belum ada item.</td>';
            poBody.appendChild(emptyRow);
        }
        reindexPO();
        recalcPO();
    });

    [jenisDiskon, nilaiDiskon, pajakTransaksi, biayaKirim, biayaLain].forEach((el) => {
        el.addEventListener('input', recalcPO);
    });

    recalcPO();
</script>
@endpush





