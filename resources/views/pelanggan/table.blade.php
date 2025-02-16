<table id="myTable" class="display table table-hover" style="width: 100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Tolepon</th>
            <th>Alamat</th>
            <th>Tipe</th>
            <th>Poin</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($pelanggans as $index => $pelanggan)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $pelanggan->nama ?? '-' }}</td>
            <td>{{ $pelanggan->telepon ?? '-'}}</td>
            <td>{{ $pelanggan->alamat ?? '-'}}</td>
            <td>{{ ucfirst($pelanggan->tipe) ?? '-'}}</td>
            <td>{{ $pelanggan->poin ?? '-' }}</td>
            <td>
                <div class="btn-group">
                    <button class="btn btn-info btn-sm" onclick="show({{ $pelanggan->id }})"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-warning btn-sm" onclick="edit({{ $pelanggan->id }})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $pelanggan->id }}, '{{ route('pelanggan.destroy', $pelanggan->id) }}')"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>
        @empty
        <tr> 
            <td colspan="7" style="text-align: center;"><small>Data Tidak ditemukan</small></td> 
        </tr> 
        @endforelse
    </tbody>
</table>