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
                                    <option value="Perunggu" selected>Perunggu</option>
                                    <option value="Perak">Perak</option>
                                    <option value="Emas">Emas</option>
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
    console.log("Document ready");
    $('#createModal').on('hidden.bs.modal', function() {
      console.log("Modal ditutup");
      $('#createForm').trigger('reset');
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').remove();
    });
    $('#createForm').submit(function(e) {
      e.preventDefault();
      console.log("Form disubmit");

      var form = $(this);
      var formData = form.serialize();
      var url = form.attr('action');

      if (!validateForm()) {
        console.log("Validasi form gagal");
        return;
      }

      console.log("Mengirim konfirmasi swal");
      swal({
        title: "Konfirmasi",
        text: "Apakah data yang dimasukkan sudah benar?",
        icon: "info",
        buttons: true,
      }).then((confirm) => {
        if (confirm) {
          console.log("Konfirmasi diterima, memproses form...");
          processFormSubmission(form, url, formData);
        } else {
          console.log("Konfirmasi dibatalkan");
        }
      });
    });

    function processFormSubmission(form, url, formData) {
      console.log("Mengirim AJAX request ke:", url);
      console.log("Data yang dikirim:", formData);

      $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        beforeSend: function() {
          console.log("Sebelum mengirim request...");
          form.find('button[type="submit"]').prop('disabled', true).html('Menyimpan...');
        },
        success: function(response) {
          console.log("Response sukses:", response);
          $('#createModal').modal('hide');
          iziToast.success({title: 'Sukses', message: response.success, position: 'topRight'});
          reloadTable();
        },
        error: function(xhr) {
          console.log("Response error:", xhr);
          form.find('button[type="submit"]').prop('disabled', false).html('Create');
          if (xhr.status === 422) {
            console.log("Error validasi:", xhr.responseJSON.errors);
            handleValidationErrors(xhr.responseJSON.errors);
          } else {
            console.log("Terjadi kesalahan sistem");
            iziToast.error({title: 'Error', message: xhr.responseJSON.message || 'Terjadi kesalahan sistem', position: 'topRight'});
          }
        }
      });
    }

    function validateForm() {
      var isValid = true;
      console.log("Memvalidasi form...");
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').remove();

      if ($('#nama').val().trim() === '') {
        console.log("Error: Nama wajib diisi");
        iziToast.error({title: 'Error', message: 'Nama wajib diisi', position: 'topRight'});
        showError($('#nama'), 'Nama wajib diisi');
        isValid = false;
      }
      if ($('#telepon').val().trim() < 8) {
        console.log("Error: Nomor telepon minimal 8 digit");
        iziToast.error({title: 'Error', message: 'Nomor telepon minimal 8 digit', position: 'topRight'});
        showError($('#telepon'), 'Nomor telepon minimal 8 digit');
        isValid = false;
      }
      if ($('#alamat').val().trim() === '') {
        console.log("Error: Alamat wajib diisi");
        iziToast.error({title: 'Error', message: 'Alamat wajib diisi', position: 'topRight'});
        showError($('#alamat'), 'Alamat wajib diisi');
        isValid = false;
      }

      console.log("Validasi selesai, hasil:", isValid);
      return isValid;
    }

    function handleValidationErrors(errors) {
      console.log("Menangani error validasi...");
      for (var field in errors) {
        var input = $('#' + field);
        console.log("Error pada:", field, "Pesan:", errors[field][0]);
        showError(input, errors[field][0]);
      }
    }

    function showError(input, message) {
      console.log("Menampilkan error pada input:", input.attr('id'), "Pesan:", message);
      input.addClass('is-invalid');
      input.after('<div class="invalid-feedback d-block">' + message + '</div>');
      $('html, body').animate({
        scrollTop: input.offset().top - 100
      }, 500);
    }
  });
</script>
@endpush