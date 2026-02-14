@php
    $logoPath = public_path('assets/brand/apocare-logo.png');
    $logoExists = file_exists($logoPath);
@endphp
<div class="kop">
    @if ($logoExists)
        <div class="kop-logo">
            <img src="{{ $logoPath }}" alt="Apocare Logo">
        </div>
    @endif
    <div class="kop-text">
        <div class="kop-title">APOCARE</div>
        <div class="kop-subtitle">Integrated Pharmacy Management System</div>
    </div>
</div>
<div class="kop-line"></div>
