<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="{{ route('produk.update', $produk->id ?? '') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="edit_kode">Kode</label>
                                <input type="text" class="form-control" id="edit_kode" name="kode" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_nama">Nama Produk</label>
                                <input type="text" class="form-control" id="edit_nama" name="nama">
                            </div>
                            <div class="form-group">
                                <label for="edit_kategori">Kategori</label>
                                <select class="form-control" id="edit_kategori" name="kategori_id">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_pembelian">Tanggal Pembelian</label>
                                <input type="date" class="form-control" id="edit_tanggal_pembelian"
                                    name="tanggal_pembelian" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_tanggal_kadaluarsa">Tanggal Kadaluarsa</label>
                                <input type="date" class="form-control" id="edit_tanggal_kadaluarsa"
                                    name="tanggal_kadaluarsa">
                            </div>
                            <div class="form-group">
                                <label for="edit_stok">Stok</label>
                                <input type="number" class="form-control" id="edit_stok" name="stok">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="edit_min_stok">Minimal Stok</label>
                                <input type="number" class="form-control" id="edit_min_stok" name="min_stok">
                            </div>

                            <div class="form-group">
                                <label for="edit_hpp">Harga Pokok Penjualan (HPP)</label>
                                <input type="number" class="form-control" id="edit_hpp" name="hpp">
                            </div>
                            <div class="form-group">
                                <label for="edit_harga1">Harga 1 (HPP + 10%)</label>
                                <input type="number" class="form-control" id="edit_harga1" name="harga1" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_harga2">Harga 2 (HPP + 20%)</label>
                                <input type="number" class="form-control" id="edit_harga2" name="harga2" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_harga3">Harga 3 (HPP + 30%)</label>
                                <input type="number" class="form-control" id="edit_harga3" name="harga3" readonly>
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
            console.log("Script edit pelanggan dimuat...");
            $('#editModal').on('hidden.bs.modal', function() {
                console.log("Modal edit ditutup. Mereset form...");
                $('#editForm').trigger('reset');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            });

            $('#edit_hpp').on('input', function() {
                let hpp = parseFloat($(this).val()) || 0;
                console.log(hpp);
                $('#edit_harga1').val(hpp + (hpp * 0.10));
                $('#edit_harga2').val(hpp + (hpp * 0.20));
                $('#edit_harga3').val(hpp + (hpp * 0.30));
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
                    method: 'PUT',
                    data: formData,
                    beforeSend: function() {
                        console.log("Mengunci tombol submit...");
                        form.find('button[type="submit"]').prop('disabled', true).html('Menyimpan...');
                    },
                    success: function(response) {
                        console.log("Respon berhasil diterima:", response);
                        $('#editModal').modal('hide');
                        iziToast.success({
                            title: 'Sukses',
                            message: response.success,
                            position: 'topRight'
                        });
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
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                console.log("Memeriksa validasi form...");

                if ($('#edit_nama').val().trim() === '') {
                    console.log("Validasi gagal: Nama kosong.");
                    showError($('#edit_nama'), 'Nama produk wajib diisi');
                    isValid = false;
                }

                if ($('#edit_min_stok').val().trim() === '' || $('#edit_min_stok').val() <= 0) {
                    console.log("Validasi gagal: Minimal stok harus lebih dari 0.");
                    showError($('#edit_min_stok'), 'Minimal stok harus lebih dari 0');
                    isValid = false;
                }

                if ($('#edit_hpp').val().trim() === '' || $('#edit_hpp').val() <= 0) {
                    console.log("Validasi gagal: HPP harus lebih dari 0.");
                    showError($('#edit_hpp'), 'HPP harus lebih dari 0');
                    isValid = false;
                }

                console.log("Validasi selesai, hasil:", isValid);
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
