<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createForm" method="POST" action="{{ route('pelanggan.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama">
                            </div>
                            <div class="form-group">
                                <label for="telepon">Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat">
                            </div>
                            <div class="form-group">
                                <label for="tipe">Tipe</label>
                                <select class="form-control" id="tipe" name="tipe">
                                    <option value="Umum" selected>Umum</option>
                                    <option value="Loyal">Loyal</option>
                                    <option value="VIP">VIP</option>
                                </select>
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
    // Reset form dan error ketika modal ditutup
    $('#createModal').on('hidden.bs.modal', function() {
      $('#createForm').trigger('reset');
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').remove();
    });

    // Tangani submit form menggunakan AJAX
    $('#createForm').submit(function(e) {
      e.preventDefault();
      var form = $(this);

      // Lakukan validasi terlebih dahulu
      if (!validateForm()) {
        // Jika validasi gagal, showError akan dipanggil dan swal tidak muncul
        return;
      }

      // Jika validasi sukses, ambil data form
      var formData = form.serialize();
      var url = form.attr('action');

      // Tampilkan konfirmasi jika semua input sudah diisi
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
          $('#createModal').modal('hide');
          iziToast.success({
            title: 'Sukses',
            message: response.success,
            position: 'topRight'
          });
          // Panggil fungsi reload table atau refresh data sesuai kebutuhan
          reloadTable();
        },
        error: function(xhr) {
          form.find('button[type="submit"]').prop('disabled', false).html('Create');
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

    // Validasi sederhana untuk form pelanggan
    function validateForm() {
      var isValid = true;
      // Reset error state
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').remove();

      if ($('#nama').val().trim() === '') {
        iziToast.error({
              title: 'Error',
              message: 'Nama wajib diisi',
              position: 'topRight'
            });
        showError($('#nama'), 'Nama wajib diisi');
        isValid = false;
      }
      if ($('#telepon').val().trim() === '') {
        iziToast.error({
              title: 'Error',
              message: 'Telepon wajib diisi',
              position: 'topRight'
            });
        showError($('#telepon'), 'Telepon wajib diisi');
        isValid = false;
      }
      if ($('#alamat').val().trim() === '') {
        iziToast.error({
              title: 'Error',
              message: 'Alamat wajib diisi',
              position: 'topRight'
            });
        showError($('#alamat'), 'Alamat wajib diisi');
        isValid = false;
      }
      if ($('#tipe').val().trim() === '') {
        showError($('#tipe'), 'Tipe wajib diisi');
        isValid = false;
      }
      return isValid;
    }

    function handleValidationErrors(errors) {
      for (var field in errors) {
        var input = $('#' + field);
        showError(input, errors[field][0]);
      }
    }

    function showError(input, message) {
      input.addClass('is-invalid');
      input.after('<div class="invalid-feedback d-block">' + message + '</div>');
      // Scroll ke error pertama
      $('html, body').animate({
        scrollTop: input.offset().top - 100
      }, 500);
    }
  });
</script>
@endpush
