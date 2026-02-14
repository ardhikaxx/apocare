@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Data Master'],
    ['label' => 'Pemasok']
]])

@include('pages.shared.page-header', [
    'title' => 'Manajemen Pemasok',
    'subtitle' => 'Kelola data pemasok, termin pembayaran, dan histori pembelian.',
    'actions' => [
        ['label' => 'Export Excel', 'icon' => 'fa-solid fa-file-excel', 'class' => 'btn btn-soft', 'href' => route('master.pemasok.export.excel')],
        ['label' => 'Export CSV', 'icon' => 'fa-solid fa-file-csv', 'class' => 'btn btn-soft', 'href' => route('master.pemasok.export.csv')],
        ['label' => 'Export PDF', 'icon' => 'fa-solid fa-file-pdf', 'class' => 'btn btn-soft', 'href' => route('master.pemasok.export.pdf')],
        ['label' => 'Tambah Pemasok', 'icon' => 'fa-solid fa-plus', 'class' => 'btn btn-primary', 'href' => route('master.pemasok.create')]
    ]
])

<div class="card">
    <div class="card-header">Daftar Pemasok</div>
    <div class="card-body">
        @php
            $columns = ['Kode', 'Nama Pemasok', 'Kontak', 'Telepon', 'Status', 'Aksi'];
            $rows = $pemasok->map(function ($item) {
                $status = $item->status_aktif ? '<span class="badge-soft success">Aktif</span>' : '<span class="badge-soft warning">Nonaktif</span>';
                $aksi = '<div class="d-flex gap-2">'
                    . '<a class="btn btn-sm btn-soft" href="' . route('master.pemasok.edit', $item) . '"><i class="fa-solid fa-pen"></i></a>'
                    . '<form method="POST" action="' . route('master.pemasok.destroy', $item) . '" onsubmit="return confirm(\'Hapus pemasok ini?\')">'
                    . csrf_field() . method_field('DELETE')
                    . '<button class="btn btn-sm btn-soft" type="submit"><i class="fa-solid fa-trash"></i></button>'
                    . '</form>'
                    . '</div>';
                return [
                    $item->kode,
                    $item->nama,
                    $item->kontak_person ?? '-',
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

