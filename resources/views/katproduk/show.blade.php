<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModalLabel">Detail Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="show_kode">Kode</label>
                                <input type="text" class="form-control" id="show_kode" readonly>
                            </div>
                            <div class="form-group">
                                <label for="show_nama">Nama</label>
                                <input type="text" class="form-control" id="show_nama" readonly>
                            </div>
                        
                            <div class="form-group">
                                <label for="show_created_at">Created at</label>
                                <input type="text" class="form-control" id="show_created_at" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
            </form>
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
