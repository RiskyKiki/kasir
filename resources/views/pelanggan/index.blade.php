@extends('layouts.app')

@section('title', 'Customer')

@section('subtitle', 'Customer Management')

@section('content')

<div class="section-body">
    <div class="card">
        <div class="text-right mb-3">
            <button class="btn btn-success" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-user-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="overflow-x: auto">
                <div id="TableContainer">
                    @include('pelanggan.table')
                </div>
            </div>
        </div>
    </div>
</div>

@push('modals')
    @include('pelanggan.create')
    @include('pelanggan.edit')
    @include('pelanggan.show')
@endpush

@push('scripts')
<script> //datatable
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
<script>//reload table&datatable
    function reloadTable() {
        console.log('Memuat ulang tabel pelanggan...');
        $("#TableContainer").load(location.href + " #TableContainer > *", function() {
            console.log('Tabel pelanggan telah diperbarui.');
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
        });
    }
</script>
<script>//modal edit&show
    function show(id) {
        console.log(`Menampilkan pelanggan dengan ID: ${id}`);

        $.get('/pelanggan/' + id, function(response) {
            console.log("Data user berhasil diambil:", response);
            $('#show_nama').val(response.nama);
            $('#show_telepon').val(response.telepon);
            $('#show_alamat').val(response.alamat);
            $('#show_tipe').val(response.tipe);
            $('#show_poin').val(response.poin);
            $('#show_created_at').val(response.created_at);
            $('#show_created_by').val(response.creator);
            $('#show_updated_at').val(response.updated_at);
            $('#show_updated_by').val(response.updater);
            $('#showModal').modal('show');
        }).fail(function(xhr) {
            console.log("Gagal mengambil data pelanggan:", xhr);
        });
    }

    function edit(id) {
        console.log(`Mengedit pelanggan dengan ID: ${id}`);

        $.get('/pelanggan/' + id + '/edit', function(response) {
            console.log("Data pelanggan berhasil diambil:", response);
            const form = $('#editForm');
            form.attr('action', '/pelanggan/' + id);
            $('#edit_nama').val(response.nama);
            $('#edit_telepon').val(response.telepon);
            $('#edit_alamat').val(response.alamat);
            $('#edit_tipe').val(response.tipe);
            $('#edit_poin').val(response.poin);
            $('#editModal').modal('show');
        }).fail(function(xhr) {
            console.log("Gagal mengambil data pelanggan:", xhr);
        });
    }
</script>
<script>//confirm delete
    function confirmDelete(id, deleteUrl) {
        swal({
            title: "Apakah Anda yakin?",
            text: "Setelah dihapus, data ini tidak bisa dikembalikan!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        reloadTable()
                        iziToast.success({title: 'Success', message: "Pelanggan berhasil dihapus", position: 'topRight'});
                    },
                    error: function() {
                        swal('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                        iziToast.error({title: 'Error', message: "Pelanggan gagl dihapus", position: 'topRight'})
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