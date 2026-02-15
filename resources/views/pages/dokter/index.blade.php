@extends('layouts.app')

@section('content')
@include('partials.breadcrumb', ['breadcrumbs' => [
    ['label' => 'Dokter']
]])

@include('pages.shared.page-header', [
    'title' => 'Data Dokter',
    'subtitle' => 'Kelola dokter, SIP, dan afiliasi klinik.',
    'actions' => [
        ['label' => 'Tambah Dokter', 'icon' => 'fa-solid fa-plus', 'class' => 'btn btn-primary', 'href' => route('dokter.create')]
    ]
])

<div class="card">
    <div class="card-header">Daftar Dokter</div>
    <div class="card-body">
        @php
            $columns = ['Kode', 'Nama Dokter', 'Spesialisasi', 'Telepon', 'Status', 'Aksi'];
            $rows = $dokter->map(function ($item) {
                $status = $item->status_aktif ? '<span class="badge-soft success">Aktif</span>' : '<span class="badge-soft warning">Nonaktif</span>';
                $deleteId = 'delete-form-' . $item->id;
                $aksi = '<div class="d-flex gap-2">'
                    . '<a class="btn btn-sm btn-action-edit" href="' . route('dokter.edit', $item) . '"><i class="fa-solid fa-pen"></i></a>'
                    . '<form id="' . $deleteId . '" method="POST" action="' . route('dokter.destroy', $item) . '">'
                    . csrf_field() . method_field('DELETE')
                    . '<button class="btn btn-sm btn-action-delete" type="button" onclick="confirmDelete(\'' . $deleteId . '\')"><i class="fa-solid fa-trash"></i></button>'
                    . '</form>'
                    . '</div>';
                return [
                    $item->kode,
                    $item->nama,
                    $item->spesialisasi ?? '-',
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
