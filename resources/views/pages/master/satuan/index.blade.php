@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Data Master'],
    ['label' => 'Satuan']
]])

@include('pages.shared.page-header', [
    'title' => 'Manajemen Satuan',
    'subtitle' => 'Kelola satuan dasar dan satuan konversi produk.',
    'actions' => [
        ['label' => 'Export Excel', 'icon' => 'fa-solid fa-file-excel', 'class' => 'btn btn-soft', 'href' => route('master.satuan.export.excel')],
        ['label' => 'Export CSV', 'icon' => 'fa-solid fa-file-csv', 'class' => 'btn btn-soft', 'href' => route('master.satuan.export.csv')],
        ['label' => 'Export PDF', 'icon' => 'fa-solid fa-file-pdf', 'class' => 'btn btn-soft', 'href' => route('master.satuan.export.pdf')],
        ['label' => 'Tambah Satuan', 'icon' => 'fa-solid fa-plus', 'class' => 'btn btn-primary', 'href' => route('master.satuan.create')]
    ]
])

<div class="card">
    <div class="card-header">Daftar Satuan</div>
    <div class="card-body">
        @php
            $columns = ['Kode', 'Nama', 'Keterangan', 'Status', 'Aksi'];
            $rows = $satuan->map(function ($item) {
                $status = $item->status_aktif ? '<span class="badge-soft success">Aktif</span>' : '<span class="badge-soft warning">Nonaktif</span>';
                $aksi = '<div class="d-flex gap-2">'
                    . '<a class="btn btn-sm btn-soft" href="' . route('master.satuan.edit', $item) . '"><i class="fa-solid fa-pen"></i></a>'
                    . '<form method="POST" action="' . route('master.satuan.destroy', $item) . '" onsubmit="return confirm(\'Hapus satuan ini?\')">'
                    . csrf_field() . method_field('DELETE')
                    . '<button class="btn btn-sm btn-soft" type="submit"><i class="fa-solid fa-trash"></i></button>'
                    . '</form>'
                    . '</div>';
                return [
                    $item->kode,
                    $item->nama,
                    $item->keterangan ?? '-',
                    $status,
                    $aksi
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection

