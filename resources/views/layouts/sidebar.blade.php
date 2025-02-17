@auth
<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="">KASIR</a>
            </div>
            <div class="sidebar-brand sidebar-brand-sm">
                <a href="">KR</a>
            </div>

            <ul class="sidebar-menu">
                <li class="menu-header">Dashboard</li>
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
                </li>
                @if (Auth::user()->role == 'admin')
                <li class="menu-header">Khusus Admin</li>
                    <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-user-friends"></i></i><span>User</span></a>
                    </li>
                    <li class="{{ request()->routeIs('pelanggan.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('pelanggan.index')}}"><i class="fas fa-users"></i><span>Pelanggan</span></a>
                    </li>
                    <li class="{{ request()->routeIs('produk.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('produk.index') }}"><i class="fas fa-box"></i><span>Produk</span></a>
                    </li>
                    <li class="{{ request()->routeIs('kategori.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('kategori.index') }}"><i class="fas fa-th"></i><span>Kategori</span></a>
                    </li>                 
                @endif
                <li class="menu-header">Khusus Petugas</li>
                <li class="{{ request()->routeIs('transaksi.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('transaksi.index') }}"><i class="fas fa-table"></i><span>Transaksi</span></a>
                </li>
            </ul>
        </aside>
    </div>
@endauth