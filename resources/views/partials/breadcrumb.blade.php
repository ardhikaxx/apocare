@php
    $breadcrumbs = $breadcrumbs ?? [];
    $showDashboard = !(count($breadcrumbs) === 1 && isset($breadcrumbs[0]['label']) && $breadcrumbs[0]['label'] === 'Dashboard');
@endphp
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        @if($showDashboard)
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        @endif
        @foreach($breadcrumbs as $crumb)
            @if(isset($crumb['url']))
                <li class="breadcrumb-item"><a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a></li>
            @else
                <li class="breadcrumb-item active" aria-current="page">{{ $crumb['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
