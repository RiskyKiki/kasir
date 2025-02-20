@extends('layouts.app')

@section('title', 'Kategori')

@section('subtitle', 'Pendataan Kategori Produk')

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
<script>
// Inisialisasi DataTable saat document ready
$(document).ready(function() {
  $('#myTable').DataTable({
    "columnDefs": [
      {"orderable": false, "targets": 3},
      {"searchable": false, "targets": 3},
      {"targets": 0, "width": "100px", "className": "dt-center"},
      {"targets": [1, 2, 3], "width": "120px"}
    ],
    "order": [[0, 'asc']],
    "responsive": true
  });
});
</script>
<script>
// Fungsi reload table dan inisialisasi ulang DataTable
function reloadTable() {
  $("#TableContainer").load(location.href + " #TableContainer > *", function() {
    $('#myTable').DataTable().destroy();
    $('#myTable').DataTable({
      "columnDefs": [
        {"orderable": false, "targets": 3},
        {"searchable": false, "targets": 3},
        {"targets": 0, "width": "100px", "className": "dt-center"},
        {"targets": [1, 2, 3], "width": "120px"}
      ],
      "order": [[0, 'asc']],
      "responsive": true
    });
  });
}
</script>
<script>
// Modal untuk menampilkan data kategori
function show(id) {
  $.get('/kategori/' + id, function(response) {
    $('#show_kode').val(response.kode);
    $('#show_nama').val(response.nama);
    $('#show_created_at').val(response.created_at);
    $('#show_created_by').val(response.creator);
    $('#show_updated_at').val(response.updated_at);
    $('#show_updated_by').val(response.updater);
    $('#showModal').modal('show');
  });
}
</script>
<script>
// Modal untuk mengedit data kategori
function edit(id) {
  $.get('/kategori/' + id + '/edit', function(response) {
    const form = $('#editForm');
    form.attr('action', '/kategori/' + id);
    $('#edit_kode').val(response.kode);
    $('#edit_nama').val(response.nama);
    $('#editModal').modal('show');
  });
}
</script>
<script>
// Konfirmasi dan proses penghapusan data kategori
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
          iziToast.success({title: 'Success', message: "Kategori berhasil dihapus", position: 'topRight'});
          reloadTable();
        },
        error: function(xhr) {
          swal('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
          iziToast.error({title: 'Error', message: "Kategori gagal dihapus", position: 'topRight'});
        }
      });
    }
  });
}
</script>
@endpush
@endsection
