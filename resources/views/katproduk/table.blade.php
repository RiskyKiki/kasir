<table id="myTable" class="display table table-hover" style="width: 100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($katproduks as $index => $katproduk)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $katproduk->kode ?? '-' }}</td>
            <td>{{ $katproduk->nama ?? '-'}}</td>
            <td>
                <div class="btn-group">
                    <button class="btn btn-info btn-sm" onclick="show({{ $katproduk->id }})"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-warning btn-sm" onclick="edit({{ $katproduk->id }})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $katproduk->id }}, '{{ route('kategori.destroy', $katproduk->id) }}')"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>
        @empty
        <tr> 
            <td colspan="4" style="text-align: center;"><small>Data Tidak ditemukan</small></td> 
        </tr> 
        @endforelse
    </tbody>
</table>