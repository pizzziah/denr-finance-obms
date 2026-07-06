{{-- ADD RECORD MODAL --}}
<div class="modal fade" id="addRecordModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width:90%;">
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

                            <div class="col-2 fw-bold fs-4 lh-1">
                                Request<br>Information
                            </div>

                            {{-- LEFT COLUMN --}}
                            <div class="col-5">

                                <div class="row mb-2 align-items-center">
                                    <div class="col-5 fw-bold">Date Received:
                                        <span class="text-danger">*</span>
                                    </div>
                                    <div class="col-7">
                                        <input type="datetime-local" name="date_received" class="form-control form-control-sm"  value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                    </div>
                                </div>

                                <div class="row mb-2 align-items-center">
                                    <div class="col-5 fw-bold"> Payee:
                                        <span class="text-danger">*</span>
                                    </div>
                                    <div class="col-7">
                                        <input type="text" name="payee"  maxlength="255"  class="form-control form-control-sm"  required>
                                    </div>
                                </div>
                                 <div class="row mb-2">
                                    <div class="col-5 fw-bold">Particulars:
                                        <span class="text-danger">*</span>
                                    </div>

                                    <div class="col-7">
                                        <textarea name="particulars" rows="2" maxlength="500" class="form-control form-control-sm" required></textarea>
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-5 fw-bold">UAC Code:
                                    </div>
                                    <div class="col-7">
                                        <select name="uac_codes"  class="form-select form-select-sm" required>
                                            <option value=""> Select UAC</option>
                                            @foreach($uacs as $uac)
                                                <option value="{{ $uac->new_uac }}">
                                                    {{ $uac->new_uac }}
                                                    -
                                                    {{ $uac->uac_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-5 fw-bold"> Amount:
                                        <span class="text-danger">*</span>
                                    </div>

                                    <div class="col-7">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"> ₱ </span>
                                            <input type="number"  name="amount" min="0" step="0.01" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT COLUMN --}}
                            <div class="col-5">
                                <div class="row mb-2 align-items-center">
                                    <div class="col-5 fw-bold"> Issuing Office:
                                        <span class="text-danger">*</span>
                                    </div>
                                    <div class="col-7">
                                        <select name="issuing_office"  class="form-select form-select-sm"  required>
                                            <option value=""> Select Office </option>
                                            @foreach($issuingOffices as $office)
                                                <option value="{{ $office->issuing_office }}"> {{ $office->issuing_office }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-2 align-items-center">
                                    <div class="col-5 fw-bold">Classification:
                                        <span class="text-danger">*</span>
                                    </div>

                                    <div class="col-7">
                                        <select name="classification" class="form-select form-select-sm" required>
                                            <option value=""> Select Classification</option>
                                            @foreach($classifications as $classification)
                                                <option value="{{ $classification->classifications }}"> {{ $classification->classifications }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-5 fw-bold"> Particulars Remark: </div>

                                    <div class="col-7">
                                        <textarea name="particulars_remark" rows="2"  class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                    
                                 <div class="row mb-2 align-items-center">
                                    <div class="col-5 fw-bold">
                                        Due Date:
                                    </div>
                                    <div class="col-7">
                                        <input type="date" name="due_date" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        {{-- ================= REVIEW PROCESSING ================= --}}
                        <div class="row">

                            <div class="col-2 fw-bold fs-4 lh-1">
                                Review<br>Processing
                            </div>

                            {{-- LEFT COLUMN --}}
                            <div class="col-5">

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Status:
                                        <span class="text-danger">*</span>
                                    </div>

                                    <div class="col-7">

                                        <select
                                            name="status"
                                            class="form-select form-select-sm"
                                            required>

                                            <option value="Pending">Pending</option>
                                            <option value="Processing">Processing</option>
                                            <option value="For Review">For Review</option>
                                            <option value="For Obligation" selected>For Obligation</option>
                                            <option value="Forwarded to Accounting">Forwarded to Accounting</option>
                                            <option value="Returned">Returned</option>
                                            <option value="Paid">Paid</option>
                                            <option value="Canceled">Canceled</option>

                                        </select>

                                    </div>

                                </div>

                            </div>

                            {{-- RIGHT COLUMN --}}
                            <div class="col-5">

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Date Returned:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="date"
                                            name="date_returned_1"
                                            class="form-control form-control-sm">

                                    </div>

                                </div>

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Remarks:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="text"
                                            name="remarks_1"
                                            maxlength="255"
                                            class="form-control form-control-sm">

                                    </div>

                                </div>

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Date Received:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="date"
                                            name="date_received_1"
                                            class="form-control form-control-sm">

                                    </div>

                                </div>

                            </div>

                        </div>

                        <hr>

                        {{-- ================= OBLIGATION PROCESSING ================= --}}
                        <div class="row">

                            <div class="col-2 fw-bold fs-4 lh-1">
                                Obligation<br>Processing
                            </div>

                            {{-- LEFT COLUMN --}}
                            <div class="col-5">

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        ORS No.:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="text"
                                            name="ors_no"
                                            class="form-control form-control-sm"
                                            inputmode="numeric"
                                            pattern="[0-9]*"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'')">

                                    </div>

                                </div>

                            </div>

                            {{-- RIGHT COLUMN --}}
                            <div class="col-5">

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Date Forwarded:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="date"
                                            name="date_forwarded_1"
                                            class="form-control form-control-sm">

                                    </div>

                                </div>

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Date ORS Received:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="date"
                                            name="date_ors_received"
                                            class="form-control form-control-sm">

                                    </div>

                                </div>

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Date Returned:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="date"
                                            name="date_returned_2"
                                            class="form-control form-control-sm">

                                    </div>

                                </div>

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Remarks:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="text"
                                            name="remarks_2"
                                            maxlength="255"
                                            class="form-control form-control-sm">

                                    </div>

                                </div>

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Date Received:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="date"
                                            name="date_received_2"
                                            class="form-control form-control-sm">

                                    </div>

                                </div>

                            </div>

                        </div>

                        <hr>
                        {{-- ================= FORWARDED TO ACCOUNTING ================= --}}
                        <div class="row">

                            <div class="col-2 fw-bold fs-4 lh-1">
                                Forwarded<br>to Accounting
                            </div>

                            {{-- LEFT COLUMN --}}
                            <div class="col-5">

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Date Forwarded:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="date"
                                            name="date_forwarded_accounting"
                                            class="form-control form-control-sm">

                                    </div>

                                </div>

                            </div>

                            {{-- RIGHT COLUMN --}}
                            <div class="col-5">

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Final Remarks:
                                    </div>

                                    <div class="col-7">

                                        <textarea
                                            name="final_remarks"
                                            rows="2"
                                            class="form-control form-control-sm"></textarea>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <hr>

                        {{-- ================= PROCESSING METRICS ================= --}}
                        <div class="row">

                            <div class="col-2 fw-bold fs-4 lh-1">
                                Processing<br>Metrics
                            </div>

                            {{-- LEFT COLUMN --}}
                            <div class="col-5">

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Total Time in Budget:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="text"
                                            name="total_time_budget"
                                            class="form-control form-control-sm"
                                            value="00:00:00"
                                            readonly>

                                    </div>

                                </div>

                            </div>

                            {{-- RIGHT COLUMN --}}
                            <div class="col-5">

                                <div class="row mb-2 align-items-center">

                                    <div class="col-5 fw-bold">
                                        Total Time:
                                    </div>

                                    <div class="col-7">

                                        <input
                                            type="text"
                                            name="total_time"
                                            class="form-control form-control-sm"
                                            value="00:00:00"
                                            readonly>

                                    </div>

                                </div>

                            </div>

                        </div>

                                        </div> {{-- container-fluid --}}
                                    </div> {{-- modal-body --}}

                                    {{-- FOOTER --}}
                                    <div class="modal-footer">

                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">

                                            Cancel

                                        </button>

                                        <button
                                            type="submit"
                                            class="btn btn-success">

                                            <i class="bi bi-check-circle"></i>
                                            Save Record

                                        </button>

                                    </div>

                                </form>

                            </div>
                        </div>
                        </div>