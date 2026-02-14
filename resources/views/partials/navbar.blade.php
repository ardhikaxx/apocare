<nav class="app-navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-soft" data-sidebar-toggle>
            <i class="fa-solid fa-bars"></i>
        </button>
        <div>
            <div class="fw-semibold">Selamat datang kembali</div>
            <small class="text-muted">{{ now()->format('d M Y') }}</small>
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="d-none d-md-flex align-items-center gap-2">
            <span class="data-pill"><i class="fa-solid fa-circle-info"></i> Shift Pagi</span>
            <span class="data-pill"><i class="fa-solid fa-signal"></i> Online</span>
        </div>
        <div class="dropdown">
            <button class="btn btn-soft dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa-solid fa-user-gear"></i> {{ auth()->user()?->nama ?? 'Pengguna' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user me-2"></i>Profil</a></li>
                <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i>Pengaturan</a></li>
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
</nav>
