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
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role">
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
<script> //validasi form
    $(document).ready(function() {
        $('#createUserModal').on('hidden.bs.modal', function() {
            $('#createUserForm').trigger('reset');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        });

        $('#createUserForm').submit(function(e) {
            e.preventDefault();
            const form = $(this);

            // Validasi client-side
            if (!validateForm()){
                return;
            } 

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
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                beforeSend: function() {
                    form.find('button[type="submit"]').prop('disabled', true).html('Menyimpan...');
                },
                success: function(response) {
                    $('#createUserModal').modal('hide');
                    iziToast.success({title: 'Sukses', message: response.success, position: 'topRight',});
                    reloadUserTable();
                },
                error: function(xhr) {
                    form.find('button[type="submit"]').prop('disabled', false).html('Simpan');

                    if (xhr.status === 422) {
                        handleValidationErrors(xhr.responseJSON.errors);
                    } else {
                        iziToast.error({title: 'Error', message: xhr.responseJSON.message || 'Terjadi kesalahan sistem', position: 'topRight'});
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
                    console.log('Validasi gagal: Username kosong.');
                    iziToast.error({
                        title: 'Error',
                        message: 'Username wajib diisi',
                        position: 'topRight'
                    });
                    showError($(this), 'Username wajib diisi');
                    isValid = false;
                }else {
                console.log('Username valid:', $(this).val());
                }
            });

            $('#email').each(function() {
                const value = $(this).val().trim();
                if (value === '') {
                    console.log('Validasi gagal: Email kosong.');
                    iziToast.error({
              title: 'Error',
              message: 'Email wajib diisi',
              position: 'topRight'
            });
                    showError($(this), 'Email wajib diisi');
                    isValid = false;
                } else if (!emailRegex.test(value)) {
                    console.log('Validasi gagal: Format email tidak valid:', value);
                    iziToast.error({
              title: 'Error',
              message: 'Format email tidak valid',
              position: 'topRight'
            });
                    showError($(this), 'Format email tidak valid');
                    isValid = false;
                }else {
                console.log('Email valid:', value);
                }
            });

            $('#password').each(function() {
                const value = $(this).val();
                if (value === '') {
                    console.log('Validasi gagal: Password kosong.');
                    iziToast.error({
              title: 'Error',
              message: 'Password wajib diisi',
              position: 'topRight'
            });
                    showError($(this), 'Password wajib diisi');
                    isValid = false;
                } else if (!passwordRegex.test(value)) {
                    console.log('Validasi gagal: Password tidak memenuhi kriteria:', value);
                    iziToast.error({
              title: 'Error',
              message: 'Minimal 8 karakter dengan 1 huruf $ angka',
              position: 'topRight'
            });
                    showError($(this), 'Minimal 8 karakter dengan 1 huruf besar & angka');
                    isValid = false;
                }else {
                console.log('Password valid.');
                }   
            });

            $('#password_confirmation').each(function() {
                if ($(this).val() !== $('#password').val()) {
                    console.log('Validasi gagal: Konfirmasi password tidak cocok.');
                    iziToast.error({
              title: 'Error',
              message: 'Konfirmasi password tidak cocok',
              position: 'topRight'
            });
                    showError($(this), 'Konfirmasi password tidak cocok');
                    isValid = false;
                }else {
                console.log('Konfirmasi password cocok.');
                }
            });

            console.log('Validasi form selesai. isValid:', isValid);
            return isValid;
        }

        function handleValidationErrors(errors) {
            for (const field in errors) {
                const input = $(`#${field}`);
                console.log(`Error validasi pada field ${field}:`, errors[field][0]);
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
@endpush