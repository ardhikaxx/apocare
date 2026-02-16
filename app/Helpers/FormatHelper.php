<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($value)
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}

if (!function_exists('formatAngka')) {
    function formatAngka($value)
    {
        return number_format($value, 0, ',', '.');
    }
}

if (!function_exists('formatDesimal')) {
    function formatDesimal($value)
    {
        return number_format($value, 2, ',', '.');
    }
}
