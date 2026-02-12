<div class="sidebar">
    <div class="text-center py-4 border-bottom border-secondary">
        <h4 class="text-white mb-0"><i class="fas fa-pills me-2"></i>APOCARE</h4>
        <small class="text-white-50">Sistem Informasi Apotek</small>
    </div>
    <ul class="nav flex-column mt-3">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('master.*') ? 'active' : '' }}" href="#masterMenu" data-bs-toggle="collapse">
                <i class="fas fa-box"></i> Data Master
            </a>
            <div class="collapse {{ request()->is('master/*') ? 'show' : '' }}" id="masterMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('kategori.index') }}"><i class="fas fa-tags"></i> Kategori</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('satuan.index') }}"><i class="fas fa-balance-scale"></i> Satuan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('produk.index') }}"><i class="fas fa-pills"></i> Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pemasok.index') }}"><i class="fas fa-truck"></i> Pemasok</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pelanggan*') ? 'active' : '' }}" href="{{ route('pelanggan.index') }}">
                <i class="fas fa-users"></i> Pelanggan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dokter*') ? 'active' : '' }}" href="{{ route('dokter.index') }}">
                <i class="fas fa-user-md"></i> Dokter
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}" href="#transaksiMenu" data-bs-toggle="collapse">
                <i class="fas fa-shopping-cart"></i> Transaksi
            </a>
            <div class="collapse {{ request()->is('transaksi/*') ? 'show' : '' }}" id="transaksiMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('penjualan.index') }}"><i class="fas fa-cash-register"></i> Penjualan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pembelian.index') }}"><i class="fas fa-shopping-bag"></i> Pembelian</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('resep.*') ? 'active' : '' }}" href="{{ route('resep.index') }}">
                <i class="fas fa-file-prescription"></i> Resep
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('stok*') ? 'active' : '' }}" href="{{ route('stok.index') }}">
                <i class="fas fa-boxes"></i> Stok
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="#laporanMenu" data-bs-toggle="collapse">
                <i class="fas fa-chart-bar"></i> Laporan
            </a>
            <div class="collapse {{ request()->is('laporan/*') ? 'show' : '' }}" id="laporanMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('laporan.penjualan') }}"><i class="fas fa-chart-line"></i> Penjualan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('laporan.produk') }}"><i class="fas fa-chart-pie"></i> Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('laporan.stok') }}"><i class="fas fa-box"></i> Stok</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('pengguna*') ? 'active' : '' }}" href="{{ route('pengguna.index') }}">
                <i class="fas fa-user-cog"></i> Pengguna
            </a>
        </li>
    </ul>
</div>
