@php
    $columns = $columns ?? [];
    $rows = $rows ?? [];
    $tableId = $tableId ?? null;
    $tableClass = $tableClass ?? '';
    $colCount = count($columns);
@endphp
<div class="table-responsive">
    <table class="table align-middle datatable {{ $tableClass }}" @if($tableId) id="{{ $tableId }}" @endif>
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                @php
                    $cells = array_values($row);
                    if (count($cells) < $colCount) {
                        $cells = array_pad($cells, $colCount, '');
                    } elseif (count($cells) > $colCount) {
                        $cells = array_slice($cells, 0, $colCount);
                    }
                @endphp
                <tr>
                    @foreach($cells as $cell)
                        <td>{!! $cell !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@if(empty($rows))
    <div class="text-center text-muted py-3">Belum ada data untuk ditampilkan.</div>
@endif
