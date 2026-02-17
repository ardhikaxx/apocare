<aside class="sidebar">
    <div class="sidebar-header">
        <div class="brand brand-logo-only">
            <img src="{{ asset('assets/images/logo-white.png') }}" alt="Apocare" class="brand-logo">
        </div>
        <button class="sidebar-close-btn d-lg-none" data-sidebar-close>
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    <div class="nav-section">Utama</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
        </li>
    </ul>

    <div class="nav-section">Data Master</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link nav-dropdown {{ request()->routeIs('master.*') ? 'active' : '' }}" data-bs-toggle="collapse"
                href="#masterMenu">
                <span class="nav-icon"><i class="fa-solid fa-layer-group"></i></span>
                <span class="nav-text">Data Master</span>
                <span class="nav-arrow"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <div class="collapse {{ request()->routeIs('master.*') ? 'show' : '' }}" id="masterMenu">
                <a class="nav-link sub-nav-link" href="{{ route('master.pemasok.index') }}"><i class="fa-solid fa-truck-field"></i>
                    Pemasok</a>
                <a class="nav-link sub-nav-link" href="{{ route('master.kategori.index') }}"><i class="fa-solid fa-tags"></i>
                    Kategori</a>
                <a class="nav-link sub-nav-link" href="{{ route('master.satuan.index') }}"><i class="fa-solid fa-scale-balanced"></i>
                    Satuan</a>
                <a class="nav-link sub-nav-link" href="{{ route('master.produk.index') }}"><i class="fa-solid fa-capsules"></i>
                    Produk</a>
            </div>
        </li>
    </ul>

    <div class="nav-section">Persediaan</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link nav-dropdown {{ request()->routeIs('persediaan.*') ? 'active' : '' }}" data-bs-toggle="collapse"
                href="#persediaanMenu">
                <span class="nav-icon"><i class="fa-solid fa-boxes-stacked"></i></span>
                <span class="nav-text">Persediaan</span>
                <span class="nav-arrow"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <div class="collapse {{ request()->routeIs('persediaan.*') ? 'show' : '' }}" id="persediaanMenu">
                <a class="nav-link sub-nav-link" href="{{ route('persediaan.stok.index') }}"><i class="fa-solid fa-warehouse"></i>
                    Stok</a>
                <a class="nav-link sub-nav-link" href="{{ route('persediaan.penyesuaian.index') }}"><i
                        class="fa-solid fa-sliders"></i> Penyesuaian</a>
                <a class="nav-link sub-nav-link" href="{{ route('persediaan.opname.index') }}"><i
                        class="fa-solid fa-clipboard-check"></i> Opname</a>
                <a class="nav-link sub-nav-link" href="{{ route('harga.index') }}"><i
                        class="fa-solid fa-tags"></i> Set Harga</a>
            </div>
        </li>
    </ul>

    <div class="nav-section">Transaksi</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link nav-dropdown {{ request()->routeIs('transaksi.*') ? 'active' : '' }}" data-bs-toggle="collapse"
                href="#transaksiMenu">
                <span class="nav-icon"><i class="fa-solid fa-cart-shopping"></i></span>
                <span class="nav-text">Transaksi</span>
                <span class="nav-arrow"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <div class="collapse {{ request()->routeIs('transaksi.*') ? 'show' : '' }}" id="transaksiMenu">
                <a class="nav-link sub-nav-link" href="{{ route('transaksi.penjualan.index') }}"><i
                        class="fa-solid fa-cash-register"></i> Penjualan</a>
                <a class="nav-link sub-nav-link" href="{{ route('transaksi.pembelian.index') }}"><i
                        class="fa-solid fa-basket-shopping"></i> Pembelian</a>
                <a class="nav-link sub-nav-link" href="{{ route('transaksi.retur.index') }}"><i class="fa-solid fa-rotate-left"></i>
                    Retur</a>
            </div>
        </li>
    </ul>

    <div class="nav-section">Relasi</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}"
                href="{{ route('pelanggan.index') }}">
                <i class="fa-solid fa-users"></i> Pelanggan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}"
                href="{{ route('karyawan.index') }}">
                <i class="fa-solid fa-user-tie"></i> Karyawan
            </a>
        </li>
    </ul>

    <div class="nav-section">Resep</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('resep') ? 'active' : '' }}" href="{{ route('resep.index') }}">
                <i class="fa-solid fa-file-prescription"></i> Resep
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dokter.*') ? 'active' : '' }}"
                href="{{ route('dokter.index') }}">
                <i class="fa-solid fa-user-doctor"></i> Dokter
            </a>
        </li>
    </ul>

    <div class="nav-section">Laporan</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link nav-dropdown {{ request()->routeIs('laporan.*') ? 'active' : '' }}" data-bs-toggle="collapse"
                href="#laporanMenu">
                <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span>
                <span class="nav-text">Laporan</span>
                <span class="nav-arrow"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <div class="collapse {{ request()->routeIs('laporan.*') ? 'show' : '' }}" id="laporanMenu">
                <a class="nav-link sub-nav-link" href="{{ route('laporan.penjualan') }}"><i class="fa-solid fa-chart-column"></i>
                    Penjualan</a>
                <a class="nav-link sub-nav-link" href="{{ route('laporan.pembelian') }}"><i class="fa-solid fa-chart-area"></i>
                    Pembelian</a>
                <a class="nav-link sub-nav-link" href="{{ route('laporan.persediaan') }}"><i class="fa-solid fa-chart-pie"></i>
                    Persediaan</a>
                <a class="nav-link sub-nav-link" href="{{ route('laporan.keuangan') }}"><i class="fa-solid fa-wallet"></i>
                    Keuangan</a>
                <a class="nav-link sub-nav-link" href="{{ route('laporan.pelanggan') }}"><i class="fa-solid fa-user-group"></i>
                    Pelanggan</a>
            </div>
        </li>
    </ul>

    <div class="nav-section">Pengaturan</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link nav-dropdown {{ request()->routeIs('pengguna*') ? 'active' : '' }}" data-bs-toggle="collapse"
                href="#penggunaMenu">
                <span class="nav-icon"><i class="fa-solid fa-user-shield"></i></span>
                <span class="nav-text">Pengaturan</span>
                <span class="nav-arrow"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <div class="collapse {{ request()->routeIs('pengguna*') ? 'show' : '' }}" id="penggunaMenu">
                <a class="nav-link sub-nav-link" href="{{ route('pengguna.index') }}"><i class="fa-solid fa-user"></i>
                    Pengguna</a>
                <a class="nav-link sub-nav-link" href="{{ route('pengguna.peran.index') }}"><i class="fa-solid fa-id-badge"></i>
                    Peran</a>
                <a class="nav-link sub-nav-link" href="{{ route('pengguna.hak-akses.index') }}"><i class="fa-solid fa-key"></i>
                    Hak Akses</a>
                <a class="nav-link sub-nav-link" href="{{ route('audit.index') }}"><i class="fa-solid fa-clock-rotate-left"></i>
                    Audit Trail</a>
                <a class="nav-link sub-nav-link" href="{{ route('backup.index') }}"><i class="fa-solid fa-database"></i>
                    Backup DB</a>
                <a class="nav-link sub-nav-link" href="{{ route('session.index') }}"><i class="fa-solid fa-users"></i>
                    Kelola Sesi</a>
            </div>
        </li>
    </ul>
</aside>
