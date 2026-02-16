@extends('layouts.app')

@section('title', 'Tambah Resep')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
@endpush

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Tambah Resep</h1>
        <p class="text-muted mb-0">Input resep dokter dan detail obat.</p>
    </div>
    <a href="{{ route('resep.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('resep.store') }}" method="POST" id="resep-form">
    @csrf
    <div class="pos-shell">
        <div class="pos-panel">
            <div class="panel-header">
                <div>
                    <h5 class="mb-0">Daftar Produk</h5>
                    <small class="text-muted">Klik tambah untuk masuk ke resep.</small>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga Jual</th>
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
                                    <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <button type="button"
                                            class="btn btn-sm btn-primary add-resep"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama }}"
                                            data-kode="{{ $item->kode }}"
                                            data-harga="{{ $item->harga_jual }}">
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
                    <h5 class="mb-0">Detail Resep</h5>
                    <small class="text-muted">Lengkapi informasi pasien dan obat.</small>
                </div>
            </div>
            <div class="panel-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Pasien</label>
                        <select name="pelanggan_id" class="form-select" required>
                            <option value="">Pilih pasien</option>
                            @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dokter</label>
                        <select name="dokter_id" class="form-select" required>
                            <option value="">Pilih dokter</option>
                            @foreach($dokter as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Resep</label>
                        <input type="date" name="tanggal_resep" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="PENDING">PENDING</option>
                            <option value="SEBAGIAN">SEBAGIAN</option>
                            <option value="SELESAI">SELESAI</option>
                            <option value="BATAL">BATAL</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Diagnosa</label>
                        <input type="text" name="diagnosa" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <input type="text" name="catatan" class="form-control">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="resep-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Dosis</th>
                                <th>Frekuensi</th>
                                <th>Durasi</th>
                                <th>Cara Pakai</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="resep-body">
                            <tr id="resep-empty">
                                <td colspan="9" class="text-center text-muted">Belum ada item.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pos-actions mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Resep
                    </button>
                    <a href="{{ route('resep.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const resepBody = document.getElementById('resep-body');
    const resepEmpty = document.getElementById('resep-empty');

    const formatRupiah = (value) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value || 0);
    };

    const recalcRow = (row) => {
        const qty = parseFloat(row.querySelector('.resep-qty').value) || 0;
        const harga = parseFloat(row.querySelector('.resep-harga').value) || 0;
        const total = qty * harga;
        row.querySelector('.resep-total').textContent = formatRupiah(total);
    };

    const reindexResep = () => {
        resepBody.querySelectorAll('tr[data-produk-id]').forEach((row, index) => {
            row.querySelector('.input-produk-id').name = `items[${index}][produk_id]`;
            row.querySelector('.resep-dosis').name = `items[${index}][dosis]`;
            row.querySelector('.resep-frekuensi').name = `items[${index}][frekuensi]`;
            row.querySelector('.resep-durasi').name = `items[${index}][durasi]`;
            row.querySelector('.resep-cara').name = `items[${index}][cara_pakai]`;
            row.querySelector('.resep-qty').name = `items[${index}][jumlah_resep]`;
            row.querySelector('.resep-harga').name = `items[${index}][harga_satuan]`;
        });
    };

    const addResep = (data) => {
        let row = resepBody.querySelector(`tr[data-produk-id='${data.id}']`);
        if (row) {
            const qtyInput = row.querySelector('.resep-qty');
            qtyInput.value = (parseFloat(qtyInput.value) || 0) + 1;
            recalcRow(row);
            return;
        }

        if (resepEmpty) {
            resepEmpty.remove();
        }

        row = document.createElement('tr');
        row.dataset.produkId = data.id;
        row.innerHTML = `
            <td>
                <div class="fw-semibold">${data.nama}</div>
                <div class="text-muted small">${data.kode}</div>
                <input type="hidden" class="input-produk-id" value="${data.id}">
            </td>
            <td><input type="text" class="form-control form-control-sm resep-dosis" placeholder="Dosis"></td>
            <td><input type="text" class="form-control form-control-sm resep-frekuensi" placeholder="3x sehari"></td>
            <td><input type="text" class="form-control form-control-sm resep-durasi" placeholder="5 hari"></td>
            <td><input type="text" class="form-control form-control-sm resep-cara" placeholder="Sesudah makan"></td>
            <td><input type="number" class="form-control form-control-sm resep-qty" value="1" min="1" step="1"></td>
            <td><input type="number" class="form-control form-control-sm resep-harga" value="${data.harga}" min="0" step="1"></td>
            <td class="resep-total">${formatRupiah(data.harga)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-action-delete remove-resep"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        resepBody.appendChild(row);
        reindexResep();
        recalcRow(row);
    };

    document.querySelectorAll('.add-resep').forEach((btn) => {
        btn.addEventListener('click', () => {
            addResep({
                id: btn.dataset.id,
                nama: btn.dataset.nama,
                kode: btn.dataset.kode,
                harga: btn.dataset.harga
            });
        });
    });

    resepBody.addEventListener('input', (event) => {
        if (event.target.matches('.resep-qty, .resep-harga')) {
            recalcRow(event.target.closest('tr'));
        }
    });

    resepBody.addEventListener('click', (event) => {
        const btn = event.target.closest('.remove-resep');
        if (!btn) return;
        btn.closest('tr').remove();
        if (!resepBody.querySelector('tr[data-produk-id]')) {
            const emptyRow = document.createElement('tr');
            emptyRow.id = 'resep-empty';
            emptyRow.innerHTML = '<td colspan="9" class="text-center text-muted">Belum ada item.</td>';
            resepBody.appendChild(emptyRow);
        }
        reindexResep();
    });

    document.getElementById('resep-form').addEventListener('submit', (event) => {
        if (!resepBody.querySelector('tr[data-produk-id]')) {
            event.preventDefault();
            showAlert('warning', 'Peringatan', 'Tambahkan minimal satu item.');
        }
    });
</script>
@endpush
