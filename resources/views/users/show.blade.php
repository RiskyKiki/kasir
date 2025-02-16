<div class="modal fade" id="showUserModal" tabindex="-1" role="dialog" aria-labelledby="showUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showUserModalLabel">Detail User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"> 
                <form>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="show_username">Username</label>
                            <input type="text" class="form-control" id="show_username" readonly>
                        </div>
                        <div class="form-group">
                            <label for="show_email">Email</label>
                            <input type="email" class="form-control" id="show_email" readonly>
                        </div>
                        <div class="form-group">
                            <label for="show_role">Role</label>
                            <input type="text" class="form-control" id="show_role" readonly>
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
        $('#showUserModal').on('show.bs.modal', function() {
            $('#showUserModal .modal-body').scrollTop(0);
        });
    });
</script>
@endpush