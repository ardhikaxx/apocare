@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Karyawan']
]])

@include('pages.shared.page-header', [
    'title' => 'Manajemen Karyawan',
    'subtitle' => 'Data karyawan dan status kepegawaian.',
    'actions' => [
        ['label' => 'Tambah Karyawan', 'icon' => 'fa-solid fa-plus', 'class' => 'btn btn-primary', 'href' => route('karyawan.create')]
    ]
])

<div class="card">
    <div class="card-header">Daftar Karyawan</div>
    <div class="card-body">
        @php
            $columns = ['Nomor', 'Nama', 'Jabatan', 'Departemen', 'Status', 'Aksi'];
            $rows = $karyawan->map(function ($item) {
                $status = $item->status_aktif ? '<span class="badge-soft success">Aktif</span>' : '<span class="badge-soft warning">Nonaktif</span>';
                $deleteId = 'delete-form-' . $item->id;
                $aksi = '<div class="d-flex gap-2">'
                    . '<a class="btn btn-sm btn-action-edit" href="' . route('karyawan.edit', $item) . '"><i class="fa-solid fa-pen"></i></a>'
                    . '<form id="' . $deleteId . '" method="POST" action="' . route('karyawan.destroy', $item) . '">'
                    . csrf_field() . method_field('DELETE')
                    . '<button class="btn btn-sm btn-action-delete" type="button" onclick="confirmDelete(\'' . $deleteId . '\')"><i class="fa-solid fa-trash"></i></button>'
                    . '</form>'
                    . '</div>';
                return [
                    $item->nomor_karyawan,
                    $item->pengguna ? $item->pengguna->nama : '-',
                    $item->jabatan ?? '-',
                    $item->departemen ?? '-',
                    $status,
                    $aksi
                ];
            })->toArray();
        @endphp
        @include('pages.shared.table', compact('columns', 'rows'))
    </div>
</div>
@endsection
