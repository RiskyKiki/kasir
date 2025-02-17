<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createForm" method="POST" action="{{ route('produk.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="kode">Kode</label>
                                <input type="text" class="form-control" id="kode" name="kode" value="PRD-">
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama Produk</label>
                                <input type="text" class="form-control" id="nama" name="nama">
                            </div>
                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <select class="form-control" id="kategori" name="kategori_id">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian" readonly>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_kadaluarsa">Tanggal Kadaluarsa</label>
                                <input type="date" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa">
                            </div>
                            <div class="form-group">
                                <label for="stok">Stok</label>
                                <input type="number" class="form-control" id="stok" name="stok">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="min_stok">Minimal Stok</label>
                                <input type="number" class="form-control" id="min_stok" name="min_stok">
                            </div>
                        
                            <div class="form-group">
                                <label for="hpp">Harga Pokok Penjualan</label>
                                <input type="number" class="form-control" id="hpp" name="hpp">
                            </div>
                            <div class="form-group">
                                <label for="harga1">Harga 1</label>
                                <input type="number" class="form-control" id="harga1" name="harga1" readonly>
                            </div>
                            <div class="form-group">
                                <label for="harga2">Harga 2</label>
                                <input type="number" class="form-control" id="harga2" name="harga2" readonly>
                            </div>
                            <div class="form-group">
                                <label for="harga3">Harga 3</label>
                                <input type="number" class="form-control" id="harga3" name="harga3" readonly>
                            </div>
                        </div>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">Create</button>
            </div>
        </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
  $(document).ready(function() {
    // Set tanggal pembelian otomatis ke tanggal hari ini
    let today = new Date().toISOString().split('T')[0];
    $('#tanggal_pembelian').val(today);

    // Reset form ketika modal ditutup
    $('#createModal').on('hidden.bs.modal', function() {
      $('#kode').val('PRD-');
      $('#createForm').trigger('reset');
      $('#tanggal_pembelian').val(today);
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').remove();
    });

    // Hitung Harga 1, 2, 3 berdasarkan HPP
    $('#hpp').on('input', function() {
      let hpp = parseFloat($(this).val()) || 0;
      $('#harga1').val(hpp + (hpp * 0.10));
      $('#harga2').val(hpp + (hpp * 0.20));
      $('#harga3').val(hpp + (hpp * 0.30));
    });

    // Submit form dengan validasi
    $('#createForm').submit(function(e) {
      e.preventDefault();
      if (!validateForm()) return;

      let form = $(this);
      let formData = form.serialize();
      let url = form.attr('action');

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
          $('#submitBtn').prop('disabled', true).html('Menyimpan...');
        },
        success: function(response) {
          $('#createModal').modal('hide');
          iziToast.success({
            title: 'Sukses',
            message: response.success,
            position: 'topRight'
          });
          reloadTable();
        },
        error: function(xhr) {
          $('#submitBtn').prop('disabled', false).html('Create');
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
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').remove();

      if ($('#kode').val().trim() === '') {
        iziToast.error({ title: 'Error', message: 'Kode wajib diisi', position: 'topRight' });
        showError($('#kode'), 'Kode wajib diisi');
        isValid = false;
      }
      if ($('#nama').val().trim() === '') {
        iziToast.error({ title: 'Error', message: 'Nama wajib diisi', position: 'topRight' });
        showError($('#nama'), 'Nama wajib diisi');
        isValid = false;
      }
      if ($('#kategori').val().trim() === '') {
        iziToast.error({ title: 'Error', message: 'Kategori wajib dipilih', position: 'topRight' });
        showError($('#kategori'), 'Kategori wajib dipilih');
        isValid = false;
      }
      if ($('#tanggal_kadaluarsa').val().trim() === '') {
        iziToast.error({ title: 'Error', message: 'Tanggal kadaluarsa wajib dipilih', position: 'topRight' });
        showError($('#tanggal_kadaluarsa'), 'tanggal_kadaluarsa wajib dipilih');
        isValid = false;
      }
      if ($('#stok').val().trim() === '' || $('#stok').val() <= 0) {
        iziToast.error({ title: 'Error', message: 'Stok wajib diisi dengan angka positif', position: 'topRight' });
        showError($('#stok'), 'Stok wajib diisi dengan angka positif');
        isValid = false;
      }
      if ($('#min_stok').val().trim() === '' || $('#min_stok').val() <= 0) {
        iziToast.error({ title: 'Error', message: 'Minimal stok wajib diisi dengan angka positif', position: 'topRight' });
        showError($('#min_stok'), 'min_Stok wajib diisi dengan angka positif');
        isValid = false;
      }
      if ($('#hpp').val().trim() === '' || $('#hpp').val() <= 0) {
        iziToast.error({ title: 'Error', message: 'HPP wajib diisi dengan angka positif', position: 'topRight' });
        showError($('#hpp'), 'HPP wajib diisi dengan angka positif');
        isValid = false;
      }

      return isValid;
    }

    function showError(input, message) {
      input.addClass('is-invalid');
      input.after('<div class="invalid-feedback d-block">' + message + '</div>');
    }
  });
</script>

@endpush
