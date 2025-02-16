@extends('layouts.app')

@section('title', 'User')

@section('subtitle', 'User Management')

@section('content')

<div class="section-body">
    <div class="card">
        <div class="text-right mb-3">
            <button class="btn btn-success" data-toggle="modal" data-target="#createUserModal">
                <i class="fas fa-user-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="overflow-x: auto">
                <div id="userTableContainer">
                    @include('users.table')
                </div>
            </div>
        </div>
    </div>
</div>

@push('modals')
    @include('users.create')
    @include('users.edit')
    @include('users.show')
@endpush

@push('scripts')
<script> //datatable
    $(document).ready(function() {
                    $('#userTable').DataTable({
                        "columnDefs": [
                            {"orderable": false, "targets": 4},
                            {"searchable": false, "targets": 4},
                            {"targets": 0, "width": "100px", "className": "dt-center"},
                            {"targets": [1, 2, 3, 4], "width": "120px"}
                        ],
                        "order": [
                            [0, 'asc']
                        ],
                        "responsive": true
                    });
                });
</script>
    <script>//reload table&datatable
        function reloadUserTable() {
            console.log('Memuat ulang tabel user...');
            $("#userTableContainer").load(location.href + " #userTableContainer > *", function() {
                console.log('Tabel user telah diperbarui.');
                $(document).ready(function() {
                    $('#userTable').DataTable({
                        "columnDefs": [
                            {"orderable": false, "targets": 4},
                            {"searchable": false, "targets": 4},
                            {"targets": 0, "width": "100px", "className": "dt-center"},
                            {"targets": [1, 2, 3, 4], "width": "120px"}
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
        function showUser(id) {
            console.log(`Menampilkan user dengan ID: ${id}`);

            $.get('/users/' + id, function(response) {
                console.log("Data user berhasil diambil:", response);
                $('#show_username').val(response.username);
                $('#show_email').val(response.email);
                $('#show_role').val(response.role);
                $('#show_created_at').val(response.created_at);
                $('#show_created_by').val(response.creator);
                $('#show_updated_at').val(response.updated_at);
                $('#show_updated_by').val(response.updater);
                $('#showUserModal').modal('show');
            }).fail(function(xhr) {
                console.log("Gagal mengambil data user:", xhr);
            });
        }

        function editUser(id) {
            console.log(`Mengedit user dengan ID: ${id}`);

            $.get('/users/' + id + '/edit', function(response) {
                console.log("Data user berhasil diambil:", response);
                const form = $('#editUserForm');
                form.attr('action', '/users/' + id);
                $('#edit_username').val(response.username);
                $('#edit_email').val(response.email);
                $('#edit_role').val(response.role);
                $('#edit_password').val('');
                $('#edit_password_confirmation').val('');
                $('#editUserModal').modal('show');
            }).fail(function(xhr) {
                console.log("Gagal mengambil data user:", xhr);
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
                            reloadUserTable();
                            iziToast.success({title: 'Success', message: "User berhasil dihapus", position: 'topRight'});
                        },
                        error: function() {
                            swal('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                            iziToast.error({title: 'Error', message: "User gagl dihapus", position: 'topRight'})
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
    <script>//togle password
        function togglePassword(inputId, iconId) {
            let passwordInput = document.getElementById(inputId);
            let icon = document.getElementById(iconId);
    
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
@endpush

@endsection