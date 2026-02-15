<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jersey+10&display=swap" rel="stylesheet">
@php
    $logoPath = public_path('assets/brand/apocare-logo.png');
    $logoExists = file_exists($logoPath);
@endphp
<table style="border: none; margin-bottom: 8px;">
    <tr>
        @if ($logoExists)
        <td style="width: 50px; vertical-align: middle; padding: 0; text-align: left;">
            <img src="{{ $logoPath }}" alt="Apocare Logo" style="height: 50px; width: auto;">
        </td>
        <td style="vertical-align: middle; padding: 0 0 0 5px; text-align: left;">
        @else
        <td style="vertical-align: middle; text-align: left;">
        @endif
            <div style="font-family: 'Jersey 10', sans-serif; font-size: 28px; letter-spacing: 1px;">APOCARE</div>
            <div style="font-size: 11px; color: #444;">Integrated Pharmacy Management System</div>
        </td>
    </tr>
</table>
<div style="border-bottom: 1px solid #222; margin-bottom: 16px;"></div>
