<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createUserForm" method="POST" action="{{ route('users.store') }}">
                    <div class="row">
                        <div class="col-lg-6">
                            @csrf
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="petugas" selected>Petugas</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control">
                                    <div class="input-group-append">
                                        <button type="button" class="btn"
                                            onclick="togglePassword('password', 'togglePasswordIcon')">
                                            <i id="togglePasswordIcon" class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control">
                                    <div class="input-group-append">
                                        <button type="button" class="btn"
                                            onclick="togglePassword('password_confirmation', 'toggleConfirmPasswordIcon')">
                                            <i id="toggleConfirmPasswordIcon" class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Reset form dan error saat modal ditutup
            $('#createUserModal').on('hidden.bs.modal', function() {
                $('#createUserForm').trigger('reset');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            });

            $('#createUserForm').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = form.serialize();
                const url = form.attr('action');

                swal({
                    title: "Konfirmasi",
                    text: "Apakah data yang dimasukkan sudah benar?",
                    icon: "info",
                    buttons: true,
                }).then((confirm) => {
                    if (confirm) {
                        processFormSubmission(form, url, formData);
                    }
                });
            });

            function processFormSubmission(form, url, formData) {
                // Validasi client-side
                if (!validateForm()) return;

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    beforeSend: function() {
                        form.find('button[type="submit"]').prop('disabled', true).html('Menyimpan...');
                    },
                    success: function(response) {
                        $('#createUserModal').modal('hide');
                        iziToast.success({
                            title: 'Sukses',
                            message: response.success,
                            position: 'topRight',
                            timeout: 2000
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        form.find('button[type="submit"]').prop('disabled', false).html('Simpan');

                        if (xhr.status === 422) {
                            handleValidationErrors(xhr.responseJSON.errors);
                        } else {
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
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const passwordRegex = /^(?=.*[A-Z])(?=.*\d).{8,}$/;

                // Reset error state
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                // Validasi tiap field
                $('#username').each(function() {
                    if ($(this).val().trim() === '') {
                        showError($(this), 'Username wajib diisi');
                        isValid = false;
                    }
                });

                $('#email').each(function() {
                    const value = $(this).val().trim();
                    if (value === '') {
                        showError($(this), 'Email wajib diisi');
                        isValid = false;
                    } else if (!emailRegex.test(value)) {
                        showError($(this), 'Format email tidak valid');
                        isValid = false;
                    }
                });

                $('#password').each(function() {
                    const value = $(this).val();
                    if (value === '') {
                        showError($(this), 'Password wajib diisi');
                        isValid = false;
                    } else if (!passwordRegex.test(value)) {
                        showError($(this), 'Minimal 8 karakter dengan 1 huruf besar & angka');
                        isValid = false;
                    }
                });

                $('#password_confirmation').each(function() {
                    if ($(this).val() !== $('#password').val()) {
                        showError($(this), 'Konfirmasi password tidak cocok');
                        isValid = false;
                    }
                });

                return isValid;
            }

            function handleValidationErrors(errors) {
                for (const field in errors) {
                    const input = $(`#${field}`);
                    showError(input, errors[field][0]);
                }
            }

            function showError(input, message) {
                input.addClass('is-invalid');
                input.after(`<div class="invalid-feedback d-block">${message}</div>`);
                // Scroll ke error pertama
                $('html, body').animate({
                    scrollTop: input.offset().top - 100
                }, 500);
            }
        });
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
