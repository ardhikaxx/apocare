@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Pengguna & Peran'],
    ['label' => 'Peran']
]])

@include('pages.shared.page-header', [
    'title' => 'Manajemen Peran',
    'subtitle' => 'Atur peran dan hak akses pengguna.',
    'actions' => [
        ['label' => 'Tambah Peran', 'icon' => 'fa-solid fa-plus', 'class' => 'btn btn-primary', 'href' => route('pengguna.peran.create')]
    ]
])

<div class="card">
    <div class="card-header">Daftar Peran</div>
    <div class="card-body">
        @php
            $columns = ['Peran', 'Deskripsi', 'Jumlah Pengguna', 'Aksi'];
            $rows = $peran->map(function ($item) {
                $aksi = '<div class="d-flex gap-2">'
                    . '<a class="btn btn-sm btn-soft" href="' . route('pengguna.peran.edit', $item) . '"><i class="fa-solid fa-pen"></i></a>'
                    . '<form id="delete-form-' . $item->id . '" method="POST" action="' . route('pengguna.peran.destroy', $item) . '">'
                    . csrf_field() . method_field('DELETE')
                    . '<button class="btn btn-sm btn-soft" type="button" onclick="confirmDelete(\'delete-form-' . $item->id . '\')"><i class="fa-solid fa-trash"></i></button>'
                    . '</form>'
                    . '</div>';
                return [
                    $item->nama,
                    $item->keterangan ?? '-',
                    $item->pengguna_count,
                    $aksi
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection
