@if(!$isLocked)
<div class="modal fade" id="addSummaryModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow border-0">
      <form id="addSummaryForm" method="POST" action="{{ route('accounting.quarterly-summary.store') }}">
        @csrf
        <input type="hidden" name="target_quarter" value="{{ $selectedQuarter }}">
        <input type="hidden" name="target_year" value="{{ $selectedYear }}">        
        
        <div class="modal-header bg-dark text-white py-3">
          <h5 class="fw-bold mb-0 text-white"><i class="bi bi-file-earmark-plus me-2"></i>Add Quarterly Summary Entry</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="fw-bold small mb-1">Date Processed <span class="text-danger">*</span></label>
              <input type="date" name="date_processed" class="form-control form-control-sm shadow-sm" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
              <label class="fw-bold small mb-1">DV/NCA/NTA Number <span class="text-danger">*</span></label>
              <input type="text" name="particulars" class="form-control form-control-sm shadow-sm" placeholder="Enter Reference Number" required>
            </div>

            <div class="col-12">
              <label class="fw-bold small mb-2 d-block">Transaction Type & Value <span class="fw-medium text-danger">*</span></label>
              <div class="p-3 border rounded bg-light d-flex flex-column gap-3 shadow-sm">
                
                {{-- ADJUSTMENT ROW --}}
                <div class="d-flex align-items-center gap-3">
                  <div class="form-check m-0" style="min-width: 160px;">
                    <input class="form-check-input tx-type-radio" type="radio" name="transaction_type" id="type_adjustment" value="Adjustment" checked required>
                    <label class="form-check-label small fw-bold" style="color: #7909FF;" for="type_adjustment">Adjustment</label>
                  </div>
                  <div class="input-group input-group-sm flex-grow-1">
                    <span class="input-group-text bg-white">₱</span>
                    <input type="number" name="amount" id="amount_input" step="0.01" class="form-control font-monospace" placeholder="0.00" required>
                  </div>
                </div>

                {{-- SIGNED DV ROW --}}
                <div class="d-flex align-items-center gap-3">
                  <div class="form-check m-0" style="min-width: 160px;">
                    <input class="form-check-input tx-type-radio" type="radio" name="transaction_type" id="type_signed_dv" value="Signed DV">
                    <label class="form-check-label small fw-bold" style="color: #20c997;" for="type_signed_dv">Signed DV</label>
                  </div>
                </div>

                {{-- RECEIVED ROW --}}
                <div class="d-flex align-items-center gap-3">
                  <div class="form-check m-0" style="min-width: 160px;">
                    <input class="form-check-input tx-type-radio" type="radio" name="transaction_type" id="type_received" value="NCA/NTA Received">
                    <label class="form-check-label small fw-bold" style="color: #9D6B0B;" for="type_received">NCA/NTA Received</label>
                  </div>
                </div>

                {{-- DOWNLOADED ROW --}}
                <div class="d-flex align-items-center gap-3">
                  <div class="form-check m-0" style="min-width: 160px;">
                    <input class="form-check-input tx-type-radio" type="radio" name="transaction_type" id="type_downloaded" value="NCA/NTA Downloaded">                    
                    <label class="form-check-label small fw-bold" style="color: var(--error);" for="type_downloaded">NCA/NTA Downloaded</label>
                  </div>
                </div>

              </div>
              <div class="mt-2 tiny font-monospace text-muted px-1">
                Live Value Preview: <span id="amount_preview" class="fw-bold text-dark">₱0.00</span>
              </div>
            </div>

            <div class="col-md-6">
              <label class="fw-bold small mb-1">EMDS Date</label>
              <input type="date" name="emds_date" class="form-control form-control-sm shadow-sm">
            </div>
            <div class="col-md-6">
              <label class="fw-bold small mb-1">ADA Check No.</label>
              <input type="text" name="ada_no" class="form-control form-control-sm shadow-sm" placeholder="Enter ADA Check Number">
            </div>

            <div class="col-12">
              <label class="fw-bold small mb-1">Remarks</label>
              <textarea name="remarks" class="form-control form-control-sm shadow-sm" rows="2" placeholder="Enter Remarks (if any)"></textarea>
            </div>
          </div>
        </div>
        <p id="debugType" class="text-danger fw-bold"></p>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" id="cancelAddBtn">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm">Save Entry</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('addSummaryForm');
    const amountInput = document.getElementById('amount_input');
    const amountPreview = document.getElementById('amount_preview');
    const radios = document.querySelectorAll('.tx-type-radio');

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            const inputGroup = amountInput.closest('.input-group');
            this.closest('.d-flex').appendChild(inputGroup);
            
            // Explicitly force the name and active parameters on shift
            amountInput.setAttribute('name', 'amount');
            amountInput.disabled = false; 
            amountInput.focus();
        });
    });

    if (amountInput && amountPreview) {
      amountInput.addEventListener('input', function() {
        const val = parseFloat(this.value);
        amountPreview.textContent = !isNaN(val) ? new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', signDisplay: 'always' }).format(val) : '₱0.00';
      });
    }

    radios.forEach(radio => {
      radio.addEventListener('change', function() {
        const inputGroup = amountInput.closest('.input-group');
        this.closest('.d-flex').appendChild(inputGroup);
        
        amountInput.disabled = false;
        amountInput.focus();
        });
    });
});
</script>
@endif