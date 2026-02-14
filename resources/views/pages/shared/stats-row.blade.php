@php
    $stats = $stats ?? [];
@endphp
<div class="row g-3 mb-4">
    @foreach($stats as $stat)
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">{{ $stat['label'] }}</div>
                        <div class="h4 mb-0">{{ $stat['value'] }}</div>
                        @if(!empty($stat['note']))
                            <small class="text-muted">{{ $stat['note'] }}</small>
                        @endif
                    </div>
                    <div class="stat-icon">
                        <i class="{{ $stat['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
