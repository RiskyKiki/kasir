@auth
    <nav class="navbar navbar-expand-lg main-navbar">
        <div class="d-flex align-items-center">
            <!-- Tombol Sidebar atau elemen lainnya -->
            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                <i class="fas fa-bars"></i>
            </a>

            <!-- Tombol Kasir di sebelah kiri -->
            <a class="btn btn-success mr-3" style="box-shadow: none" href="{{ route('kasir.index') }}">
                <i class="fas fa-cart-plus"></i>
            </a>
        </div>

        <!-- Bagian kanan navbar -->    
        <ul class="navbar-nav ml-auto">
            <li class="dropdown nav-item">
                <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                    <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->username }}</div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- Tombol untuk membuka modal logout -->
                    <button type="button" class="dropdown-item text-danger" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endauth
