@extends('layouts.app')

@section('title', 'Buat Stok Opname')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
@endpush

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Stok Opname</h1>
        <p class="text-muted mb-0">Input hasil perhitungan fisik stok.</p>
    </div>
    <a href="{{ route('persediaan.opname.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('persediaan.opname.store') }}" method="POST" id="opname-form">
    @csrf
    <div class="pos-shell">
        <div class="pos-panel">
            <div class="panel-header">
                <div>
                    <h5 class="mb-0">Daftar Produk</h5>
                    <small class="text-muted">Klik tambah untuk masuk ke daftar opname.</small>
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
                                    <td>{{ number_format($stok, 2, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <button type="button"
                                            class="btn btn-sm btn-primary add-opname"
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
                    <h5 class="mb-0">Detail Opname</h5>
                    <small class="text-muted">Lengkapi informasi opname.</small>
                </div>
            </div>
            <div class="panel-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Opname</label>
                        <input type="date" name="tanggal_opname" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="DRAFT">DRAFT</option>
                            <option value="PROSES">PROSES</option>
                            <option value="SELESAI">SELESAI</option>
                            <option value="DISETUJUI">DISETUJUI</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kategori (Opsional)</label>
                        <select name="kategori_id" class="form-select">
                            <option value="">Semua</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <input type="text" name="catatan" class="form-control">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="opname-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Stok Sistem</th>
                                <th>Stok Hitung</th>
                                <th>Selisih</th>
                                <th>Harga</th>
                                <th>Total Nilai</th>
                                <th>Catatan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="opname-body">
                            <tr id="opname-empty">
                                <td colspan="8" class="text-center text-muted">Belum ada item.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pos-actions mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Opname
                    </button>
                    <a href="{{ route('persediaan.opname.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const opnameBody = document.getElementById('opname-body');
    const opnameEmpty = document.getElementById('opname-empty');

    const formatRupiah = (value) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value || 0);
    };

    const recalcRow = (row) => {
        const sistem = parseFloat(row.querySelector('.op-sistem').value) || 0;
        const hitung = parseFloat(row.querySelector('.op-hitung').value) || 0;
        const harga = parseFloat(row.querySelector('.op-harga').value) || 0;
        const selisih = hitung - sistem;
        const total = selisih * harga;
        row.querySelector('.op-selisih').textContent = selisih.toFixed(2);
        row.querySelector('.op-total').textContent = formatRupiah(total);
    };

    const reindexOpname = () => {
        opnameBody.querySelectorAll('tr[data-produk-id]').forEach((row, index) => {
            row.querySelector('.input-produk-id').name = `items[${index}][produk_id]`;
            row.querySelector('.op-sistem').name = `items[${index}][jumlah_sistem]`;
            row.querySelector('.op-hitung').name = `items[${index}][jumlah_hitung]`;
            row.querySelector('.op-harga').name = `items[${index}][harga_satuan]`;
            row.querySelector('.op-catatan').name = `items[${index}][catatan]`;
        });
    };

    const addOpname = (data) => {
        let row = opnameBody.querySelector(`tr[data-produk-id='${data.id}']`);
        if (row) {
            const hitungInput = row.querySelector('.op-hitung');
            hitungInput.value = (parseFloat(hitungInput.value) || 0) + 1;
            recalcRow(row);
            return;
        }

        if (opnameEmpty) {
            opnameEmpty.remove();
        }

        row = document.createElement('tr');
        row.dataset.produkId = data.id;
        row.innerHTML = `
            <td>
                <div class="fw-semibold">${data.nama}</div>
                <div class="text-muted small">${data.kode}</div>
                <input type="hidden" class="input-produk-id" value="${data.id}">
            </td>
            <td><input type="number" class="form-control form-control-sm op-sistem" value="${data.stok}" readonly></td>
            <td><input type="number" class="form-control form-control-sm op-hitung" value="${data.stok}" step="0.01"></td>
            <td class="op-selisih">0.00</td>
            <td><input type="number" class="form-control form-control-sm op-harga" value="${data.harga}" step="0.01"></td>
            <td class="op-total">${formatRupiah(0)}</td>
            <td><input type="text" class="form-control form-control-sm op-catatan" placeholder="Catatan"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-action-delete remove-opname"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        opnameBody.appendChild(row);
        reindexOpname();
        recalcRow(row);
    };

    document.querySelectorAll('.add-opname').forEach((btn) => {
        btn.addEventListener('click', () => {
            addOpname({
                id: btn.dataset.id,
                nama: btn.dataset.nama,
                kode: btn.dataset.kode,
                stok: btn.dataset.stok,
                harga: btn.dataset.harga
            });
        });
    });

    opnameBody.addEventListener('input', (event) => {
        if (event.target.matches('.op-hitung, .op-harga')) {
            recalcRow(event.target.closest('tr'));
        }
    });

    opnameBody.addEventListener('click', (event) => {
        const btn = event.target.closest('.remove-opname');
        if (!btn) return;
        btn.closest('tr').remove();
        if (!opnameBody.querySelector('tr[data-produk-id]')) {
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'opname-empty';
            emptyRow.innerHTML = '<td colspan="8" class="text-center text-muted">Belum ada item.</td>';
            opnameBody.appendChild(emptyRow);
        }
        reindexOpname();
    });

    document.getElementById('opname-form').addEventListener('submit', (event) => {
        if (!opnameBody.querySelector('tr[data-produk-id]')) {
            event.preventDefault();
            showAlert('warning', 'Peringatan', 'Tambahkan minimal satu item.');
        }
    });
</script>
@endpush
