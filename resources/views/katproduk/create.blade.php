<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel">Tambah Kategori</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="createForm" method="POST" action="{{ route('kategori.store') }}">
          @csrf
          <div class="form-group">
            <label for="kode">Kode</label>
            <input type="text" class="form-control" id="kode" name="kode" readonly>
          </div>
          <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- Gunakan atribut form agar tombol submit memicu submit form yang sesuai -->
        <button type="submit" class="btn btn-primary" form="createForm">Create</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
  // Saat modal create akan ditampilkan, ambil kode terbaru dari server
  $('#createModal').on('show.bs.modal', function() {
    $.ajax({
      url: '{{ route('kategori.newkode') }}',
      type: 'GET',
      success: function(response) {
        console.log("Kode terbaru didapat:", response.kodeTerbaru);
        $('#kode').val(response.kodeTerbaru);
      },
      error: function(xhr) {
        console.log("Gagal mengambil kode terbaru:", xhr);
      }
    });
  });

  // Reset form dan hapus error saat modal ditutup
  $('#createModal').on('hidden.bs.modal', function() {
    $('#createForm').trigger('reset');
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
  });

  // Tangani submit form create dengan validasi dan konfirmasi
  $('#createForm').submit(function(e) {
    e.preventDefault();
    var form = $(this);
    var formData = form.serialize();
    var url = form.attr('action');

    if (!validateForm()) {
      return;
    }

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
        iziToast.success({title: 'Sukses', message: response.success, position: 'topRight'});
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

  function validateForm() {
    var isValid = true;
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();

    if ($('#kode').val().trim() === '') {
      iziToast.error({title: 'Error', message: 'Kode wajib diisi', position: 'topRight'});
      showError($('#kode'), 'Kode wajib diisi');
      isValid = false;
    }
    if ($('#nama').val().trim() === '') {
      iziToast.error({title: 'Error', message: 'Nama wajib diisi', position: 'topRight'});
      showError($('#nama'), 'Nama wajib diisi');
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
    $('html, body').animate({
      scrollTop: input.offset().top - 100
    }, 500);
  }
});
</script>
@endpush
