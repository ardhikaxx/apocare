@extends('layouts.app')

@section('title', 'Resep Dokter')

@push('styles')
<style>
    .kanban-wrap {
        display: grid;
        grid-template-columns: repeat(4, minmax(240px, 1fr));
        gap: 12px;
    }

    .kanban-column {
        background: #f8fafc;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 14px;
        min-height: 220px;
        padding: 10px;
    }

    .kanban-column.is-over {
        border-color: #0e6f64;
        box-shadow: inset 0 0 0 2px rgba(14, 111, 100, 0.15);
    }

    .kanban-col-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .kanban-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-height: 140px;
    }

    .kanban-card {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 10px;
        padding: 10px;
        cursor: grab;
    }

    .kanban-card.dragging {
        opacity: 0.5;
    }

    .kanban-card .meta {
        font-size: 0.8rem;
        color: #64748b;
    }

    .kanban-card .title {
        font-weight: 600;
        font-size: 0.9rem;
    }

    @media (max-width: 1200px) {
        .kanban-wrap {
            grid-template-columns: repeat(2, minmax(240px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .kanban-wrap {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Resep Dokter</h1>
        <p class="text-muted mb-0">Kelola resep dan antrian dispensing bertahap.</p>
    </div>
    <a href="{{ route('resep.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Tambah Resep
    </a>
</div>

@php
    $tahapLabels = [
        'DITERIMA' => 'Diterima',
        'DIRACIK' => 'Diracik',
        'DIVERIFIKASI' => 'Diverifikasi',
        'DISERAHKAN' => 'Diserahkan',
    ];
    $tahapBadge = [
        'DITERIMA' => 'secondary',
        'DIRACIK' => 'warning',
        'DIVERIFIKASI' => 'info',
        'DISERAHKAN' => 'success',
    ];
    $groupedResep = $resep->groupBy('tahap_antrian');
@endphp

<div class="row g-3 mb-3">
    @foreach(['DITERIMA','DIRACIK','DIVERIFIKASI','DISERAHKAN'] as $tahap)
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-{{ $tahapBadge[$tahap] ?? 'secondary' }}">{{ $tahapLabels[$tahap] ?? $tahap }}</span>
                        <strong class="fs-4" data-summary-count="{{ $tahap }}">{{ $ringkasanTahap[$tahap] ?? 0 }}</strong>
                    </div>
                    <small class="text-muted">Antrian tahap {{ strtolower($tahapLabels[$tahap] ?? $tahap) }}</small>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Board Antrian Resep</span>
        <small class="text-muted">Drag kartu ke kolom berikutnya untuk lanjut tahap</small>
    </div>
    <div class="card-body">
        <div class="kanban-wrap" id="resep-kanban">
            @foreach(['DITERIMA','DIRACIK','DIVERIFIKASI','DISERAHKAN'] as $tahap)
                <div class="kanban-column" data-tahap="{{ $tahap }}">
                    <div class="kanban-col-head">
                        <span class="badge bg-{{ $tahapBadge[$tahap] ?? 'secondary' }}">{{ $tahapLabels[$tahap] ?? $tahap }}</span>
                        <span class="text-muted small" data-col-count="{{ $tahap }}">{{ $groupedResep->get($tahap, collect())->count() }}</span>
                    </div>
                    <div class="kanban-list" data-list="{{ $tahap }}">
                        @foreach($groupedResep->get($tahap, collect()) as $item)
                            <div class="kanban-card"
                                 data-id="{{ $item->id }}"
                                 data-stage="{{ $item->tahap_antrian }}"
                                 data-status="{{ $item->status }}"
                                 draggable="{{ in_array($item->status, ['SELESAI','BATAL']) ? 'false' : 'true' }}">
                                <div class="title">{{ $item->nomor_resep }}</div>
                                <div class="meta">{{ $item->pelanggan->nama ?? '-' }} | {{ $item->dokter->nama ?? '-' }}</div>
                                <div class="meta">Status: {{ $item->status }}</div>
                                <div class="meta">{{ \Carbon\Carbon::parse($item->tanggal_resep)->format('d/m/Y') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-3">
                <label class="form-label">Tahap Antrian</label>
                <select name="tahap_antrian" class="form-select">
                    <option value="">Semua Tahap</option>
                    @foreach(['DITERIMA','DIRACIK','DIVERIFIKASI','DISERAHKAN'] as $tahap)
                        <option value="{{ $tahap }}" @selected(request('tahap_antrian') == $tahap)>{{ $tahap }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['PENDING','SEBAGIAN','SELESAI','BATAL'] as $status)
                        <option value="{{ $status }}" @selected(request('status') == $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end gap-2">
                <button class="btn btn-primary w-100" type="submit"><i class="fa-solid fa-filter"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Tahap</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resep as $item)
                        @php
                            $nextTahap = $item->nextTahap();
                        @endphp
                        <tr>
                            <td>{{ $item->nomor_resep }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_resep)->format('d/m/Y') }}</td>
                            <td>{{ $item->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $item->dokter->nama ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $tahapBadge[$item->tahap_antrian] ?? 'secondary' }}">
                                    {{ $item->tahap_antrian ?? 'DITERIMA' }}
                                </span>
                            </td>
                            <td>{{ $item->status }}</td>
                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a href="{{ route('resep.show', $item) }}" class="btn btn-sm btn-action-view" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                @if($nextTahap && !in_array($item->status, ['SELESAI','BATAL']))
                                    <form action="{{ route('resep.tahap.update', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="tahap_antrian" value="{{ $nextTahap }}">
                                        <button type="submit" class="btn btn-sm btn-action-process" title="Lanjut ke {{ $nextTahap }}">
                                            <i class="fa-solid fa-forward-step"></i>
                                        </button>
                                    </form>
                                @endif

                                @if(in_array($item->tahap_antrian, ['DIVERIFIKASI','DISERAHKAN']) && $item->status !== 'SELESAI')
                                    <a href="{{ route('resep.penjualan.create', $item) }}" class="btn btn-sm btn-success" title="Buat Penjualan">
                                        <i class="fa-solid fa-cash-register"></i>
                                    </a>
                                @endif

                                <form id="delete-form-{{ $item->id }}" action="{{ route('resep.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-action-delete" onclick="confirmDelete('delete-form-{{ $item->id }}')" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const columns = document.querySelectorAll('.kanban-column');
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const updateUrlTemplate = @json(route('resep.tahap.update', ['resep' => '__ID__']));
        let draggingCard = null;

        const tahapUrut = ['DITERIMA', 'DIRACIK', 'DIVERIFIKASI', 'DISERAHKAN'];
        const getNextTahap = (stage) => {
            const idx = tahapUrut.indexOf(stage);
            return idx >= 0 ? (tahapUrut[idx + 1] || null) : null;
        };

        const updateCounts = () => {
            tahapUrut.forEach((stage) => {
                const list = document.querySelector(`.kanban-list[data-list="${stage}"]`);
                const count = list ? list.querySelectorAll('.kanban-card').length : 0;
                const colCount = document.querySelector(`[data-col-count="${stage}"]`);
                const summaryCount = document.querySelector(`[data-summary-count="${stage}"]`);
                if (colCount) colCount.textContent = String(count);
                if (summaryCount) summaryCount.textContent = String(count);
            });
        };

        const bindCardEvents = (card) => {
            if (!card || card.dataset.status === 'SELESAI' || card.dataset.status === 'BATAL') {
                if (card) card.setAttribute('draggable', 'false');
                return;
            }

            card.setAttribute('draggable', 'true');

            card.addEventListener('dragstart', (event) => {
                draggingCard = card;
                card.classList.add('dragging');
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', card.dataset.id);
            });

            card.addEventListener('dragend', () => {
                card.classList.remove('dragging');
                draggingCard = null;
            });
        };

        const moveCard = async (card, targetStage) => {
            const currentStage = card.dataset.stage;
            const nextAllowed = getNextTahap(currentStage);

            if (!nextAllowed || nextAllowed !== targetStage) {
                window.showToast('warning', 'Perpindahan tahap harus berurutan.');
                return;
            }

            const url = updateUrlTemplate.replace('__ID__', card.dataset.id);

            try {
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({ tahap_antrian: targetStage }),
                });

                const result = await response.json();
                if (!response.ok || !result.success) {
                    window.showToast('error', result.message || 'Gagal memindahkan tahap.');
                    return;
                }

                const targetList = document.querySelector(`.kanban-list[data-list="${targetStage}"]`);
                if (!targetList) return;

                card.dataset.stage = targetStage;
                if (result.data?.status) {
                    card.dataset.status = result.data.status;
                    card.querySelectorAll('.meta').forEach((metaEl) => {
                        if (metaEl.textContent.startsWith('Status:')) {
                            metaEl.textContent = `Status: ${result.data.status}`;
                        }
                    });
                }

                targetList.appendChild(card);
                bindCardEvents(card);
                updateCounts();
                window.showToast('success', result.message || 'Tahap berhasil diperbarui.');
            } catch (_) {
                window.showToast('error', 'Terjadi kesalahan saat sinkronisasi tahap.');
            }
        };

        columns.forEach((column) => {
            column.addEventListener('dragover', (event) => {
                event.preventDefault();
                column.classList.add('is-over');
            });

            column.addEventListener('dragleave', () => {
                column.classList.remove('is-over');
            });

            column.addEventListener('drop', async (event) => {
                event.preventDefault();
                column.classList.remove('is-over');
                const targetStage = column.dataset.tahap;
                if (!draggingCard || !targetStage) return;
                await moveCard(draggingCard, targetStage);
            });
        });

        document.querySelectorAll('.kanban-card').forEach(bindCardEvents);
        updateCounts();
    })();
</script>
@endpush