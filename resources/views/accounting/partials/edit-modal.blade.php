{{-- EDIT MODAL --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="fw-bold">Edit Budget Record</h4>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Basic Information --}}
                    <h5 class="fw-bold mb-3">Basic Information</h5>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">ORS No.</label>
                            <input type="text"
                                   id="edit_ors_no"
                                   name="ors_no"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">OBR No.</label>
                            <input type="text"
                                   id="edit_obr_no"
                                   name="obr_no"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">DV No.</label>
                            <input type="text"
                                   id="edit_dv_no"
                                   name="dv_no"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payee</label>
                            <input type="text"
                                   id="edit_payee"
                                   name="payee"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Budget Year</label>
                            <input type="text"
                                   id="edit_budget_year"
                                   name="budget_year"
                                   class="form-control">
                        </div>

                    </div>

                    <hr>

                    {{-- Particulars --}}
                    <h5 class="fw-bold mb-3">Particulars</h5>

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">Particulars</label>
                            <textarea class="form-control"
                                      rows="3"
                                      id="edit_particulars"
                                      name="particulars"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Particulars Remarks</label>
                            <textarea class="form-control"
                                      rows="2"
                                      id="edit_particulars_remark"
                                      name="particulars_remark"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">UACS Code</label>
                            <input type="text"
                                   id="edit_uac_codes"
                                   name="uac_codes"
                                   class="form-control">
                        </div>

                    </div>

                    <hr>

                    {{-- Financial Information --}}
                    <h5 class="fw-bold mb-3">Financial Information</h5>

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">Debit</label>
                            <input type="number"
                                   step="0.01"
                                   id="edit_debit"
                                   name="debit"
                                   class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Credit</label>
                            <input type="number"
                                   step="0.01"
                                   id="edit_credit"
                                   name="credit"
                                   class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tax (%)</label>
                            <input type="number"
                                   step="0.01"
                                   id="edit_tax_percent"
                                   name="tax_percent"
                                   class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tax Remarks</label>
                            <input type="text"
                                   id="edit_tax_remarks"
                                   name="tax_remarks"
                                   class="form-control">
                        </div>

                    </div>

                    <hr>

                    {{-- Dates --}}
                    <h5 class="fw-bold mb-3">Processing Dates</h5>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Date Received</label>
                            <input type="date"
                                   id="edit_date_received"
                                   name="date_received"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date Processed</label>
                            <input type="date"
                                   id="edit_date_processed"
                                   name="date_processed"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">OBR Date</label>
                            <input type="date"
                                   id="edit_obr_date"
                                   name="obr_date"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date Signed</label>
                            <input type="date"
                                   id="edit_date_signed"
                                   name="date_signed"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date Forwarded</label>
                            <input type="date"
                                   id="edit_date_forwarded"
                                   name="date_forwarded"
                                   class="form-control">
                        </div>

                    </div>

                    <hr>

                    {{-- Other Information --}}
                    <h5 class="fw-bold mb-3">Other Information</h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Returned Remarks</label>
                            <textarea class="form-control"
                                      rows="2"
                                      id="edit_returned_remarks"
                                      name="returned_remarks"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Signed By Accountant</label>
                            <input type="text"
                                   id="edit_signed_by_accountant"
                                   name="signed_by_accountant"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Source Month</label>
                            <input type="text"
                                   id="edit_source_month"
                                   name="source_month"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>

                            <select id="edit_status"
                                    name="status"
                                    class="form-select">

                                <option value="Pending">Pending</option>
                                <option value="Processing">Processing</option>
                                <option value="Completed">Completed</option>
                                <option value="Returned">Returned</option>

                            </select>
                        </div>

                    </div>

            </div>

            <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-success">
                        Save Changes
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>