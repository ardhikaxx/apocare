@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Pengguna & Peran'],
    ['label' => 'Hak Akses']
]])

@include('pages.shared.page-header', [
    'title' => 'Manajemen Hak Akses',
    'subtitle' => 'Kelola permission per modul dan aksi.',
    'actions' => [
        ['label' => 'Tambah Hak Akses', 'icon' => 'fa-solid fa-plus', 'class' => 'btn btn-primary', 'href' => route('pengguna.hak-akses.create')]
    ]
])

<div class="card">
    <div class="card-header">Daftar Hak Akses</div>
    <div class="card-body">
        @php
            $columns = ['Nama', 'Kode', 'Modul', 'Aksi'];
            $rows = $hakAkses->map(function ($item) {
                $deleteId = 'delete-form-' . $item->id;
                $aksi = '<div class="d-flex gap-2">'
                    . '<a class="btn btn-sm btn-action-edit" href="' . route('pengguna.hak-akses.edit', $item) . '"><i class="fa-solid fa-pen"></i></a>'
                    . '<form id="' . $deleteId . '" method="POST" action="' . route('pengguna.hak-akses.destroy', $item) . '">'
                    . csrf_field() . method_field('DELETE')
                    . '<button class="btn btn-sm btn-action-delete" type="button" onclick="confirmDelete(\'' . $deleteId . '\')"><i class="fa-solid fa-trash"></i></button>'
                    . '</form>'
                    . '</div>';
                return [
                    $item->nama,
                    $item->kode,
                    $item->modul,
                    $aksi
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection
