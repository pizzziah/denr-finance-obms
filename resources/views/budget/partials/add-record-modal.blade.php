{{-- ADD RECORD MODAL --}}
<div class="modal fade" id="addRecordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 90%;">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="fw-bold">Add Budget Record</h4>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
            <form action="{{ route('budget.logbook.store') }}" method="POST">
                @csrf

                    <div class="container-fluid">
                        {{-- ================= REQUEST INFORMATION ================= --}}
                        <div class="row">

                            <div class="col-2 fw-bold fs-4">
                                Request<br>Information
                            </div>

                            <div class="col-10">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Date Received</strong><span class="text-danger">*</span></label>
                                        <input type="date" name="date_received" class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Issuing Office</strong><span class="text-danger">*</span></label>
                                        <select name="issuing_office" class="form-select form-select-sm" required>
                                            <option value="">Select Office</option>
                                            @foreach($issuingOffices as $office)
                                                <option value="{{ $office->issuing_office }}">
                                                    {{ $office->issuing_office }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><strong>Payee<strong> <span class="text-danger">*</span></label>
                                        <input type="text" name="payee" class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Classification</strong> <span class="text-danger">*</span></label>
                                        <select name="classification" class="form-select form-select-sm" required>
                                            <option value="">Select Classification</option>
                                            @foreach($classifications as $classification)
                                                <option value="{{ $classification->classifications }}">
                                                    {{ $classification->classifications }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label"><strong>UACS Code</strong></label>
                                        <select name="uac_codes" class="form-select form-select-sm">
                                            <option value="">Select UACS Code</option>
                                            @foreach($uacs as $uac)
                                                <option value="{{ $uac->new_uac }}">
                                                    {{ $uac->new_uac }}
                                                    -
                                                    {{ $uac->uac_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"> <strong> Amount </strong><span class="text-danger">*</span></label>
                                        <input type="number"step="0.01" name="amount" class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label"><strong>Particulars </strong><span class="text-danger">*</span></label>
                                        <textarea name="particulars"rows="2"class="form-control form-control-sm" required></textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label"><strong> Particulars Remark</strong></label>
                                        <textarea name="particulars_remark"rows="2"class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        {{-- ================= REVIEW PROCESSING ================= --}}
                        <div class="row">

                            <div class="col-2 fw-bold fs-4">
                                Review<br>Processing
                            </div>

                            <div class="col-10">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Status</strong></label>
                                        <select name="status"
                                                class="form-select form-select-sm">
                                            <option value="Pending">Pending</option>
                                            <option value="Processing">Processing</option>
                                            <option value="For Review">For Review</option>
                                            <option value="For Obligation" selected>For Obligation</option>
                                            <option value="Forwarded to Accounting">Forwarded to Accounting</option>
                                            <option value="Returned">Returned</option>
                                            <option value="Canceled">Canceled</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Date Returned</strong></label>
                                        <input type="date" name="date_returned_1" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Date Received</strong></label>
                                        <input type="date" name="date_received_1" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label"><strong>Remarks</strong></label>
                                        <textarea name="remarks_1" rows="2" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        {{-- ================= ROUTING & PROCESSING ================= --}}
                        <div class="row">
                            <div class="col-2 fw-bold fs-4">
                                Routing &<br>Processing
                            </div>

                            <div class="col-10">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Date Forwarded to Accounting</strong></label>
                                        <input type="date" name="date_forwarded_accounting" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Date Returned</strong></label>
                                        <input type="date" name="date_returned_2" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Date Received</strong></label>
                                        <input type="date" name="date_received_2" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label"><strong>Remarks<strong></label>
                                        <textarea name="remarks_2" rows="2" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        {{-- ================= STATUS & METRICS ================= --}}
                        <div class="row">

                            <div class="col-2 fw-bold fs-4">
                                Status &<br>Metrics
                            </div>

                            <div class="col-10">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Status</strong></label>
                                        <select name="status"
                                                class="form-select form-select-sm">
                                            <option value="Pending">Pending</option>
                                            <option value="Processing">Processing</option>
                                            <option value="For Review">For Review</option>
                                            <option value="For Obligation">For Obligation</option>
                                            <option value="Forwarded to Accounting">Forwarded to Accounting</option>
                                            <option value="Returned">Returned</option>
                                            <option value="Paid">Paid</option>
                                            <option value="Canceled">Canceled</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Total Time in Budget</strong></label>
                                        <input type="text"  name="total_time_budget" value="00:00:00" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Total Time</strong></label>
                                        <input type="text" name="total_time" value="00:00:00" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label"><strong>Final Remarks</strong></label>
                                        <textarea name="final_remarks" rows="3" class="form-control form-control-sm"></textarea>
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

                    <button type="submit"
                            class="btn btn-success">
                        <i class="bi bi-file-earmark-plus"></i>
                        Add Record
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>