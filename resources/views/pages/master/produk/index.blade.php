@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Data Master'],
    ['label' => 'Produk']
]])

@include('pages.shared.page-header', [
    'title' => 'Manajemen Produk',
    'subtitle' => 'Kelola produk, batch, harga, dan multi satuan.',
    'actions' => [
        ['label' => 'Export Excel', 'icon' => 'fa-solid fa-file-excel', 'class' => 'btn btn-soft', 'href' => route('master.produk.export.excel')],
        ['label' => 'Export CSV', 'icon' => 'fa-solid fa-file-csv', 'class' => 'btn btn-soft', 'href' => route('master.produk.export.csv')],
        ['label' => 'Export PDF', 'icon' => 'fa-solid fa-file-pdf', 'class' => 'btn btn-soft', 'href' => route('master.produk.export.pdf')],
        ['label' => 'Tambah Produk', 'icon' => 'fa-solid fa-plus', 'class' => 'btn btn-primary', 'href' => route('master.produk.create')]
    ]
])

<div class="card">
    <div class="card-header">Daftar Produk</div>
    <div class="card-body">
        @php
            $columns = ['Favorit', 'Kode', 'Nama Produk', 'Golongan', 'Expired', 'Harga Jual', 'Stok Minimum', 'Status', 'Aksi'];
            $rows = $produk->map(function ($item) {
                $status = $item->status_aktif ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-warning text-dark">Nonaktif</span>';
                
                $golonganColors = [
                    'umum' => 'bg-secondary',
                    'keras' => 'bg-danger',
                    'psikotropika' => 'bg-warning text-dark',
                    'golongan' => 'bg-dark'
                ];
                $golonganLabels = [
                    'umum' => 'Obat Umum',
                    'keras' => 'Obat Keras',
                    'psikotropika' => 'Psikotropika',
                    'golongan' => 'Narcotic'
                ];
                $golongan = '<span class="badge ' . ($golonganColors[$item->jenis_produk] ?? 'bg-secondary') . '">' . ($golonganLabels[$item->jenis_produk] ?? $item->jenis_produk) . '</span>';
                
                $expiredBadge = '';
                if ($item->is_expired) {
                    $expiredBadge = '<span class="badge bg-danger">Expired</span>';
                } elseif ($item->tanggal_expired && $item->tanggal_expired->isPast()) {
                    $expiredBadge = '<span class="badge bg-danger">Expired</span>';
                } elseif ($item->tanggal_expired && $item->tanggal_expired->diffInDays(now()) <= 30) {
                    $expiredBadge = '<span class="badge bg-warning text-dark">Exp: ' . $item->tanggal_expired->format('d/m/y') . '</span>';
                } else {
                    $expiredBadge = '<span class="badge bg-light text-dark">' . ($item->tanggal_expired ? $item->tanggal_expired->format('d/m/Y') : '-') . '</span>';
                }
                
                $favoritId = 'favorit-form-' . $item->id;
                $favorit = $item->is_favorit 
                    ? '<form id="' . $favoritId . '" method="POST" action="' . route('master.produk.favorit', $item) . '">' . csrf_field() . method_field('PATCH') . '<button type="submit" class="btn btn-sm btn-warning" title="Hapus dari favorit"><i class="fa-solid fa-star"></i></button></form>'
                    : '<form id="' . $favoritId . '" method="POST" action="' . route('master.produk.favorit', $item) . '">' . csrf_field() . method_field('PATCH') . '<button type="submit" class="btn btn-sm btn-outline-warning" title="Tambah ke favorit"><i class="fa-regular fa-star"></i></button></form>';
                $deleteId = 'delete-form-' . $item->id;
                $aksi = '<div class="d-flex gap-2">'
                    . '<a class="btn btn-sm btn-action-edit" href="' . route('master.produk.edit', $item) . '"><i class="fa-solid fa-pen"></i></a>'
                    . '<form id="' . $deleteId . '" method="POST" action="' . route('master.produk.destroy', $item) . '">'
                    . csrf_field() . method_field('DELETE')
                    . '<button class="btn btn-sm btn-action-delete" type="button" onclick="confirmDelete(\'' . $deleteId . '\')"><i class="fa-solid fa-trash"></i></button>'
                    . '</form>'
                    . '</div>';
                return [
                    $favorit,
                    $item->kode,
                    $item->nama,
                    $golongan,
                    $expiredBadge,
                    'Rp ' . number_format($item->harga_jual ?? 0, 0, ',', '.'),
                    $item->stok_minimum ?? 0,
                    $status,
                    $aksi
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection

