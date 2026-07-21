<div class="modal fade" id="actionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white" id="actionModalHeader">
                <h5 class="modal-title" id="actionModalTitle">
                    <i class="bi bi-trash me-2"></i>
                    Delete Record
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <form id="actionForm" method="POST">
                @csrf

                <div id="actionMethod">
                    @method('DELETE')
                </div>

                <div class="modal-body">

                    <p class="mb-2" id="actionModalMessage">
                        Are you sure you want to delete this transaction?
                    </p>

                    <div class="fw-bold text-danger">
                        DV No.:
                        <span id="deleteDvNoText"></span>
                    </div>

                    <small class="text-muted" id="actionModalNote">
                        This action cannot be undone.
                    </small>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            id="actionSubmitBtn"
                            class="btn btn-danger">
                        Delete
                    </button>

                </div>
            </form>

        </div>
    </div>
</div>