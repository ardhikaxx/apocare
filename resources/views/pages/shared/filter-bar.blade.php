@php
    $filters = $filters ?? [];
@endphp
<div class="filter-bar mb-4">
    <div class="flex-grow-1">
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="fa-solid fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Cari data...">
        </div>
    </div>
    @foreach($filters as $filter)
        <select class="form-select" style="max-width: 200px;">
            <option>{{ $filter }}</option>
            <option>Semua</option>
            <option>Aktif</option>
            <option>Nonaktif</option>
        </select>
    @endforeach
    <button class="btn btn-soft"><i class="fa-solid fa-filter me-1"></i>Filter</button>
</div>
