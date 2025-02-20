<table id="myTable" class="display table table-hover" style="width: 100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori Barang</th>
            <th>Sisa Stok</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($produks as $index => $produk)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $produk->kode }}</td>
                <td>{{ $produk->nama }}</td>
                <td>{{ $produk->kategori->nama ?? '-' }}</td>
                <td>{{ $produk->stok }}</td>
                <td>
                    @if ($produk->stok == 0)
                        <span class="badge badge-danger">Habis</span>
                    @elseif($produk->stok <= $produk->min_stok)
                        <span class="badge badge-warning">Menipis</span>
                    @else
                        <span class="badge badge-success">Masih</span>
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align: center;"><small>Data Tidak ditemukan</small></td>
            </tr>
        @endforelse
    </tbody>
</table>
