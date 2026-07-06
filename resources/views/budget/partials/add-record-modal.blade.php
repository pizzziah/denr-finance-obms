{{-- ADD MODAL --}}
<div class="modal fade" id="addRecordModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable" style="max-width: 90%;">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="fw-bold">Add Budget Record</h4>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="addForm" action="{{ route('budget.logbook.store') }}" method="POST">
          @csrf

          <div class="container-fluid">

            {{-- ================= REQUEST INFORMATION ================= --}}
            <div class="row">
              <div class="col-2 fw-bold fs-4">Request<br>Information</div>

              <div class="col-10">
                <div class="row g-2">

                  <div class="col-md-3">
                    <label class="form-label small fw-semibold">Date Received *</label>
                    <input type="datetime-local"
                           name="date_received"
                           class="form-control form-control-sm"
                           value="{{ now()->format('Y-m-d\TH:i') }}"
                           required>
                  </div>

                  <div class="col-md-3">
                    <label class="form-label small fw-semibold">Due Date</label>
                    <input type="date" name="due_date" class="form-control form-control-sm">
                  </div>

                  <div class="col-md-3">
                    <label class="form-label small fw-semibold">ORS No.</label>
                    <input type="text"
                           name="ors_no"
                           class="form-control form-control-sm"
                           inputmode="numeric"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                  </div>

                  <div class="col-md-3">
                    <label class="form-label small fw-semibold">Status *</label>
                    <select name="status" id="add_status" class="form-select form-select-sm" required>
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

                  <div class="col-md-4">
                    <label class="form-label small fw-semibold">Issuing Office *</label>
                    <select name="issuing_office" id="add_issuing_office" class="form-select form-select-sm">
                      <option value="">Select Office</option>
                      @foreach($issuingOffices as $office)
                        <option value="{{ $office->issuing_office }}">{{ $office->issuing_office }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label small fw-semibold">Classification *</label>
                    <select name="classification" id="add_classification" class="form-select form-select-sm">
                      <option value="">Select Classification</option>
                      @foreach($classifications as $c)
                        <option value="{{ $c->classifications }}">{{ $c->classifications }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label small fw-semibold">Payee *</label>
                    <input type="text" name="payee" class="form-control form-control-sm" required>
                  </div>

                  <div class="col-md-8">
                    <label class="form-label small fw-semibold">Particulars *</label>
                    <textarea name="particulars" rows="2" class="form-control form-control-sm" required></textarea>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label small fw-semibold">Particular Remark</label>
                    <textarea name="particulars_remark" rows="2" class="form-control form-control-sm"></textarea>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label small fw-semibold">UAC Code *</label>
                    <select name="uac_codes" id="add_uac_codes" class="form-select form-select-sm">
                      <option value="">Select UAC</option>
                      @foreach($uacs as $uac)
                        <option value="{{ $uac->new_uac }}">
                          {{ $uac->new_uac }} - {{ $uac->uac_title }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label small fw-semibold">Amount *</label>
                    <div class="input-group input-group-sm">
                      <span class="input-group-text">₱</span>
                      <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="reset" form="addForm" class="btn btn-outline-secondary">Clear</button>
        <button type="submit" form="addForm" class="btn btn-success">Save</button>
      </div>

    </div>
  </div>
</div>
<template id="reviewRowTemplate">
  <div class="border rounded p-3 mb-2 review-row bg-light">

    <div class="row g-2">

      <div class="col-md-4">
        <label class="form-label small fw-semibold">Date Returned</label>
        <input type="date"
               name="review_date_returned[]"
               class="form-control form-control-sm">
      </div>

      <div class="col-md-4">
        <label class="form-label small fw-semibold">Remarks</label>
        <input type="text"
               name="review_remarks[]"
               class="form-control form-control-sm">
      </div>

      <div class="col-md-3">
        <label class="form-label small fw-semibold">Date Received</label>
        <input type="date"
               name="review_date_received[]"
               class="form-control form-control-sm">
      </div>

      <div class="col-md-1 d-flex align-items-end">
        <button type="button"
                class="btn btn-sm btn-danger w-100 btnRemoveReview">
          ✕
        </button>
      </div>

    </div>

  </div>
</template>