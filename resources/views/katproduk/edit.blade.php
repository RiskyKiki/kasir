<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="{{ route('kategori.update', $katproduk->id ?? '') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_kode">Kode</label>
                        <input type="text" class="form-control" id="edit_kode" name="kode" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama">Nama</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama">
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
            console.log("Script edit pelanggan dimuat...");
            $('#editModal').on('hidden.bs.modal', function() {
                console.log("Modal edit ditutup. Mereset form...");
                $('#editForm').trigger('reset');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            });

            $('#editForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();
                var url = form.attr('action');

                console.log("Mengirim form edit dengan data:", formData);

                if (!validateForm()) {
                    console.log("Validasi form gagal, pengiriman dibatalkan.");
                    return;
                }

                swal({
                    title: "Konfirmasi",
                    text: "Apakah Anda yakin ingin menyimpan perubahan?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        console.log("Konfirmasi diterima, mengirim data...");
                        submitEditForm(form, url, formData);
                    } else {
                        console.log("Pengeditan dibatalkan oleh pengguna.");
                    }
                });
            });

            function submitEditForm(form, url, formData) {
                console.log(`Mengirim AJAX request ke: ${url}`);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    beforeSend: function() {
                        console.log("Mengunci tombol submit...");
                        form.find('button[type="submit"]').prop('disabled', true).html('Menyimpan...');
                    },
                    success: function(response) {
                        console.log("Respon berhasil diterima:", response);
                        $('#editModal').modal('hide');
                        iziToast.success({title: 'Sukses', message: response.success, position: 'topRight'});
                        reloadTable();
                    },
                    error: function(xhr) {
                        console.log("Terjadi kesalahan saat mengirim AJAX:", xhr);
                        form.find('button[type="submit"]').prop('disabled', false).html(
                            'Simpan Perubahan');
                        if (xhr.status === 422) {
                            console.log("Kesalahan validasi dari server:", xhr.responseJSON.errors);
                            handleValidationErrors(xhr.responseJSON.errors);
                        } else {
                            console.log("Kesalahan sistem:", xhr.responseJSON.message);
                            iziToast.error({title: 'Error', message: xhr.responseJSON.message || 'Terjadi kesalahan sistem', position: 'topRight'});
                        }
                    }
                });
            }

            function validateForm() {
                var isValid = true;
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                
                console.log("Memeriksa validasi form...");

                if ($('#edit_nama').val().trim() === '') {
                  console.log("Validasi gagal: Nama kosong.");
                    iziToast.error({title: 'Error', message: 'Nama wajib diisi', position: 'topRight'});
                    showError($('#edit_nama'), 'Nama wajib diisi');
                    isValid = false;
                }

                return isValid;
            }

            function handleValidationErrors(errors) {
              console.log("Menangani kesalahan validasi...");
                $.each(errors, function(field, messages) {
                  console.log(`Kesalahan pada ${field}: ${messages[0]}`);
                    var input = $('#' + field);
                    showError(input, messages[0]);
                });
            }

            function showError(input, message) {
                input.addClass('is-invalid');
                input.after('<div class="invalid-feedback d-block">' + message + '</div>');
            }
        });
    </script>
@endpush
