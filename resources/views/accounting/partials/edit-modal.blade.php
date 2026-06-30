{{-- EDIT MODAL --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 90%;">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="fw-bold">Edit Accounting Record</h4>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="container-fluid">

                    {{-- ================= RECORD INFORMATION ================= --}}
                    <div class="row">

                        <div class="col-2 fw-bold fs-4">
                            Record<br>Information
                        </div>

                        <div class="col-10">

                            <div class="row g-2">

                                <div class="col-md-3">
                                    <label class="form-label">Date Received</label>
                                    <input type="date"
                                        id="edit_date_received"
                                        name="date_received"
                                        class="form-control form-control-sm">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">OBR Date</label>
                                    <input type="date"
                                        id="edit_obr_date"
                                        name="obr_date"
                                        class="form-control form-control-sm">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">OBR No.</label>
                                    <input type="text"
                                        id="edit_obr_no"
                                        name="obr_no"
                                        class="form-control form-control-sm">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">ORS No.</label>
                                    <input type="text"
                                        id="edit_ors_no"
                                        name="ors_no"
                                        class="form-control form-control-sm">
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Payee</label>
                                    <input type="text"
                                        id="edit_payee"
                                        name="payee"
                                        class="form-control form-control-sm">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Budget Year</label>
                                    <input type="text"
                                        id="edit_budget_year"
                                        name="budget_year"
                                        class="form-control form-control-sm">
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Particulars</label>
                                    <textarea id="edit_particulars"
                                            name="particulars"
                                            rows="2"
                                            class="form-control form-control-sm"></textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Particular Remarks</label>
                                    <textarea id="edit_particulars_remark"
                                            name="particulars_remark"
                                            rows="2"
                                            class="form-control form-control-sm"></textarea>
                                </div>

                            </div>

                        </div>

                    </div>

                    <hr>

                    {{-- ================= ACCOUNTING PROCESSING ================= --}}
                    <div class="row">

                        <div class="col-2 fw-bold fs-4">
                            Accounting<br>Processing
                        </div>

                        <div class="col-10">

                            <div class="row g-2">

                                <div class="col-md-3">
                                    <label class="form-label">Date Processed</label>
                                    <input type="date"
                                        id="edit_date_processed"
                                        name="date_processed"
                                        class="form-control form-control-sm">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">DV No.</label>
                                    <input type="text"
                                        id="edit_dv_no"
                                        name="dv_no"
                                        class="form-control form-control-sm">
                                </div>

                            </div>

                            <div class="border rounded p-3 mt-3 bg-light">

                                <div class="row g-2">

                                    <div id="accountingRows"></div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr>

                    {{-- ================= SIGNATURE ================= --}}
                    <div class="row">

                        <div class="col-2 fw-bold fs-4">
                            Signature
                        </div>

                        <div class="col-10">

                            <div class="row g-2">

                                <div class="col-md-4">
                                    <label class="form-label">Signed By</label>
                                    <input type="text"
                                        id="edit_signed_by_accountant"
                                        name="signed_by_accountant"
                                        class="form-control form-control-sm">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Date Signed</label>
                                    <input type="date"
                                        id="edit_date_signed"
                                        name="date_signed"
                                        class="form-control form-control-sm">
                                </div>

                            </div>

                        </div>

                    </div>

                    <hr>

                    {{-- ================= ROUTING & STATUS ================= --}}
                    <div class="row">

                        <div class="col-2 fw-bold fs-4">
                            Routing &<br>Status
                        </div>

                        <div class="col-10">

                            <div class="row g-2">

                                <div class="col-md-4">
                                    <label class="form-label">Status</label>

                                    <select id="edit_status"
                                            name="status"
                                            class="form-select form-select-sm">

                                        <option value="Pending">Pending</option>
                                        <option value="Processing">Processing</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Returned">Returned</option>

                                    </select>

                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Date Forwarded</label>
                                    <input type="date"
                                        id="edit_date_forwarded"
                                        name="date_forwarded"
                                        class="form-control form-control-sm">
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-success">
                        Save Changes
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>