@extends('layouts.app')

@section('title', 'Buat Penyesuaian Stok')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
@endpush

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Penyesuaian Stok</h1>
        <p class="text-muted mb-0">Buat penyesuaian stok baru.</p>
    </div>
    <a href="{{ route('persediaan.penyesuaian.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('persediaan.penyesuaian.store') }}" method="POST" id="penyesuaian-form">
    @csrf
    <div class="pos-shell">
        <div class="pos-panel">
            <div class="panel-header">
                <div>
                    <h5 class="mb-0">Daftar Produk</h5>
                    <small class="text-muted">Klik tambah untuk masuk ke daftar penyesuaian.</small>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Stok Sistem</th>
                                <th>Harga Beli</th>
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
                                    <td>{{ formatAngka($stok) }}</td>
                                    <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <button type="button"
                                            class="btn btn-sm btn-primary add-adjust"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama }}"
                                            data-kode="{{ $item->kode }}"
                                            data-stok="{{ $stok }}"
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
                    <h5 class="mb-0">Detail Penyesuaian</h5>
                    <small class="text-muted">Lengkapi informasi penyesuaian.</small>
                </div>
            </div>
            <div class="panel-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Penyesuaian</label>
                        <input type="date" name="tanggal_penyesuaian" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Penyesuaian</label>
                        <select name="jenis_penyesuaian" class="form-select" required>
                            <option value="PENAMBAHAN">PENAMBAHAN</option>
                            <option value="PENGURANGAN">PENGURANGAN</option>
                            <option value="RUSAK">RUSAK</option>
                            <option value="KADALUARSA">KADALUARSA</option>
                            <option value="KOREKSI">KOREKSI</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="DRAFT">DRAFT</option>
                            <option value="DISETUJUI">DISETUJUI</option>
                            <option value="DITOLAK">DITOLAK</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Catatan</label>
                        <input type="text" name="catatan" class="form-control">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="adjust-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Stok Sistem</th>
                                <th>Stok Aktual</th>
                                <th>Selisih</th>
                                <th>Harga</th>
                                <th>Total Nilai</th>
                                <th>Catatan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="adjust-body">
                            <tr id="adjust-empty">
                                <td colspan="8" class="text-center text-muted">Belum ada item.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pos-actions mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Penyesuaian
                    </button>
                    <a href="{{ route('persediaan.penyesuaian.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const adjustBody = document.getElementById('adjust-body');
    const adjustEmpty = document.getElementById('adjust-empty');

    const formatRupiah = (value) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value || 0);
    };

    const recalcRow = (row) => {
        const sistem = parseFloat(row.querySelector('.adj-sistem').value) || 0;
        const aktual = parseFloat(row.querySelector('.adj-aktual').value) || 0;
        const harga = parseFloat(row.querySelector('.adj-harga').value) || 0;
        const selisih = aktual - sistem;
        const total = selisih * harga;
        row.querySelector('.adj-selisih').textContent = selisih.toFixed(2);
        row.querySelector('.adj-total').textContent = formatRupiah(total);
    };

    const reindexAdjust = () => {
        adjustBody.querySelectorAll('tr[data-produk-id]').forEach((row, index) => {
            row.querySelector('.input-produk-id').name = `items[${index}][produk_id]`;
            row.querySelector('.adj-sistem').name = `items[${index}][jumlah_sistem]`;
            row.querySelector('.adj-aktual').name = `items[${index}][jumlah_aktual]`;
            row.querySelector('.adj-harga').name = `items[${index}][harga_satuan]`;
            row.querySelector('.adj-catatan').name = `items[${index}][catatan]`;
        });
    };

    const addAdjust = (data) => {
        let row = adjustBody.querySelector(`tr[data-produk-id='${data.id}']`);
        if (row) {
            const aktualInput = row.querySelector('.adj-aktual');
            aktualInput.value = (parseFloat(aktualInput.value) || 0) + 1;
            recalcRow(row);
            return;
        }

        if (adjustEmpty) {
            adjustEmpty.remove();
        }

        row = document.createElement('tr');
        row.dataset.produkId = data.id;
        row.innerHTML = `
            <td>
                <div class="fw-semibold">${data.nama}</div>
                <div class="text-muted small">${data.kode}</div>
                <input type="hidden" class="input-produk-id" value="${data.id}">
            </td>
            <td><input type="number" class="form-control form-control-sm adj-sistem" value="${data.stok}" readonly></td>
            <td><input type="number" class="form-control form-control-sm adj-aktual" value="${data.stok}" step="1"></td>
            <td><input type="text" class="form-control form-control-sm adj-keterangan" placeholder="Keterangan"></td>
            <td><input type="number" class="form-control form-control-sm adj-harga" value="${data.harga}" step="1"></td>
            <td class="adj-total">${formatRupiah(0)}</td>
            <td><input type="text" class="form-control form-control-sm adj-catatan" placeholder="Catatan"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-action-delete remove-adjust"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        adjustBody.appendChild(row);
        reindexAdjust();
        recalcRow(row);
    };

    document.querySelectorAll('.add-adjust').forEach((btn) => {
        btn.addEventListener('click', () => {
            addAdjust({
                id: btn.dataset.id,
                nama: btn.dataset.nama,
                kode: btn.dataset.kode,
                stok: btn.dataset.stok,
                harga: btn.dataset.harga
            });
        });
    });

    adjustBody.addEventListener('input', (event) => {
        if (event.target.matches('.adj-aktual, .adj-harga')) {
            recalcRow(event.target.closest('tr'));
        }
    });

    adjustBody.addEventListener('click', (event) => {
        const btn = event.target.closest('.remove-adjust');
        if (!btn) return;
        btn.closest('tr').remove();
        if (!adjustBody.querySelector('tr[data-produk-id]')) {
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'adjust-empty';
            emptyRow.innerHTML = '<td colspan="8" class="text-center text-muted">Belum ada item.</td>';
            adjustBody.appendChild(emptyRow);
        }
        reindexAdjust();
    });

    document.getElementById('penyesuaian-form').addEventListener('submit', (event) => {
        if (!adjustBody.querySelector('tr[data-produk-id]')) {
            event.preventDefault();
            showAlert('warning', 'Peringatan', 'Tambahkan minimal satu item.');
        }
    });
</script>
@endpush
