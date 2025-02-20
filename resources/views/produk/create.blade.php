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
                                <input type="text" class="form-control" id="kode" name="kode" readonly>
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
                              <label for="tanggal_pembelian">Tanggal Pembelian</label>
                                <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian">
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
    let today = new Date().toISOString().split('T')[0];
    $('#tanggal_pembelian').val(today);
    $('#createModal').on('show.bs.modal', function() {
    $.ajax({
      url: '{{ route('produk.newkode') }}',
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
    $('#createModal').on('hidden.bs.modal', function() {
      console.log("Modal ditutup");
      $('#createForm').trigger('reset');
      $('#tanggal_pembelian').val(today);
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').remove();
    });

    $('#hpp').on('input', function() {
      let hpp = parseFloat($(this).val()) || 0;
      $('#harga1').val(hpp + (hpp * 0.10));
      $('#harga2').val(hpp + (hpp * 0.20));
      $('#harga3').val(hpp + (hpp * 0.30));
    });

    $('#createForm').submit(function(e) {
      e.preventDefault();
      console.log("Form disubmit");

      let form = $(this);
      let formData = form.serialize();
      let url = form.attr('action');

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
          iziToast.success({
            title: 'Sukses',
            message: response.success,
            position: 'topRight'
          });
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
      let isValid = true;
      console.log("Memvalidasi form...");
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').remove();

      if ($('#nama').val().trim() === '') {
        console.log("Error: Nama wajib diisi");
        iziToast.error({ title: 'Error', message: 'Nama wajib diisi', position: 'topRight' });
        showError($('#nama'), 'Nama wajib diisi');
        isValid = false;
      }
      if ($('#tanggal_kadaluarsa').val().trim() === '') {
        console.log("Error: Tanggal kadaluarsa wajib dipilih");
        iziToast.error({ title: 'Error', message: 'Tanggal kadaluarsa wajib dipilih', position: 'topRight' });
        showError($('#tanggal_kadaluarsa'), 'tanggal_kadaluarsa wajib dipilih');
        isValid = false;
      }
      if ($('#stok').val().trim() === '' || $('#stok').val() <= 0) {
        console.log("Error: Stok wajib diisi dengan angka positif");
        iziToast.error({ title: 'Error', message: 'Stok wajib diisi dengan angka positif', position: 'topRight' });
        showError($('#stok'), 'Stok wajib diisi dengan angka positif');
        isValid = false;
      }
      if ($('#min_stok').val().trim() === '' || $('#min_stok').val() <= 0) {
        console.log("Error: Stok wajib diisi dengan angka positif");
        iziToast.error({ title: 'Error', message: 'Minimal stok wajib diisi dengan angka positif', position: 'topRight' });
        showError($('#min_stok'), 'min_Stok wajib diisi dengan angka positif');
        isValid = false;
      }
      if ($('#hpp').val().trim() === '' || $('#hpp').val() <= 0) {
        console.log("Error: HPP wajib diisi dengan angka positif");
        iziToast.error({ title: 'Error', message: 'HPP wajib diisi dengan angka positif', position: 'topRight' });
        showError($('#hpp'), 'HPP wajib diisi dengan angka positif');
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
