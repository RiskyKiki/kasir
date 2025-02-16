<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST">
                    <div class="row">
                        <div class="col-lg-6">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="edit_username">Username</label>
                                <input type="text" class="form-control" id="edit_username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_role">Role</label>
                                <select class="form-control" id="edit_role" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="petugas">Petugas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="password">Password (Opsional)</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Kosongkan jika tidak merubah password">
                                    <div class="input-group-append">
                                        <button type="button" class="btn" onclick="togglePassword('password', 'togglePasswordIcon')">
                                            <i id="togglePasswordIcon" class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Kosongkan jika tidak mengisi password">
                                    <div class="input-group-append">
                                        <button type="button" class="btn" onclick="togglePassword('password_confirmation', 'toggleConfirmPasswordIcon')">
                                            <i id="toggleConfirmPasswordIcon" class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            console.log("Script editUserForm loaded");

            // Reset form saat modal ditutup
            $('#editUserModal').on('hidden.bs.modal', function() {
                console.log("Modal editUserModal ditutup, reset form");
                $('#editUserForm').trigger('reset');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            });

            $('#editUserForm').submit(function(e) {
                e.preventDefault();
                console.log("Form editUserForm dikirim");

                const form = $(this);
                const formData = form.serialize();
                const url = form.attr('action');

                console.log("Form data:", formData);
                console.log("Form action URL:", url);

                swal({
                    title: "Konfirmasi",
                    text: "Apakah Anda yakin ingin menyimpan perubahan?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    console.log("Konfirmasi swal:", confirm);
                    if (confirm) {
                        submitEditForm(form, url, formData);
                    }
                });
            });

            function submitEditForm(form, url, formData) {
                console.log("Submit edit form ke URL:", url);

                if (!validateForm()) {
                    console.log("Validasi form gagal, tidak melanjutkan AJAX request");
                    return;
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    beforeSend: function() {
                        console.log("Sebelum mengirim AJAX request, menonaktifkan tombol submit");
                        form.find('button[type="submit"]').prop('disabled', true).html('Menyimpan...');
                    },
                    success: function(response) {
                        console.log("AJAX request sukses:", response);

                        $('#editUserModal').modal('hide');
                        iziToast.success({
                            title: 'Sukses',
                            message: response.success,
                            position: 'topRight',
                            timeout: 2000
                        });

                        setTimeout(() => {
                            console.log("Refresh halaman setelah update");
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        console.log("AJAX request error:", xhr);

                        form.find('button[type="submit"]').prop('disabled', false).html(
                            'Simpan Perubahan');

                        if (xhr.status === 422) {
                            console.log("Validasi gagal, menampilkan error:", xhr.responseJSON.errors);
                            handleValidationErrors(xhr.responseJSON.errors);
                        } else {
                            console.log("Kesalahan sistem:", xhr.responseJSON.message);
                            iziToast.error({
                                title: 'Error',
                                message: xhr.responseJSON.message || 'Terjadi kesalahan sistem',
                                position: 'topRight'
                            });
                        }
                    }
                });
            }

            function validateForm() {
                let isValid = true;
                console.log("Memulai validasi form");

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $('#username, #email, #password, #password_confirmation').each(function() {
                    console.log(`Memeriksa field: ${this.id} dengan nilai:`, $(this).val());
                });

                return isValid;
            }

            function handleValidationErrors(errors) {
                console.log("Menangani error validasi:", errors);
                for (const field in errors) {
                    const input = $(`#${field}`);
                    showError(input, errors[field][0]);
                }
            }

            function showError(input, message) {
                console.log(`Menampilkan error pada field ${input.attr('id')}:`, message);
                input.addClass('is-invalid');
                input.after(`<div class="invalid-feedback d-block">${message}</div>`);
            }
        });
    </script>
    <script>
        function editUser(userId) {
            console.log(`Mengedit user dengan ID: ${userId}`);

            $.get('/users/' + userId + '/edit', function(response) {
                console.log("Data user berhasil diambil:", response);

                const form = $('#editUserForm');
                form.attr('action', '/users/' + userId);
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
    <script>
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
