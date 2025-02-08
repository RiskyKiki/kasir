<div class="sidebar">
    <ul>
        @auth
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            @if(Auth::user()->role == 'admin')
                <li><a href="{{ route('users.index') }}">Table User</a></li>
                <li><a href="{{ route('barang.index') }}">Table Barang</a></li>
                <li><a href="{{ route('pelanggan.index') }}">Table Pelanggan</a></li>
            @endif

            <li><a href="{{ route('transaksi.index') }}">Table Transaksi</a></li>

            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </li>
        @endauth
    </ul>
</div>
