<!-- ADD RECORD MODAL -->
<div class="modal fade" id="addRecordModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="fw-bold">Add Budget Record</h4>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="addForm"
                      method="POST"
                      action="{{ route('budget.logbook.store') }}">
                    @csrf

                    {{-- Record Information --}}
                    <h5 class="fw-bold mb-3">Record Information</h5>

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">ORS No.</label>
                            <input type="text"
                                   name="ors_no"
                                   class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Date Received</label>
                            <input type="date"
                                   name="date_received"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payee</label>
                            <input type="text"
                                   name="payee"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Issuing Office</label>
                            <input type="text"
                                   name="issuing_office"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Classification</label>
                            <input type="text"
                                   name="classification"
                                   class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Particulars</label>
                            <textarea name="particulars"
                                      rows="3"
                                      class="form-control"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">UACS Code</label>
                            <input type="text"
                                   name="uac_codes"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Amount</label>
                            <input type="number"
                                   step="0.01"
                                   name="amount"
                                   class="form-control">
                        </div>

                    </div>

                    <hr>

                    {{-- Returned to End User --}}
                    <h5 class="fw-bold mb-3">Returned to End User</h5>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Date Returned</label>
                            <input type="date"
                                   name="date_returned_1"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date Received</label>
                            <input type="date"
                                   name="date_received_1"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Remarks</label>
                            <input type="text"
                                   name="remarks_1"
                                   class="form-control">
                        </div>

                    </div>

                    <hr>

                    {{-- Forwarded --}}
                    <h5 class="fw-bold mb-3">Forwarded</h5>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Date Forwarded</label>
                            <input type="date"
                                   name="date_forwarded_1"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date ORS Received</label>
                            <input type="date"
                                   name="date_ors_received"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Remarks</label>
                            <input type="text"
                                   name="remarks_2"
                                   class="form-control">
                        </div>

                    </div>

                    <hr>

                    {{-- Returned by Accounting --}}
                    <h5 class="fw-bold mb-3">Returned by Accounting</h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Date Returned</label>
                            <input type="date"
                                   name="date_returned_2"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date Received</label>
                            <input type="date"
                                   name="date_received_2"
                                   class="form-control">
                        </div>

                    </div>

                    <hr>

                    {{-- Forwarded to Accounting --}}
                    <h5 class="fw-bold mb-3">Forwarded to Accounting</h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Date Forwarded to Accounting</label>
                            <input type="date"
                                   name="date_forwarded_accounting"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Total Time in Budget</label>
                            <input type="text"
                                   name="total_time_budget"
                                   class="form-control">
                        </div>

                    </div>

                    <hr>

                    {{-- Status --}}
                    <h5 class="fw-bold mb-3">Status</h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Status</label>

                            <select name="status" class="form-select">
                                <option value="Pending" selected>Pending</option>
                                <option value="Processing">Processing</option>
                                <option value="For Review">For Review</option>
                                <option value="For Obligation">For Obligation</option>
                                <option value="Forwarded to Accounting">Forwarded to Accounting</option>
                                <option value="Returned">Returned</option>
                                <option value="Canceled">Canceled</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Total Time</label>
                            <input type="text"
                                   name="total_time"
                                   class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Final Remarks</label>
                            <textarea name="final_remarks"
                                      rows="3"
                                      class="form-control"></textarea>
                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="submit"
                        form="addForm"
                        class="btn btn-success">
                    Add Record
                </button>
            </div>

        </div>
    </div>
</div>