@extends('layouts.app')

@section('title', 'Produk')

@section('subtitle', 'Manajemen Produk')

@section('content')

<div class="section-body">
    <div class="card">
        <div class="text-right mb-3">
            <button class="btn btn-success" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="overflow-x: auto">
                <div id="TableContainer">
                    @include('produk.table')
                </div>
            </div>
        </div>
    </div>
</div>

@push('modals')
    @include('produk.create')
    @include('produk.edit')
    @include('produk.show')
@endpush

@push('scripts')
<script> // Inisialisasi DataTable
    $(document).ready(function() {
        $('#myTable').DataTable({
            "columnDefs": [
                {"orderable": false, "targets": 5},
                {"searchable": false, "targets": 5},
                {"targets": 0, "width": "100px", "className": "dt-center"},
                {"targets": [1, 2, 3, 4, 5], "width": "120px"}
            ],
            "order": [
                [0, 'asc']
            ],
            "responsive": true
        });
    });
</script>

<script>// Reload tabel & DataTable
    function reloadTable() {
        console.log('Memuat ulang tabel produk...');
        $("#TableContainer").load(location.href + " #TableContainer > *", function() {
            console.log('Tabel produk telah diperbarui.');
            $('#myTable').DataTable().destroy();
            $('#myTable').DataTable({
                "columnDefs": [
                    {"orderable": false, "targets": 9},
                    {"searchable": false, "targets": 9},
                    {"targets": 0, "width": "100px", "className": "dt-center"},
                    {"targets": [1, 2, 3, 4, 5, 6, 7, 8, 9], "width": "120px"}
                ],
                "order": [
                    [0, 'asc']
                ],
                "responsive": true
            });
        });
    }
</script>

<script>// Modal Show & Edit Produk
    function show(id) {
        console.log(`Menampilkan produk dengan ID: ${id}`);

        $.get('/produk/' + id, function(response) {
            console.log("Data produk berhasil diambil:", response);
            $('#show_kode').val(response.kode);
            $('#show_nama').val(response.nama);
            $('#show_kategori').val(response.kategori);
            $('#show_hpp').val(response.hpp);
            $('#show_harga1').val(response.harga1);
            $('#show_harga2').val(response.harga2);
            $('#show_harga3').val(response.harga3);
            $('#show_stok').val(response.stok);
            $('#show_min_stok').val(response.min_stok);
            $('#show_tanggal_pembelian').val(response.tanggal_pembelian);
            $('#show_tanggal_kadaluarsa').val(response.tanggal_kadaluarsa);
            $('#show_created_at').val(response.created_at);
            $('#show_created_by').val(response.creator);
            $('#show_updated_at').val(response.updated_at);
            $('#show_updated_by').val(response.updater);
            $('#showModal').modal('show');
        }).fail(function(xhr) {
            console.log("Gagal mengambil data produk:", xhr);
        });
    }

    function edit(id) {
        
        console.log(`Mengedit produk dengan ID: ${id}`);

        $.get('/produk/' + id + '/edit', function(response) {
            console.log("Data produk berhasil diambil:", response);
            const form = $('#editForm');
            form.attr('action', '/produk/' + id);
            $('#edit_kode').val(response.kode);
            $('#edit_nama').val(response.nama);
            $('#edit_kategori').val(response.kategori_id);
            $('#edit_hpp').val(response.hpp);
            $('#edit_harga1').val(response.harga1);
            $('#edit_harga2').val(response.harga2);
            $('#edit_harga3').val(response.harga3);
            $('#edit_stok').val(response.stok);
            $('#edit_min_stok').val(response.min_stok);
            $('#edit_tanggal_pembelian').val(response.tanggal_pembelian);
            $('#edit_tanggal_kadaluarsa').val(response.tanggal_kadaluarsa);
            $('#editModal').modal('show');
        }).fail(function(xhr) {
            console.log("Gagal mengambil data produk:", xhr);
        });
    }
</script>

<script>// Konfirmasi Hapus Produk
    function confirmDelete(id, deleteUrl) {
        console.log(id, deleteUrl);

        swal({
            title: "Apakah Anda yakin?",
            text: "Setelah dihapus, data ini tidak bisa dikembalikan!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            console.log(willDelete);
            if (willDelete) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response);
                        iziToast.success({title: 'Success', message: "Produk berhasil dihapus", position: 'topRight'});
                        reloadTable();
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        swal('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                    }
                });
            }
        });
    }
</script>

<script>// Toast Notifikasi dari Controller
    $(document).ready(function() {
        @if (session('success'))
            iziToast.success({title: 'Success', message: "{{ session('success') }}", position: 'topRight'});
        @endif

        @if (session('error'))
            iziToast.error({title: 'Error', message: "{{ session('error') }}", position: 'topRight'});
        @endif
    });
</script>
@endpush

@endsection
