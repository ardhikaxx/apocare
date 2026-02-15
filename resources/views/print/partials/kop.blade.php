@php
    $logoPath = public_path('assets/brand/apocare-logo.png');
    $logoExists = file_exists($logoPath);
@endphp
<table style="width: 100%; border: none; margin-bottom: 8px;">
    <tr>
        @if ($logoExists)
        <td style="width: 70px; vertical-align: middle;">
            <img src="{{ $logoPath }}" alt="Apocare Logo" style="height: 65px; width: auto;">
        </td>
        <td style="vertical-align: middle; padding-left: 16px;">
        @else
        <td style="vertical-align: middle;">
        @endif
            <div style="font-size: 20px; font-weight: 700; letter-spacing: 1px;">APOCARE</div>
            <div style="font-size: 12px; color: #444;">Integrated Pharmacy Management System</div>
        </td>
    </tr>
</table>
<div style="border-bottom: 1px solid #222; margin-bottom: 16px;"></div>
