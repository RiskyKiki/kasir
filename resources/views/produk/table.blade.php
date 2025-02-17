<table id="myTable" class="display table table-hover" style="width: 100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>HPP</th>
            <th>Harga 1</th>
            <th>Harga 2</th>
            <th>Harga 3</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($produks as $index => $produk)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $produk->kode ?? '-' }}</td>
            <td>{{ $produk->nama ?? '-' }}</td>
            <td>{{ $produk->kategori->nama ?? '-' }}</td>
            <td>{{ $produk->stok ?? '-' }}</td>
            <td>Rp{{ number_format($produk->hpp, 2, ',', '.') }}</td>
            <td>Rp{{ number_format($produk->harga1, 2, ',', '.') }}</td>
            <td>Rp{{ number_format($produk->harga2, 2, ',', '.') }}</td>
            <td>Rp{{ number_format($produk->harga3, 2, ',', '.') }}</td>
            <td>
                <div class="btn-group">
                    <button class="btn btn-info btn-sm" onclick="show({{ $produk->id }})"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-warning btn-sm" onclick="edit({{ $produk->id }})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $produk->id }}, '{{ route('produk.destroy', $produk->id) }}')"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>
        @empty
        <tr> 
            <td colspan="10" style="text-align: center;"><small>Data Tidak ditemukan</small></td> 
        </tr> 
        @endforelse
    </tbody>
</table>
