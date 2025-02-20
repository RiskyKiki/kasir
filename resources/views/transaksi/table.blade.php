<table id="myTable" class="display table table-hover" style="width: 100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Pelanggan</th>
            <th>User</th>
            <th>Tanggal transaksi</th>
            <th>Subtotal</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>   
    </thead>
    <tbody>
        @forelse ($transaksis as $index => $transaksi)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $transaksi->pelanggan->nama ?? '-' }}</td>
            <td>{{ $transaksi->user->username ?? '-' }}</td>
            <td>{{ $transaksi->tanggal_transaksi ?? '-' }}</td>
            <td>Rp{{ number_format($transaksi->subtotal, 2, ',', '.')}}</td>
            <td>Rp{{ number_format($transaksi->total, 2, ',', '.')}}</td>
            <td>
                <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i>
                </a>
            </td>
            
        </tr>
        @empty
        <tr> 
            <td colspan="7" style="text-align: center;"><small>Data Tidak ditemukan</small></td> 
        </tr> 
        @endforelse
    </tbody>
</table>