@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Data Master'],
    ['label' => 'Kategori']
]])

@include('pages.shared.page-header', [
    'title' => 'Manajemen Kategori',
    'subtitle' => 'Atur hierarki kategori produk dan icon tampilan.',
    'actions' => [
        ['label' => 'Export Excel', 'icon' => 'fa-solid fa-file-excel', 'class' => 'btn btn-soft', 'href' => route('master.kategori.export.excel')],
        ['label' => 'Export CSV', 'icon' => 'fa-solid fa-file-csv', 'class' => 'btn btn-soft', 'href' => route('master.kategori.export.csv')],
        ['label' => 'Export PDF', 'icon' => 'fa-solid fa-file-pdf', 'class' => 'btn btn-soft', 'href' => route('master.kategori.export.pdf')],
        ['label' => 'Tambah Kategori', 'icon' => 'fa-solid fa-plus', 'class' => 'btn btn-primary', 'href' => route('master.kategori.create')]
    ]
])

<div class="card">
    <div class="card-header">Daftar Kategori</div>
    <div class="card-body">
        @php
            $columns = ['Kode', 'Nama', 'Parent', 'Icon', 'Status', 'Aksi'];
            $rows = $kategori->map(function ($item) {
                $status = $item->status_aktif ? '<span class="badge-soft success">Aktif</span>' : '<span class="badge-soft warning">Nonaktif</span>';
                $aksi = '<div class="d-flex gap-2">'
                    . '<a class="btn btn-sm btn-soft" href="' . route('master.kategori.edit', $item) . '"><i class="fa-solid fa-pen"></i></a>'
                    . '<form method="POST" action="' . route('master.kategori.destroy', $item) . '" onsubmit="return confirm(\'Hapus kategori ini?\')">'
                    . csrf_field() . method_field('DELETE')
                    . '<button class="btn btn-sm btn-soft" type="submit"><i class="fa-solid fa-trash"></i></button>'
                    . '</form>'
                    . '</div>';
                return [
                    $item->kode,
                    $item->nama,
                    $item->parent ? $item->parent->nama : '-',
                    $item->ikon ? '<i class="' . $item->ikon . '"></i>' : '-',
                    $status,
                    $aksi
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection

