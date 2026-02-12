<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-0">
        <button class="btn btn-link text-dark sidebar-toggle d-md-none">
            <i class="fas fa-bars"></i>
        </button>
        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
                <a class="btn btn-light dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-2"></i>{{ auth()->user()->nama }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
