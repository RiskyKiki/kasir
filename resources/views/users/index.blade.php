@extends('layouts.app')

@section('title', 'User')

@section('subtitle', 'User Management')

@section('content')
    <div class="section-body">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center w-100">
                <h4>A</h4>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success ml-auto" data-toggle="modal" data-target="#createUserModal"><i
                            class="fas fa-user-plus"></i></button>
                </div>
            </div>
            <div class="card-body">
                {{-- <div class="section-title mt-0">A</div> --}}
                <div class="table-responsive" style="overflow-x: auto; min-height: 400px;">
                    <table id="myTable" class="display table table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created at</th>
                                <th>Created by</th>
                                <th>Updated at</th>
                                <th>Updated by</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->count() > 0)
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-warning btn-sm edit-btn"
                                                    data-id="{{ $user->id }}" data-username="{{ $user->username }}"
                                                    data-email="{{ $user->email }}" data-role="{{ $user->role }}"
                                                    data-bs-toggle="modal" data-bs-target="#editUserModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $user->id }}" data-toggle="modal"
                                                    data-target="#deleteUserModal">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->username ?? '?' }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role ?? '?' }}</td>
                                        <td>{{ $user->created_at ?? '?' }}</td>
                                        <td>{{ $user->creator->username ?? '?' }}</td>
                                        <td>{{ $user->updated_at ?? '?' }}</td>
                                        <td>{{ $user->updater->username ?? '?' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" style="text-align: center;"><small>Data Tidak
                                            ditemukan</small></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    @include('users.create')
    @include('users.edit')
    @include('users.delete')
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "columnDefs": [{
                        "orderable": false,
                        "targets": 0
                    }, // Non-aktifkan sorting untuk kolom action
                    {
                        "searchable": false,
                        "targets": 0
                    }, // Non-aktifkan search untuk kolom action
                    {
                        "targets": 0,
                        "width": "100px",
                        "className": "dt-center"
                    },
                    {
                        "targets": [1, 4, 5, 6, 7],
                        "width": "120px"
                    }
                ],
                "order": [
                    [1, 'asc']
                ],
                "responsive": true
            });
        });

        $(document).ready(function() {

            $('#createUserModal').on('show.bs.modal', function() {
                $(this).find('form')[0].reset(); // Reset form setiap kali modal ditampilkan
            });

            $('#editUserModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset(); // Reset form saat modal ditutup
            });

            $('.edit-btn').click(function() {
                const userId = $(this).data('id');
                const username = $(this).data('username');
                const email = $(this).data('email');
                const role = $(this).data('role');
                const password = $(this).data('password');

                // Pastikan form modal diisi dengan data yang benar
                $('#editUserForm').attr('action', `/users/${userId}`);
                $('#edit_username').val(username);
                $('#edit_email').val(email);
                $('#edit_role').val(role);
                $('#edit_password').val(password);

                // Tampilkan modal
                $('#editUserModal').modal('show');
            });

            // Handle delete button click
            $('.delete-btn').click(function() {
                const userId = $(this).data('id');
                $('#deleteUserForm').attr('action', `/users/${userId}`);
            });
        });

        $(document).ready(function() {
            @if (session('success'))
                iziToast.success({
                    title: 'Berhasil',
                    message: "{{ session('success') }}",
                    position: 'topRight',
                    timeout: 5000, // Waktu tampil
                });
            @endif

            @if (session('error'))
                iziToast.error({
                    title: 'Gagal',
                    message: "{{ session('error') }}",
                    position: 'topRight',
                    timeout: 5000,
                });
            @endif
        });
    </script>
@endpush
