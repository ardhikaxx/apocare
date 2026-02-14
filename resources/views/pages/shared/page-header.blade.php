@php
    $actions = $actions ?? [];
@endphp
<div class="page-header">
    <div>
        <h1>{{ $title ?? 'Judul Halaman' }}</h1>
        @if(!empty($subtitle))
            <p class="text-muted mb-0">{{ $subtitle }}</p>
        @endif
    </div>
    @if(count($actions))
        <div class="d-flex flex-wrap gap-2">
            @foreach($actions as $action)
                <a href="{{ $action['href'] ?? '#' }}" class="btn {{ $action['class'] ?? 'btn-primary' }}" {!! $action['attrs'] ?? '' !!}>
                    @if(!empty($action['icon']))
                        <i class="{{ $action['icon'] }} me-1"></i>
                    @endif
                    {{ $action['label'] ?? 'Aksi' }}
                </a>
            @endforeach
        </div>
    @endif
</div>
