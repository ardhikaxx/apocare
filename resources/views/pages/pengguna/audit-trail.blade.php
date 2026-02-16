@extends('layouts.app')

@section('title', 'Audit Trail')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="page-title">Audit Trail</h1>
        <p class="text-muted mb-0">Jejak perubahan data before/after beserta pengguna yang melakukan aksi.</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Event</label>
                <select name="event" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['created','updated','deleted','restored'] as $event)
                        <option value="{{ $event }}" @selected(request('event') === $event)>{{ strtoupper($event) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Model</label>
                <input type="text" name="model" class="form-control" placeholder="Contoh: Resep" value="{{ request('model') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-2"></i>Filter</button>
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
                        <th>Waktu</th>
                        <th>Event</th>
                        <th>Model</th>
                        <th>ID Data</th>
                        <th>Pengguna</th>
                        <th>Field Berubah</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($auditLogs as $log)
                        <tr>
                            <td>{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <span class="badge bg-{{ $log->event === 'created' ? 'success' : ($log->event === 'updated' ? 'warning text-dark' : ($log->event === 'deleted' ? 'danger' : 'info')) }}">
                                    {{ strtoupper($log->event) }}
                                </span>
                            </td>
                            <td>{{ class_basename($log->auditable_type) }}</td>
                            <td>{{ $log->auditable_id }}</td>
                            <td>{{ $log->actor->nama ?? '-' }}</td>
                            <td>
                                <small>{{ is_array($log->changed_fields) ? implode(', ', $log->changed_fields) : '-' }}</small>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-primary btn-view-audit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#auditDetailModal"
                                    data-event="{{ $log->event }}"
                                    data-model="{{ class_basename($log->auditable_type) }}"
                                    data-id="{{ $log->auditable_id }}"
                                    data-actor="{{ $log->actor->nama ?? '-' }}"
                                    data-fields='{{ json_encode($log->changed_fields ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) }}'
                                    data-before='{{ json_encode($log->before_data ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) }}'
                                    data-after='{{ json_encode($log->after_data ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) }}'>
                                    Lihat
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="auditDetailModal" tabindex="-1" aria-labelledby="auditDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="auditDetailLabel">Detail Audit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-3"><strong>Event:</strong> <span id="modal-event"></span></div>
                    <div class="col-md-3"><strong>Model:</strong> <span id="modal-model"></span></div>
                    <div class="col-md-3"><strong>ID:</strong> <span id="modal-id"></span></div>
                    <div class="col-md-3"><strong>Pengguna:</strong> <span id="modal-actor"></span></div>
                </div>
                <div class="mb-3">
                    <strong>Fields Changed:</strong>
                    <ul class="list-group list-group-flush" id="modal-fields"></ul>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Before</strong>
                        <pre class="p-2 bg-light" id="modal-before" style="max-height: 360px; overflow:auto;"></pre>
                    </div>
                    <div class="col-md-6">
                        <strong>After</strong>
                        <pre class="p-2 bg-light" id="modal-after" style="max-height: 360px; overflow:auto;"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const beforeEl = document.getElementById('modal-before');
        const afterEl = document.getElementById('modal-after');
        const fieldsList = document.getElementById('modal-fields');

        document.querySelectorAll('.btn-view-audit').forEach((btn) => {
            btn.addEventListener('click', () => {
                const before = JSON.parse(btn.dataset.before || '{}');
                const after = JSON.parse(btn.dataset.after || '{}');
                const fields = JSON.parse(btn.dataset.fields || '[]');

                document.getElementById('modal-event').textContent = (btn.dataset.event || '-').toUpperCase();
                document.getElementById('modal-model').textContent = btn.dataset.model || '-';
                document.getElementById('modal-id').textContent = btn.dataset.id || '-';
                document.getElementById('modal-actor').textContent = btn.dataset.actor || '-';

                fieldsList.innerHTML = '';
                if (Array.isArray(fields) && fields.length) {
                    fields.forEach((field) => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item py-1';
                        li.textContent = field;
                        fieldsList.appendChild(li);
                    });
                } else {
                    fieldsList.innerHTML = '<li class="list-group-item py-1 text-muted">Tidak ada field khusus</li>';
                }

                beforeEl.textContent = JSON.stringify(before, null, 2) || '{}';
                afterEl.textContent = JSON.stringify(after, null, 2) || '{}';
            });
        });
    })();
</script>
@endpush