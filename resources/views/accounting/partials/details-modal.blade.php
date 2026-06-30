{{-- DETAILS MODAL --}}
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 95%;">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="fw-bold">View Record</h4>

                <button class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="detailsBody">

                <!-- AJAX content goes here -->

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="printDetails()">
                    <i class="bi bi-printer"></i> Print
                </button>
                <button class="btn btn-success"
                        data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>
    </div>
</div>