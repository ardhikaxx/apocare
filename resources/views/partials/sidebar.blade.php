@php
    $role = strtolower(auth()->user()->role->nama ?? '');

    $isAdmin = $role === 'admin';
    $isApoteker = $role === 'apoteker';
    $isKasir = $role === 'kasir';
    $isGudang = $role === 'gudang';

    $canMaster = $isAdmin || $isApoteker || $isGudang;
    $canPersediaan = $isAdmin || $isApoteker || $isGudang;
    $canPenjualan = $isAdmin || $isApoteker || $isKasir;
    $canPembelian = $isAdmin || $isGudang;
    $canRetur = $isAdmin || $isApoteker || $isKasir || $isGudang;
    $canPelanggan = $isAdmin || $isApoteker || $isKasir;
    $canKaryawan = $isAdmin;
    $canResep = $isAdmin || $isApoteker || $isKasir;
    $canDokter = $isAdmin || $isApoteker;

    $canLaporanPenjualan = $isAdmin || $isApoteker || $isKasir;
    $canLaporanPembelian = $isAdmin || $isGudang;
    $canLaporanPersediaan = $isAdmin || $isApoteker || $isGudang;
    $canLaporanKeuangan = $isAdmin;
    $canLaporanPelanggan = $isAdmin || $isApoteker || $isKasir;
    $canLaporan = $canLaporanPenjualan || $canLaporanPembelian || $canLaporanPersediaan || $canLaporanKeuangan || $canLaporanPelanggan;
@endphp

<aside class="sidebar">
    <div class="brand brand-logo-only">
        <img src="{{ asset('assets/images/logo-white.png') }}" alt="Apocare" class="brand-logo">
    </div>

    <div class="nav-section">Utama</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
        </li>
    </ul>

    @if ($canMaster)
        <div class="nav-section">Data Master</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('master.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#masterMenu">
                    <i class="fa-solid fa-layer-group"></i> Data Master
                </a>
                <div class="collapse {{ request()->routeIs('master.*') ? 'show' : '' }}" id="masterMenu">
                    <a class="nav-link" href="{{ route('master.pemasok.index') }}"><i class="fa-solid fa-truck-field"></i> Pemasok</a>
                    <a class="nav-link" href="{{ route('master.kategori.index') }}"><i class="fa-solid fa-tags"></i> Kategori</a>
                    <a class="nav-link" href="{{ route('master.satuan.index') }}"><i class="fa-solid fa-scale-balanced"></i> Satuan</a>
                    <a class="nav-link" href="{{ route('master.produk.index') }}"><i class="fa-solid fa-capsules"></i> Produk</a>
                </div>
            </li>
        </ul>
    @endif

    @if ($canPersediaan)
        <div class="nav-section">Persediaan</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('persediaan.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#persediaanMenu">
                    <i class="fa-solid fa-boxes-stacked"></i> Persediaan
                </a>
                <div class="collapse {{ request()->routeIs('persediaan.*') ? 'show' : '' }}" id="persediaanMenu">
                    <a class="nav-link" href="{{ route('persediaan.stok.index') }}"><i class="fa-solid fa-warehouse"></i> Stok</a>
                    <a class="nav-link" href="{{ route('persediaan.penyesuaian.index') }}"><i class="fa-solid fa-sliders"></i> Penyesuaian</a>
                    <a class="nav-link" href="{{ route('persediaan.opname.index') }}"><i class="fa-solid fa-clipboard-check"></i> Opname</a>
                </div>
            </li>
        </ul>
    @endif

    @if ($canPenjualan || $canPembelian || $canRetur)
        <div class="nav-section">Transaksi</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#transaksiMenu">
                    <i class="fa-solid fa-cart-shopping"></i> Transaksi
                </a>
                <div class="collapse {{ request()->routeIs('transaksi.*') ? 'show' : '' }}" id="transaksiMenu">
                    @if ($canPenjualan)
                        <a class="nav-link" href="{{ route('transaksi.penjualan.index') }}"><i class="fa-solid fa-cash-register"></i> Penjualan</a>
                    @endif
                    @if ($canPembelian)
                        <a class="nav-link" href="{{ route('transaksi.pembelian.index') }}"><i class="fa-solid fa-basket-shopping"></i> Pembelian</a>
                    @endif
                    @if ($canRetur)
                        <a class="nav-link" href="{{ route('transaksi.retur.index') }}"><i class="fa-solid fa-rotate-left"></i> Retur</a>
                    @endif
                </div>
            </li>
        </ul>
    @endif

    @if ($canPelanggan || $canKaryawan)
        <div class="nav-section">Relasi</div>
        <ul class="nav flex-column">
            @if ($canPelanggan)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}" href="{{ route('pelanggan.index') }}">
                        <i class="fa-solid fa-users"></i> Pelanggan
                    </a>
                </li>
            @endif
            @if ($canKaryawan)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}" href="{{ route('karyawan.index') }}">
                        <i class="fa-solid fa-user-tie"></i> Karyawan
                    </a>
                </li>
            @endif
        </ul>
    @endif

    @if ($canResep || $canDokter)
        <div class="nav-section">Resep</div>
        <ul class="nav flex-column">
            @if ($canResep)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('resep.*') ? 'active' : '' }}" href="{{ route('resep.index') }}">
                        <i class="fa-solid fa-file-prescription"></i> Resep
                    </a>
                </li>
            @endif
            @if ($canDokter)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dokter.*') ? 'active' : '' }}" href="{{ route('dokter.index') }}">
                        <i class="fa-solid fa-user-doctor"></i> Dokter
                    </a>
                </li>
            @endif
        </ul>
    @endif

    @if ($canLaporan)
        <div class="nav-section">Laporan</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#laporanMenu">
                    <i class="fa-solid fa-chart-line"></i> Laporan
                </a>
                <div class="collapse {{ request()->routeIs('laporan.*') ? 'show' : '' }}" id="laporanMenu">
                    @if ($canLaporanPenjualan)
                        <a class="nav-link" href="{{ route('laporan.penjualan') }}"><i class="fa-solid fa-chart-column"></i> Penjualan</a>
                    @endif
                    @if ($canLaporanPembelian)
                        <a class="nav-link" href="{{ route('laporan.pembelian') }}"><i class="fa-solid fa-chart-area"></i> Pembelian</a>
                    @endif
                    @if ($canLaporanPersediaan)
                        <a class="nav-link" href="{{ route('laporan.persediaan') }}"><i class="fa-solid fa-chart-pie"></i> Persediaan</a>
                    @endif
                    @if ($canLaporanKeuangan)
                        <a class="nav-link" href="{{ route('laporan.keuangan') }}"><i class="fa-solid fa-wallet"></i> Keuangan</a>
                    @endif
                    @if ($canLaporanPelanggan)
                        <a class="nav-link" href="{{ route('laporan.pelanggan') }}"><i class="fa-solid fa-user-group"></i> Pelanggan</a>
                    @endif
                </div>
            </li>
        </ul>
    @endif

    @if ($isAdmin)
        <div class="nav-section">Pengaturan</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pengguna*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#penggunaMenu">
                    <i class="fa-solid fa-user-shield"></i> Pengguna & Peran
                </a>
                <div class="collapse {{ request()->routeIs('pengguna*') ? 'show' : '' }}" id="penggunaMenu">
                    <a class="nav-link" href="{{ route('pengguna.index') }}"><i class="fa-solid fa-user"></i> Pengguna</a>
                    <a class="nav-link" href="{{ route('pengguna.peran.index') }}"><i class="fa-solid fa-id-badge"></i> Peran</a>
                    <a class="nav-link" href="{{ route('pengguna.hak-akses.index') }}"><i class="fa-solid fa-key"></i> Hak Akses</a>
                </div>
            </li>
        </ul>
    @endif
</aside>
