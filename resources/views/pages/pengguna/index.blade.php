@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Pengguna & Peran'],
    ['label' => 'Pengguna']
]])

@include('pages.shared.page-header', [
    'title' => 'Manajemen Pengguna',
    'subtitle' => 'Kelola akun dan status akses.',
    'actions' => [
        ['label' => 'Tambah Pengguna', 'icon' => 'fa-solid fa-plus', 'class' => 'btn btn-primary', 'href' => route('pengguna.create')]
    ]
])

<div class="card">
    <div class="card-header">Daftar Pengguna</div>
    <div class="card-body">
        @php
            $columns = ['Nama', 'Email', 'Peran', 'Status', 'Aksi'];
            $rows = $pengguna->map(function ($item) {
                $status = $item->status_aktif ? '<span class="badge-soft success">Aktif</span>' : '<span class="badge-soft warning">Nonaktif</span>';
                $aksi = '<div class="d-flex gap-2">'
                    . '<a class="btn btn-sm btn-soft" href="' . route('pengguna.edit', $item) . '"><i class="fa-solid fa-pen"></i></a>'
                    . '<form method="POST" action="' . route('pengguna.destroy', $item) . '" onsubmit="return confirm(\'Hapus pengguna ini?\')">'
                    . csrf_field() . method_field('DELETE')
                    . '<button class="btn btn-sm btn-soft" type="submit"><i class="fa-solid fa-trash"></i></button>'
                    . '</form>'
                    . '</div>';
                return [
                    $item->nama,
                    $item->email ?? '-',
                    $item->peran ? $item->peran->nama : '-',
                    $status,
                    $aksi
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection
