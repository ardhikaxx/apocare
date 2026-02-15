@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Pelanggan']
]])

@include('pages.shared.page-header', [
    'title' => 'Manajemen Pelanggan',
    'subtitle' => 'Kelola pelanggan retail, reseller, dan healthcare.',
    'actions' => [
        ['label' => 'Export Excel', 'icon' => 'fa-solid fa-file-excel', 'class' => 'btn btn-soft', 'href' => route('pelanggan.export.excel')],
        ['label' => 'Export CSV', 'icon' => 'fa-solid fa-file-csv', 'class' => 'btn btn-soft', 'href' => route('pelanggan.export.csv')],
        ['label' => 'Export PDF', 'icon' => 'fa-solid fa-file-pdf', 'class' => 'btn btn-soft', 'href' => route('pelanggan.export.pdf')],
        ['label' => 'Tambah Pelanggan', 'icon' => 'fa-solid fa-plus', 'class' => 'btn btn-primary', 'href' => route('pelanggan.create')]
    ]
])

<div class="card">
    <div class="card-header">Daftar Pelanggan</div>
    <div class="card-body">
        @php
            $columns = ['Kode', 'Nama', 'Kategori', 'Telepon', 'Status', 'Aksi'];
            $rows = $pelanggan->map(function ($item) {
                $status = $item->status_aktif ? '<span class="badge-soft success">Aktif</span>' : '<span class="badge-soft warning">Nonaktif</span>';
                $aksi = '<div class="d-flex gap-2">'
                    . '<a class="btn btn-sm btn-soft" href="' . route('pelanggan.edit', $item) . '"><i class="fa-solid fa-pen"></i></a>'
                    . '<form id="delete-form-' . $item->id . '" method="POST" action="' . route('pelanggan.destroy', $item) . '">'
                    . csrf_field() . method_field('DELETE')
                    . '<button class="btn btn-sm btn-soft" type="button" onclick="confirmDelete(\'delete-form-' . $item->id . '\')"><i class="fa-solid fa-trash"></i></button>'
                    . '</form>'
                    . '</div>';
                return [
                    $item->kode,
                    $item->nama,
                    $item->jenis_pelanggan,
                    $item->telepon ?? '-',
                    $status,
                    $aksi
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection

