<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Menggunakan modal besar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModalLabel">Detail Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="show_kode">Kode</label>
                                <input type="text" class="form-control" id="show_kode" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_nama">Nama</label>
                                <input type="text" class="form-control" id="show_nama" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_kategori">Kategori</label>
                                <input type="text" class="form-control" id="show_kategori" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_tanggal_pembelian">Tanggal Pembelian</label>
                                <input type="text" class="form-control" id="show_tanggal_pembelian" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_tanggal_kadaluarsa">Tanggal Kadaluarsa</label>
                                <input type="text" class="form-control" id="show_tanggal_kadaluarsa" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="show_stok">Stok</label>
                                <input type="text" class="form-control" id="show_stok" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_min_stok">Minimal Stok</label>
                                <input type="text" class="form-control" id="show_min_stok" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_hpp">Harga Pokok Penjualan (HPP)</label>
                                <input type="text" class="form-control" id="show_hpp" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_harga1">Harga 1</label>
                                <input type="text" class="form-control" id="show_harga1" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_harga2">Harga 2</label>
                                <input type="text" class="form-control" id="show_harga2" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="show_harga3">Harga 3</label>
                                <input type="text" class="form-control" id="show_harga3" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_created_at">Created at</label>
                                <input type="text" class="form-control" id="show_created_at" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_created_by">Creator</label>
                                <input type="text" class="form-control" id="show_created_by" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_updated_at">Updated at</label>
                                <input type="text" class="form-control" id="show_updated_at" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_updated_by">Updater</label>
                                <input type="text" class="form-control" id="show_updated_by" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#showModal').on('show.bs.modal', function() {
                $('#showModal .modal-body').scrollTop(0);
            });
        });
    </script>
@endpush
