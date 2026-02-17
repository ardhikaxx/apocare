@extends('layouts.app')

@section('title', 'Auto Set Harga Jual')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Auto Set Harga Jual</h1>
        <p class="text-muted mb-0">Pengaturan otomatis harga jual berdasarkan persentase markup.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Pengaturan Default</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('harga.simpan-pengaturan') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Persentase Markup Default</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="persentase_markup_default" class="form-control" 
                                value="{{ $pengaturan->persentase_markup_default ?? 20 }}" min="0" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Markup dari harga beli</small>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="statusAktif" name="status_aktif" 
                            {{ ($pengaturan->status_aktif ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="statusAktif">Aktifkan Auto Set Harga</label>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i>Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Update Massal</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('harga.update-semua') }}" method="POST" id="updateMassalForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Persentase Markup</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="persentase_markup" class="form-control" 
                                    value="{{ $pengaturan->persentase_markup_default ?? 20 }}" min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori (Opsional)</label>
                            <select name="kategori_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fa-solid fa-calculator me-2"></i>Hitung & Update
                            </button>
                        </div>
                    </div>
                </form>
                
                <hr>
                
                <h6>Kalkulator Harga</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" id="hargaBeli" class="form-control" placeholder="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Persentase Markup</label>
                        <input type="number" id="persentaseMarkup" class="form-control" value="{{ $pengaturan->persentase_markup_default ?? 20 }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-info w-100" onclick="hitungHarga()">
                            <i class="fa-solid fa-calculator me-2"></i>Hitung
                        </button>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-light rounded">
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted d-block">Harga Jual</small>
                            <strong class="text-success" id="hasilHargaJual" style="font-size: 1.5rem;">Rp 0</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Keuntungan</small>
                            <strong class="text-primary" id="hasilKeuntungan" style="font-size: 1.5rem;">Rp 0</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <h5 class="card-title mb-0">Daftar Produk</h5>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
                <select name="kategori_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach($kategori as $item)
                        <option value="{{ $item->id }}" {{ request('kategori_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-soft"><i class="fa-solid fa-search"></i></button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Markup</th>
                        <th class="text-center">Margin</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produk as $item)
                    <tr>
                        <td>{{ $item->kode }}</td>
                        <td>
                            <strong>{{ $item->nama }}</strong>
                            @if($item->jenis_produk && $item->jenis_produk != 'umum')
                                <span class="badge bg-danger ms-1">{{ $item->jenis_produk }}</span>
                            @endif
                        </td>
                        <td>{{ $item->kategori->nama ?? '-' }}</td>
                        <td class="text-end">Rp {{ number_format($item->harga_beli ?? 0, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($item->harga_jual ?? 0, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $item->persentase_markup ?? 20 }}%</span>
                        </td>
                        <td class="text-center">
                            @php
                                $margin = $item->harga_beli > 0 ? (($item->harga_jual - $item->harga_beli) / $item->harga_beli) * 100 : 0;
                            @endphp
                            <span class="badge {{ $margin > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ number_format($margin, 1) }}%
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-primary" onclick="bukaModal({{ $item->id }}, '{{ $item->nama }}', {{ $item->harga_beli ?? 0 }}, {{ $item->persentase_markup ?? 20 }})">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editHargaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Harga Jual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditHarga">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="editProdukId" name="produk_id">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" id="editNamaProduk" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Beli</label>
                        <input type="text" id="editHargaBeli" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Persentase Markup</label>
                        <div class="input-group">
                            <input type="number" step="0.01" id="editPersentaseMarkup" name="persentase_markup" class="form-control" min="0" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Jual Baru</label>
                        <input type="text" id="editHargaJualBaru" class="form-control" readonly>
                        <small class="text-muted">Akan dihitung otomatis berdasarkan markup</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let modalEdit = null;

document.addEventListener('DOMContentLoaded', function() {
    modalEdit = new bootstrap.Modal(document.getElementById('editHargaModal'));
    
    document.getElementById('editPersentaseMarkup').addEventListener('input', hitungHargaModal);
});

function hitungHarga() {
    const hargaBeli = parseFloat(document.getElementById('hargaBeli').value) || 0;
    const persentase = parseFloat(document.getElementById('persentaseMarkup').value) || 0;
    
    const hargaJual = hargaBeli + (hargaBeli * persentase / 100);
    const keuntungan = hargaJual - hargaBeli;
    
    document.getElementById('hasilHargaJual').textContent = 'Rp ' + hargaJual.toLocaleString('id-ID');
    document.getElementById('hasilKeuntungan').textContent = 'Rp ' + keuntungan.toLocaleString('id-ID');
}

function hitungHargaModal() {
    const hargaBeli = parseFloat(document.getElementById('editHargaBeli').value.replace(/[^0-9]/g, '')) || 0;
    const persentase = parseFloat(document.getElementById('editPersentaseMarkup').value) || 0;
    const hargaJual = hargaBeli + (hargaBeli * persentase / 100);
    
    document.getElementById('editHargaJualBaru').value = 'Rp ' + Math.round(hargaJual).toLocaleString('id-ID');
}

function bukaModal(id, nama, hargaBeli, markup) {
    document.getElementById('editProdukId').value = id;
    document.getElementById('editNamaProduk').value = nama;
    document.getElementById('editHargaBeli').value = 'Rp ' + hargaBeli.toLocaleString('id-ID');
    document.getElementById('editPersentaseMarkup').value = markup;
    hitungHargaModal();
    modalEdit.show();
}

document.getElementById('formEditHarga').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch('{{ route("harga.update-persentase") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            produk_id: document.getElementById('editProdukId').value,
            persentase_markup: document.getElementById('editPersentaseMarkup').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message
            }).then(() => {
                location.reload();
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Terjadi kesalahan'
        });
    });
});
</script>
@endpush
