@extends('layouts.app')

@section('title', 'Category')

@section('subtitle', 'Product Category Management')

@section('content')

<div class="section-body">
    <div class="card">
        <div class="text-right mb-3">
            <button class="btn btn-success" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="overflow-x: auto">
                <div id="TableContainer">
                    @include('katproduk.table')
                </div>
            </div>
        </div>
    </div>
</div>

@push('modals')
    @include('katproduk.create')
    @include('katproduk.edit')
    @include('katproduk.show')
@endpush

@push('scripts')
<script> //datatable
    $(document).ready(function() {
                $('#myTable').DataTable({
                    "columnDefs": [
                        {"orderable": false, "targets": 3},
                        {"searchable": false, "targets": 3},
                        {"targets": 0, "width": "100px", "className": "dt-center"},
                        {"targets": [1, 2, 3], "width": "120px"}
                    ],
                    "order": [
                        [0, 'asc']
                    ],
                    "responsive": true
                });
            });
</script>
<script>//reload table&datatable
    function reloadTable() {
        console.log('Memuat ulang tabel kategori...');
        $("#TableContainer").load(location.href + " #TableContainer > *", function() {
            console.log('Tabel kategori telah diperbarui.');
                $('#myTable').DataTable().destroy();
                $('#myTable').DataTable({
                    "columnDefs": [
                        {"orderable": false, "targets": 3},
                        {"searchable": false, "targets": 3},
                        {"targets": 0, "width": "100px", "className": "dt-center"},
                        {"targets": [1, 2, 3], "width": "120px"}
                    ],
                    "order": [
                        [0, 'asc']
                    ],
                    "responsive": true
                });
        });
    }
</script>
<script>//modal edit&show
    function show(id) {
        console.log(`Menampilkan kategori dengan ID: ${id}`);

        $.get('/kategori/' + id, function(response) {
            console.log("Data user berhasil diambil:", response);
            $('#show_kode').val(response.kode);
            $('#show_nama').val(response.nama);
            $('#show_created_at').val(response.created_at);
            $('#show_created_by').val(response.creator);
            $('#show_updated_at').val(response.updated_at);
            $('#show_updated_by').val(response.updater);
            $('#showModal').modal('show');
        }).fail(function(xhr) {
            console.log("Gagal mengambil data kategori:", xhr);
        });
    }

    function edit(id) {
        console.log(`Mengedit kategori dengan ID: ${id}`);

        $.get('/kategori/' + id + '/edit', function(response) {
            console.log("Data kategori berhasil diambil:", response);
            const form = $('#editForm');
            form.attr('action', '/kategori/' + id);
            $('#edit_nama').val(response.nama);
            $('#edit_kode').val(response.kode);
            $('#editModal').modal('show');
        }).fail(function(xhr) {
            console.log("Gagal mengambil data kategori:", xhr);
        });
    }
</script>
<script>//confirm delete
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
                        console.log(response); // Debugging response
                        iziToast.success({title: 'Success', message: "Kategori berhasil dihapus", position: 'topRight'});
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
<script>//toast dari controller
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