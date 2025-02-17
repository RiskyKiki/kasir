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
                        <input type="text" class="form-control" id="edit_kode" name="kode" value="PDR-">
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
  // Reset form dan error saat modal ditutup
  $('#editModal').on('hidden.bs.modal', function() {
    $('#editForm').trigger('reset');
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
  });

  // Tangani submit form edit
  $('#editForm').submit(function(e) {
    e.preventDefault();
    var form = $(this);

    // Validasi form terlebih dahulu
    if (!validateForm()) {
      return;
    }

    var formData = form.serialize();
    var url = form.attr('action');

    swal({
      title: "Konfirmasi",
      text: "Apakah Anda yakin ingin menyimpan perubahan?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then((confirm) => {
      if (confirm) {
        submitEditForm(form, url, formData);
      }
    });
  });

  function submitEditForm(form, url, formData) {
    $.ajax({
      url: url,
      method: 'POST',
      data: formData,
      
      beforeSend: function() {
        form.find('button[type="submit"]').prop('disabled', true).html('Menyimpan...');
      },
      success: function(response) {
        $('#editModal').modal('hide');
        iziToast.success({
          title: 'Sukses',
          message: response.success,
          position: 'topRight'
        });
        // Panggil fungsi reload tabel atau refresh data sesuai kebutuhan, misal:
        reloadTable();
      },
      error: function(xhr) {
        form.find('button[type="submit"]').prop('disabled', false).html('Simpan Perubahan');
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
      var isValid = true;
      // Reset error state
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').remove();

    if ($('#edit_kode').val().trim() === '') {
            iziToast.error({
                title: 'Error',
                message: 'Kode wajib diisi',
                position: 'topRight'
                });
            showError($('#edit_kode'), 'Kode wajib diisi');
            isValid = false;
        }
      if ($('#edit_nama').val().trim() === '') {
        iziToast.error({
              title: 'Error',
              message: 'Nama wajib diisi',
              position: 'topRight'
            });
        showError($('#edit_nama'), 'Nama wajib diisi');
        isValid = false;
      }
      
      return isValid;
    }

  function handleValidationErrors(errors) {
    $.each(errors, function(field, messages) {
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