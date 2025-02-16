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
                    <table id="userTable" class="display table table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->username ?? '-' }}</td>
                                <td>{{ $user->email ?? '-'}}</td>
                                <td>{{ ucfirst($user->role) ?? '-'}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-info btn-sm" onclick="showUser({{ $user->id }})"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-warning btn-sm" onclick="editUser({{ $user->id }})"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }}, '{{ route('users.destroy', $user->id) }}')"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        <script>
            $(document).ready(function() {  
                $('#userTable').DataTable({
                    "columnDefs": [
                        {"orderable": false, "targets": 4}, // Non-aktifkan sorting untuk kolom action
                        { "searchable": false, "targets": 4}, // Non-aktifkan search untuk kolom action
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
        <script>
            function showUser(userId) {
                $.get('/users/' + userId, function(response) {
                    $('#show_username').val(response.username);
                    $('#show_email').val(response.email);
                    $('#show_role').val(response.role);
                    $('#show_created_at').val(response.created_at);
                    $('#show_created_by').val(response.creator);
                    $('#show_updated_at').val(response.updated_at);
                    $('#show_updated_by').val(response.updater);
                    $('#showUserModal').modal('show');
                });
            }

            function editUser(userId) {
                $.get('/users/' + userId + '/edit', function(response) {
                    $('#editUserForm').attr('action', '/users/' + userId);
                    $('#edit_username').val(response.username);
                    $('#edit_email').val(response.email);
                    $('#edit_role').val(response.role);
                    $('#editUserModal').modal('show');
                });
            }
        </script>
        <script>  
            $(document).ready(function() {
                @if (session('success'))
                    iziToast.success({ title: 'Success', message: "{{ session('success') }}", position: 'topRight'});
                @endif

                @if (session('error'))
                    iziToast.error({ title: 'Error', message: "{{ session('error') }}", position: 'topRight'});
                @endif
            });
        </script>
        <script>
            function confirmDelete(userId, deleteUrl) {
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
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                                iziToast.success({ title: 'Success', message: "User berhasil dihapus", position: 'topRight'});
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
        @endpush
        @endsection
