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
            $columns = ['Kode', 'Nama Produk', 'Kategori', 'Harga Jual', 'Stok Minimum', 'Status', 'Aksi'];
            $rows = $produk->map(function ($item) {
                $status = $item->status_aktif ? '<span class="badge-soft success">Aktif</span>' : '<span class="badge-soft warning">Nonaktif</span>';
                $deleteId = 'delete-form-' . $item->id;
                $aksi = '<div class="d-flex gap-2">'
                    . '<a class="btn btn-sm btn-action-edit" href="' . route('master.produk.edit', $item) . '"><i class="fa-solid fa-pen"></i></a>'
                    . '<form id="' . $deleteId . '" method="POST" action="' . route('master.produk.destroy', $item) . '">'
                    . csrf_field() . method_field('DELETE')
                    . '<button class="btn btn-sm btn-action-delete" type="button" onclick="confirmDelete(\'' . $deleteId . '\')"><i class="fa-solid fa-trash"></i></button>'
                    . '</form>'
                    . '</div>';
                return [
                    $item->kode,
                    $item->nama,
                    $item->kategori ? $item->kategori->nama : '-',
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

