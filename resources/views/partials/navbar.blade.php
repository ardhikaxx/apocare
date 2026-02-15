<nav class="app-navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-soft" data-sidebar-toggle>
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="navbar-greeting">
            <div class="fw-semibold">Selamat datang kembali</div>
            <small class="text-muted">{{ now()->format('d M Y') }}</small>
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="dropdown">
            <button class="btn btn-soft dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa-solid fa-user-gear"></i><span class="navbar-username">{{ auth()->user()?->nama ?? 'Pengguna' }}</span>
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
</nav>
