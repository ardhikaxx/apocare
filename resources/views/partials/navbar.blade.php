<nav class="app-navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-soft" data-sidebar-toggle>
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="navbar-greeting d-none d-md-block">
            <div class="fw-semibold">Selamat datang kembali</div>
            <small class="text-muted">{{ now()->format('d M Y') }}</small>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-soft d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch" aria-expanded="false">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        <div class="smart-search-wrapper position-relative d-none d-lg-block">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0 ps-0" id="smartSearchInput" 
                    placeholder="Cari produk, pelanggan, resep..." autocomplete="off">
            </div>
            <div class="smart-search-dropdown" id="smartSearchResults"></div>
        </div>
        <div class="dropdown">
            <button class="btn btn-soft dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa-solid fa-user-gear"></i><span class="navbar-username d-none d-md-inline">{{ auth()->user()?->nama ?? 'Pengguna' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profil.edit') }}"><i class="fa-solid fa-user me-2"></i>Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
    <div class="collapse d-lg-none" id="mobileSearch">
        <div class="pt-2 pb-2">
            <div class="smart-search-wrapper position-relative">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" id="mobileSearchInput" 
                        placeholder="Cari produk, pelanggan, resep..." autocomplete="off">
                </div>
                <div class="smart-search-dropdown" id="mobileSearchResults"></div>
            </div>
        </div>
    </div>
</nav>

<style>
.smart-search-wrapper {
    width: 300px;
}
.smart-search-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    max-height: 400px;
    overflow-y: auto;
    z-index: 1050;
    display: none;
}
.smart-search-dropdown.show {
    display: block;
}
.smart-search-category {
    padding: 0.5rem 1rem;
    background: #f8f9fa;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    color: #6c757d;
    border-bottom: 1px solid #dee2e6;
}
.smart-search-item {
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    color: inherit;
    border-bottom: 1px solid #f1f3f5;
    transition: background 0.15s;
}
.smart-search-item:hover {
    background: #f8f9fa;
    text-decoration: none;
    color: inherit;
}
.smart-search-item:last-child {
    border-bottom: none;
}
.smart-search-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}
.smart-search-icon.produk { background: #e7f5ff; color: #1971c2; }
.smart-search-icon.pelanggan { background: #e5f3ef; color: #0ca678; }
.smart-search-icon.dokter { background: #fff3bf; color: #f08c00; }
.smart-search-icon.karyawan { background: #e7e5ff; color: #5c7cfa; }
.smart-search-icon.resep { background: #ffe3e3; color: #e03131; }
.smart-search-icon.pemasok { background: #fff5f5; color: #c92a2a; }
.smart-search-icon.penjualan { background: #e7f5ff; color: #1971c2; }
.smart-search-icon.pembelian { background: #fff9db; color: #5c940d; }
.smart-search-info { flex: 1; min-width: 0; }
.smart-search-title {
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.smart-search-subtitle {
    font-size: 0.75rem;
    color: #868e96;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.smart-search-arrow {
    color: #adb5bd;
    font-size: 0.75rem;
}
.smart-search-empty {
    padding: 2rem 1rem;
    text-align: center;
    color: #868e96;
}
</style>

<script>
(function() {
    const searchInput = document.getElementById('smartSearchInput');
    const resultsContainer = document.getElementById('smartSearchResults');
    const mobileSearchInput = document.getElementById('mobileSearchInput');
    const mobileResultsContainer = document.getElementById('mobileSearchResults');
    let debounceTimer;

    const categoryIcons = {
        produk: 'fa-pills',
        pelanggan: 'fa-users',
        dokter: 'fa-user-doctor',
        karyawan: 'fa-user-tie',
        resep: 'fa-file-prescription',
        pemasok: 'fa-truck-field',
        penjualan: 'fa-cash-register',
        pembelian: 'fa-basket-shopping'
    };

    const categoryLabels = {
        produk: 'Produk',
        pelanggan: 'Pelanggan',
        dokter: 'Dokter',
        karyawan: 'Karyawan',
        resep: 'Resep',
        pemasok: 'Pemasok',
        penjualan: 'Penjualan',
        pembelian: 'Pembelian'
    };

    function showLoading(resultsEl) {
        resultsEl.innerHTML = '<div class="smart-search-empty"><i class="fa-solid fa-circle-notch fa-spin"></i> Mencari...</div>';
        resultsEl.classList.add('show');
    }

    function showEmpty(resultsEl) {
        resultsEl.innerHTML = '<div class="smart-search-empty">Tidak ada hasil ditemukan</div>';
        resultsEl.classList.add('show');
    }

    function renderResults(data, resultsEl) {
        if (Object.keys(data).length === 0) {
            showEmpty(resultsEl);
            return;
        }

        let html = '';
        for (const [category, items] of Object.entries(data)) {
            if (items.length === 0) continue;
            
            html += `<div class="smart-search-category">${categoryLabels[category] || category}</div>`;
            
            items.forEach(item => {
                const title = item.nama || item.nama_pasien || item.kode || item.invoice || '';
                const subtitle = item.harga ? `Rp ${parseInt(item.harga).toLocaleString('id-ID')}` 
                    : item.stok !== undefined ? `Stok: ${item.stok}`
                    : item.no_telp || item.no_sip || item.jabatan || item.status || item.kode || '';
                
                html += `
                    <a href="${item.url}" class="smart-search-item">
                        <div class="smart-search-icon ${category}">
                            <i class="fa-solid ${categoryIcons[category]}"></i>
                        </div>
                        <div class="smart-search-info">
                            <div class="smart-search-title">${title}</div>
                            <div class="smart-search-subtitle">${subtitle}</div>
                        </div>
                        <i class="fa-solid fa-chevron-right smart-search-arrow"></i>
                    </a>
                `;
            });
        }

        resultsEl.innerHTML = html;
        resultsEl.classList.add('show');
    }

    function performSearch(query, inputEl, resultsEl) {
        clearTimeout(debounceTimer);
        
        if (query.length < 2) {
            resultsEl.classList.remove('show');
            return;
        }

        debounceTimer = setTimeout(function() {
            showLoading(resultsEl);
            
            fetch(`{{ route('app.search') }}?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                renderResults(data, resultsEl);
            })
            .catch(() => {
                resultsEl.innerHTML = '<div class="smart-search-empty">Terjadi kesalahan</div>';
                resultsEl.classList.add('show');
            });
        }, 300);
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            performSearch(this.value.trim(), searchInput, resultsContainer);
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                resultsContainer.classList.add('show');
            }
        });
    }

    if (mobileSearchInput) {
        mobileSearchInput.addEventListener('input', function() {
            performSearch(this.value.trim(), mobileSearchInput, mobileResultsContainer);
        });

        mobileSearchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                mobileResultsContainer.classList.add('show');
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (searchInput && !searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.classList.remove('show');
        }
        if (mobileSearchInput && !mobileSearchInput.contains(e.target) && !mobileResultsContainer.contains(e.target)) {
            mobileResultsContainer.classList.remove('show');
        }
    });

    const allInputs = [searchInput, mobileSearchInput].filter(Boolean);
    allInputs.forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (this === searchInput) {
                    resultsContainer.classList.remove('show');
                } else {
                    mobileResultsContainer.classList.remove('show');
                }
                this.blur();
            }
        });
    });
})();
</script>
