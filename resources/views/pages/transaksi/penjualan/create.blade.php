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
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary" id="sync-offline-btn">
            <i class="fa-solid fa-rotate me-2"></i>Sinkronkan (<span id="offline-queue-count">0</span>)
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
            <i class="fa-solid fa-plus me-2"></i>Tambah Produk
        </button>
        <a href="{{ route('transaksi.penjualan.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="offline-status-card" id="offline-status-card">
    <div class="d-flex align-items-center gap-2">
        <span class="offline-dot" id="offline-dot"></span>
        <strong id="offline-status-label">Memeriksa koneksi...</strong>
    </div>
    <small class="text-muted" id="offline-status-subtext">Antrian offline: 0 transaksi</small>
</div>

<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Pilih Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="product-search" placeholder="Cari produk...">
                </div>
                <div class="table-responsive">
                    <table class="table table-striped datatable" id="product-table-modal">
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
                                <tr class="{{ $item->is_favorit ? 'table-warning' : '' }}">
                                    <td>
                                        <div class="fw-semibold">
                                            @if($item->is_favorit)
                                                <i class="fa-solid fa-star text-warning me-1"></i>
                                            @endif
                                            {{ $item->nama }}
                                        </div>
                                        <div class="text-muted small">{{ $item->kode }}</div>
                                    </td>
                                    <td>{{ $item->kategori->nama ?? '-' }}</td>
                                    <td>{{ formatAngka($stok) }}</td>
                                    <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <button type="button"
                                            class="btn btn-sm btn-primary add-to-cart"
                                            data-bs-dismiss="modal"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama }}"
                                            data-kode="{{ $item->kode }}"
                                            data-harga="{{ $item->harga_jual }}"
                                            data-pajak="{{ $item->persentase_pajak ?? 0 }}"
                                            data-stok="{{ $stok }}">
                                            <i class="fa-solid fa-plus"></i> Tambah
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('transaksi.penjualan.store') }}" method="POST" id="pos-form">
    @csrf
    <input type="hidden" name="client_reference" id="client_reference" value="">
    <div class="pos-shell">
        <div class="pos-panel w-100">
            <div class="panel-header">
                <div>
                    <h5 class="mb-0">Keranjang</h5>
                    <small class="text-muted">Produk yang akan dijual.</small>
                </div>
            </div>
            <div class="panel-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Pelanggan</label>
                        <select name="pelanggan_id" class="form-select" id="pelanggan_id">
                            <option value="">Umum</option>
                            @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-select" id="metode_pembayaran" required>
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
                                <th class="text-end">Harga</th>
                                <th>Pajak (%)</th>
                                <th class="text-end">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-body">
                            <tr id="cart-empty">
                                <td colspan="6" class="text-center text-muted py-4">Belum ada item.</td>
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
                        <label class="form-label">Pajak Transaksi (%)</label>
                        <input type="number" name="pajak_transaksi" id="pajak_transaksi" class="form-control" value="0" min="0" step="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah Bayar</label>
                        <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control" value="0" min="0" step="1" required>
                        <small class="text-muted">Kembalian: <span id="summary-kembalian">Rp 0</span></small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="pos-actions mt-4">
                    <button type="button" class="btn btn-info" id="preview-nota-btn">
                        <i class="fa-solid fa-receipt me-2"></i>Preview Nota
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit-pos-btn">
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

<div class="modal fade" id="notaPreviewModal" tabindex="-1" aria-labelledby="notaPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notaPreviewModalLabel">Preview Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="nota-preview-content">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="save-from-preview">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Transaksi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const OFFLINE_QUEUE_KEY = 'apocare_pos_offline_queue_v1';
    const syncUrl = @json(route('transaksi.penjualan.sync'));
    const indexUrl = @json(route('transaksi.penjualan.index'));

    const cartBody = document.getElementById('cart-body');
    const cartEmpty = document.getElementById('cart-empty');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryPajak = document.getElementById('summary-pajak');
    const summaryTotal = document.getElementById('summary-total');
    const summaryKembalian = document.getElementById('summary-kembalian');
    const queueCountEl = document.getElementById('offline-queue-count');
    const statusLabelEl = document.getElementById('offline-status-label');
    const statusSubTextEl = document.getElementById('offline-status-subtext');
    const statusDotEl = document.getElementById('offline-dot');
    const syncBtn = document.getElementById('sync-offline-btn');
    const form = document.getElementById('pos-form');
    const submitPosBtn = document.getElementById('submit-pos-btn');

    const pajakTransaksiInput = document.getElementById('pajak_transaksi');
    const jumlahBayarInput = document.getElementById('jumlah_bayar');
    const pelangganInput = document.getElementById('pelanggan_id');
    const metodePembayaranInput = document.getElementById('metode_pembayaran');
    const catatanInput = document.getElementById('catatan');
    const clientReferenceInput = document.getElementById('client_reference');

    const formatRupiah = (value) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value || 0);
    };

    const parseNumber = (value) => {
        const parsed = parseFloat(value);
        return Number.isNaN(parsed) ? 0 : parsed;
    };

    const generateClientReference = () => {
        if (window.crypto && typeof window.crypto.randomUUID === 'function') {
            return `POS-${window.crypto.randomUUID()}`;
        }

        return `POS-${Date.now()}-${Math.floor(Math.random() * 1_000_000)}`;
    };

    const getOfflineQueue = () => {
        try {
            const raw = localStorage.getItem(OFFLINE_QUEUE_KEY);
            if (!raw) return [];
            const parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch (_) {
            return [];
        }
    };

    const setOfflineQueue = (queue) => {
        localStorage.setItem(OFFLINE_QUEUE_KEY, JSON.stringify(queue));
        updateOfflineStatus();
    };

    const addToOfflineQueue = (transaction) => {
        const queue = getOfflineQueue();
        queue.push(transaction);
        setOfflineQueue(queue);
    };

    const removeClientReferences = (clientReferences) => {
        if (!Array.isArray(clientReferences) || clientReferences.length === 0) return;

        const refSet = new Set(clientReferences);
        const nextQueue = getOfflineQueue().filter((item) => !refSet.has(item.client_reference));
        setOfflineQueue(nextQueue);
    };

    const updateOfflineStatus = () => {
        const queue = getOfflineQueue();
        const queueCount = queue.length;

        queueCountEl.textContent = String(queueCount);
        statusSubTextEl.textContent = `Antrian offline: ${queueCount} transaksi`;

        if (navigator.onLine) {
            statusLabelEl.textContent = 'Online - POS siap sinkronisasi';
            statusDotEl.classList.remove('is-offline');
            statusDotEl.classList.add('is-online');
        } else {
            statusLabelEl.textContent = 'Offline - transaksi akan disimpan lokal';
            statusDotEl.classList.remove('is-online');
            statusDotEl.classList.add('is-offline');
        }
    };

    const recalcTotals = () => {
        let subtotal = 0;
        let pajakItem = 0;

        const rows = cartBody.querySelectorAll('tr[data-produk-id]');
        rows.forEach((row) => {
            const qty = parseNumber(row.querySelector('.cart-qty').value);
            const harga = parseNumber(row.querySelector('.cart-price').value);
            const pajak = parseNumber(row.querySelector('.cart-pajak').value);

            const lineSubtotal = qty * harga;
            const linePajak = lineSubtotal * (pajak / 100);
            const lineTotal = lineSubtotal + linePajak;

            subtotal += lineSubtotal;
            pajakItem += linePajak;

            row.querySelector('.line-subtotal').textContent = formatRupiah(lineTotal);
            row.querySelector('.cart-price-display').textContent = formatRupiah(harga);
        });

        const pajakTransaksi = parseNumber(pajakTransaksiInput.value);
        const totalPajak = pajakItem + (subtotal * (pajakTransaksi / 100));
        const totalAkhir = subtotal + totalPajak;

        const jumlahBayar = parseNumber(jumlahBayarInput.value);
        const kembalian = jumlahBayar - totalAkhir;

        summarySubtotal.textContent = formatRupiah(subtotal);
        summaryPajak.textContent = formatRupiah(totalPajak);
        summaryTotal.textContent = formatRupiah(totalAkhir);
        summaryKembalian.textContent = formatRupiah(kembalian);
    };

    const reindexCart = () => {
        const rows = cartBody.querySelectorAll('tr[data-produk-id]');
        rows.forEach((row, index) => {
            row.querySelector('.input-produk-id').name = `items[${index}][produk_id]`;
            row.querySelector('.cart-qty').name = `items[${index}][jumlah]`;
            row.querySelector('.cart-price').name = `items[${index}][harga_satuan]`;
            row.querySelector('.cart-pajak').name = `items[${index}][persentase_pajak]`;
        });
    };

    const addToCart = (data) => {
        let row = cartBody.querySelector(`tr[data-produk-id='${data.id}']`);
        if (row) {
            const qtyInput = row.querySelector('.cart-qty');
            qtyInput.value = parseNumber(qtyInput.value) + 1;
            recalcTotals();
            return;
        }

        const empty = document.getElementById('cart-empty');
        if (empty) {
            empty.remove();
        }

        row = document.createElement('tr');
        row.dataset.produkId = data.id;
        row.innerHTML = `
            <td>
                <div class="fw-semibold">${data.nama}</div>
                <div class="text-muted small">${data.kode}</div>
                <input type="hidden" class="input-produk-id" value="${data.id}">
                <input type="hidden" class="cart-price" value="${data.harga}">
            </td>
            <td><input type="number" class="form-control form-control-sm cart-qty" value="1" min="1" step="1"></td>
            <td class="text-end cart-price-display">${formatRupiah(data.harga)}</td>
            <td><input type="number" class="form-control form-control-sm cart-pajak" value="${data.pajak}" min="0" step="1"></td>
            <td class="text-end line-subtotal">${formatRupiah(data.harga)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-action-delete remove-item"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        cartBody.appendChild(row);
        reindexCart();
        recalcTotals();
    };

    const getCartItemsPayload = () => {
        const rows = cartBody.querySelectorAll('tr[data-produk-id]');
        return Array.from(rows).map((row) => ({
            produk_id: parseInt(row.dataset.produkId, 10),
            jumlah: parseNumber(row.querySelector('.cart-qty').value),
            harga_satuan: parseNumber(row.querySelector('.cart-price').value),
            persentase_pajak: parseNumber(row.querySelector('.cart-pajak').value),
        }));
    };

    const buildTransactionPayload = () => {
        const items = getCartItemsPayload();
        if (items.length === 0) {
            window.showToast('warning', 'Keranjang masih kosong.');
            return null;
        }

        const clientReference = generateClientReference();
        clientReferenceInput.value = clientReference;

        return {
            client_reference: clientReference,
            pelanggan_id: pelangganInput.value ? parseInt(pelangganInput.value, 10) : null,
            metode_pembayaran: metodePembayaranInput.value,
            pajak_transaksi: parseNumber(pajakTransaksiInput.value),
            jumlah_bayar: parseNumber(jumlahBayarInput.value),
            catatan: catatanInput.value || null,
            items,
        };
    };

    const resetFormAfterSaved = () => {
        const rows = cartBody.querySelectorAll('tr[data-produk-id]');
        rows.forEach((row) => row.remove());

        if (!document.getElementById('cart-empty')) {
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'cart-empty';
            emptyRow.innerHTML = '<td colspan="6" class="text-center text-muted py-4">Belum ada item.</td>';
            cartBody.appendChild(emptyRow);
        }

        pajakTransaksiInput.value = '0';
        jumlahBayarInput.value = '0';
        catatanInput.value = '';
        clientReferenceInput.value = '';
        recalcTotals();
    };

    const postTransactionsToServer = async (transactions) => {
        const response = await fetch(syncUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ transactions })
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        return response.json();
    };

    const syncOfflineQueue = async () => {
        if (!navigator.onLine) {
            window.showToast('warning', 'Masih offline. Sinkronisasi ditunda.');
            return;
        }

        const queue = getOfflineQueue();
        if (queue.length === 0) {
            window.showToast('info', 'Tidak ada antrian offline.');
            return;
        }

        syncBtn.disabled = true;
        syncBtn.innerHTML = '<i class="fa-solid fa-rotate fa-spin me-2"></i>Sinkronisasi...';

        try {
            const response = await postTransactionsToServer(queue);
            const results = Array.isArray(response.results) ? response.results : [];

            const removableRefs = results
                .filter((item) => item.status === 'synced' || item.status === 'duplicate')
                .map((item) => item.client_reference)
                .filter(Boolean);

            removeClientReferences(removableRefs);

            const failedCount = results.filter((item) => item.status === 'failed').length;
            if (failedCount > 0) {
                window.showToast('warning', `Sinkronisasi selesai, ${failedCount} transaksi gagal.`);
            } else {
                window.showToast('success', `Sinkronisasi berhasil: ${response.synced_count || removableRefs.length} transaksi.`);
            }
        } catch (error) {
            window.showToast('error', 'Sinkronisasi gagal. Coba lagi saat koneksi stabil.');
        } finally {
            syncBtn.disabled = false;
            syncBtn.innerHTML = '<i class="fa-solid fa-rotate me-2"></i>Sinkronkan (<span id="offline-queue-count">0</span>)';
            const freshCountEl = document.getElementById('offline-queue-count');
            if (freshCountEl) {
                freshCountEl.textContent = String(getOfflineQueue().length);
            }
            updateOfflineStatus();
        }
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
        if (event.target.matches('.cart-qty, .cart-price, .cart-pajak')) {
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
            emptyRow.innerHTML = '<td colspan="6" class="text-center text-muted py-4">Belum ada item.</td>';
            cartBody.appendChild(emptyRow);
        }

        reindexCart();
        recalcTotals();
    });

    pajakTransaksiInput.addEventListener('input', recalcTotals);
    jumlahBayarInput.addEventListener('input', recalcTotals);

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const payload = buildTransactionPayload();
        if (!payload) return;

        submitPosBtn.disabled = true;

        try {
            if (navigator.onLine) {
                const result = await postTransactionsToServer([payload]);
                const first = (result.results || [])[0];

                if (first && (first.status === 'synced' || first.status === 'duplicate')) {
                    window.showToast('success', 'Transaksi berhasil disimpan.');
                    resetFormAfterSaved();
                    window.location.href = indexUrl;
                    return;
                }

                addToOfflineQueue(payload);
                resetFormAfterSaved();
                window.showToast('warning', 'Server menolak transaksi. Data disimpan ke antrian offline.');
                return;
            }

            addToOfflineQueue(payload);
            resetFormAfterSaved();
            window.showToast('warning', 'Offline: transaksi disimpan lokal dan akan disinkronkan saat online.');
        } catch (error) {
            addToOfflineQueue(payload);
            resetFormAfterSaved();
            window.showToast('warning', 'Koneksi bermasalah. Transaksi dimasukkan ke antrian offline.');
        } finally {
            submitPosBtn.disabled = false;
            updateOfflineStatus();
        }
    });

    syncBtn.addEventListener('click', syncOfflineQueue);

    window.addEventListener('online', async () => {
        updateOfflineStatus();
        await syncOfflineQueue();
    });

    window.addEventListener('offline', updateOfflineStatus);

    updateOfflineStatus();
    recalcTotals();

    if (navigator.onLine && getOfflineQueue().length > 0) {
        syncOfflineQueue();
    }

    const previewNotaBtn = document.getElementById('preview-nota-btn');
    const notaPreviewModal = document.getElementById('notaPreviewModal');
    const notaPreviewContent = document.getElementById('nota-preview-content');
    const saveFromPreviewBtn = document.getElementById('save-from-preview');

    previewNotaBtn.addEventListener('click', function() {
        const items = getCartItemsPayload();
        if (items.length === 0) {
            window.showToast('warning', 'Keranjang masih kosong.');
            return;
        }

        const rows = cartBody.querySelectorAll('tr[data-produk-id]');
        let html = `
            <div style="font-family: monospace; font-size: 12px;">
                <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px;">
                    <strong>APOCARE</strong><br>
                    Apotek Modern<br>
                    Jl. Contoh No. 123<br>
                </div>
                <div style="border-bottom: 1px dashed #000; padding-bottom: 5px; margin-bottom: 10px;">
                    <div>${new Date().toLocaleString('id-ID')}</div>
                    <div>Pelanggan: ${pelangganInput.options[pelangganInput.selectedIndex]?.text || 'Umum'}</div>
                </div>
                <table style="width: 100%; border-collapse: collapse;">
        `;

        let subtotal = 0;
        rows.forEach(row => {
            const nama = row.querySelector('.fw-semibold').textContent;
            const qty = parseNumber(row.querySelector('.cart-qty').value);
            const harga = parseNumber(row.querySelector('.cart-price').value);
            const pajak = parseNumber(row.querySelector('.cart-pajak').value);
            const lineTotal = (qty * harga) + (qty * harga * pajak / 100);
            subtotal += lineTotal;

            html += `
                <tr>
                    <td style="padding: 3px 0;">${nama}</td>
                    <td style="text-align: right; padding: 3px 0;">${qty} x ${formatRupiah(harga)}</td>
                    <td style="text-align: right; padding: 3px 0;">${formatRupiah(lineTotal)}</td>
                </tr>
            `;
        });

        const pajakTransaksi = parseNumber(pajakTransaksiInput.value);
        const totalPajak = subtotal * (pajakTransaksi / 100);
        const total = subtotal + totalPajak;
        const jumlahBayar = parseNumber(jumlahBayarInput.value);
        const kembalian = jumlahBayar - total;

        html += `
                </table>
                <div style="border-top: 1px dashed #000; margin-top: 10px; padding-top: 10px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Subtotal:</span>
                        <span>${formatRupiah(subtotal)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Pajak (${pajakTransaksi}%):</span>
                        <span>${formatRupiah(totalPajak)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 14px; margin-top: 5px;">
                        <span>TOTAL:</span>
                        <span>${formatRupiah(total)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                        <span>Bayar:</span>
                        <span>${formatRupiah(jumlahBayar)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Kembalian:</span>
                        <span>${formatRupiah(kembalian)}</span>
                    </div>
                </div>
                <div style="text-align: center; border-top: 1px dashed #000; margin-top: 10px; padding-top: 10px;">
                    Terima kasih atas kunjungan Anda<br>
                    Silahkan datang kembali
                </div>
            </div>
        `;

        notaPreviewContent.innerHTML = html;
        const modal = new bootstrap.Modal(notaPreviewModal);
        modal.show();
    });

    saveFromPreviewBtn.addEventListener('click', function() {
        notaPreviewModal.querySelector('.btn-close').click();
        form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
    });
</script>
@endpush