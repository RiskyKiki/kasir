@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="text-right mb-3">
                    <a href="{{ route('produk.create') }}" class="btn btn-primary">
                        Tambah Produk
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped w-100" id="produk-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga Jual</th>
                                <th>Stock</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_script')
    <script>
        $(function () {
            $('#produk-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'kode_produk', name: 'kode_produk' },
                    { data: 'nama_produk', name: 'nama_produk' },
                    { data: 'kategori.nama_kategori', name: 'kategori.nama_kategori' },
                    { data: 'harga_jual', name: 'harga_jual', render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
                    { data: 'stock', name: 'stock' },
                    {
                        data: 'id',
                        name: '_',
                        orderable: false,
                        searchable: false,
                        class: 'text-right nowrap',
                        render: function (data, type, row) {
                            return `
                                <a href="/produk/${data}" class="btn btn-info btn-icon mr-2" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/produk/${data}/edit" class="btn btn-warning btn-icon mr-2" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="destroy(${data})" class="btn btn-danger btn-icon mr-2" title="Hapus">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            `;
                        }
                    }
                ]
            });
        });

        function destroy(id) {
            if (confirm('Yakin ingin menghapus?')) {
                $.ajax({
                    url: '/produk/' + id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        alert('Produk berhasil dihapus!');
                        $('#produk-table').DataTable().ajax.reload();
                    },
                    error: function () {
                        alert('Gagal menghapus produk!');
                    }
                });
            }
        }
    </script>
@endpush
