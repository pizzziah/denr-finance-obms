<div class="modal fade" id="editSummaryModal{{ $rowId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog text-start">
    <div class="modal-content shadow border-0">
      <form id="editSummaryForm_{{ $rowId }}" method="POST" action="{{ route('accounting.quarterly-summary.update', ['id' => $rowId]) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="target_quarter" value="{{ $selectedQuarter }}">
        <input type="hidden" name="target_year" value="{{ $selectedYear }}">
        
        <div class="modal-header text-white" style="background-color: var(--primary);">
          <h5 class="fw-bold mb-0 text-white"><i class="bi bi-pencil-square me-2"></i>Modify Ledger Entry</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="fw-bold small mb-1">Date Processed</label>
              @php 
                try { $formattedProcessedDate = !empty($record->date_processed) ? \Carbon\Carbon::parse($record->date_processed)->format('Y-m-d') : ''; } 
                catch(\Exception $e) { $formattedProcessedDate = ''; }
              @endphp
              <input type="date" name="date_processed" class="form-control form-control-sm shadow-sm" value="{{ $formattedProcessedDate }}" required>
            </div>

          <div class="col-md-6">
            <label class="fw-bold small mb-1">DV/NCA/NTA Number</label>
            <input type="text"
                  name="dv_no"
                  class="form-control form-control-sm shadow-sm"
                  value="{{ $record->dv_no }}"
                  required>
          </div>

          <div class="col-12">
            <label class="fw-bold small mb-1">Particulars</label>
            <input type="text"
                  name="particulars"
                  class="form-control form-control-sm shadow-sm"
                  value="{{ $record->particulars }}">
          </div>

            <div class="col-12">
              <label class="fw-bold small mb-2 d-block">Transaction Type & Value</label>
              <div class="p-3 border rounded bg-light d-flex flex-column gap-3 shadow-sm">
                
                {{-- ADJUSTMENT ROW --}}
                <div class="d-flex align-items-center gap-3">
                  <div class="form-check m-0" style="min-width: 160px;">
                    <input class="form-check-input tx-type-radio-{{ $rowId }}" type="radio" name="transaction_type" id="type_adjustment_{{ $rowId }}" value="Adjustment" @checked($record->transaction_type === 'Adjustment') required>
                    <label class="form-check-label small fw-bold" style="color: #7909FF;" for="type_adjustment_{{ $rowId }}">Adjustment</label>
                  </div>
                  <div class="input-group input-group-sm flex-grow-1 dynamic-input-group-{{ $rowId }} @if($record->transaction_type !== 'Adjustment') d-none @endif">
                    <span class="input-group-text bg-white">₱</span>
                    <input type="number" name="amount" id="amount_input_adj_{{ $rowId }}" step="0.01" class="form-control font-monospace" value="{{ $rawAmount }}" @if($record->transaction_type === 'Adjustment') required @else disabled @endif>
                  </div>
                </div>

                {{-- SIGNED DV ROW --}}
                <div class="d-flex align-items-center gap-3">
                  <div class="form-check m-0" style="min-width: 160px;">
                    <input class="form-check-input tx-type-radio-{{ $rowId }}" type="radio" name="transaction_type" id="type_signed_dv_{{ $rowId }}" value="Signed DV" @checked($record->transaction_type === 'Signed DV')>
                    <label class="form-check-label small fw-bold" style="color: #20c997;" for="type_signed_dv_{{ $rowId }}">Signed DV</label>
                  </div>
                  <div class="input-group input-group-sm flex-grow-1 dynamic-input-group-{{ $rowId }} @if($record->transaction_type !== 'Signed DV') d-none @endif">
                    <span class="input-group-text bg-white">₱</span>
                    <input type="number" name="amount" id="amount_input_dv_{{ $rowId }}" step="0.01" class="form-control font-monospace" value="{{ $rawAmount }}" @if($record->transaction_type === 'Signed DV') required @else disabled @endif>
                  </div>
                </div>

                {{-- RECEIVED ROW --}}
                <div class="d-flex align-items-center gap-3">
                  <div class="form-check m-0" style="min-width: 160px;">
                    <input class="form-check-input tx-type-radio-{{ $rowId }}" type="radio" name="transaction_type" id="type_received_{{ $rowId }}" value="NCA/NTA Received" @checked($record->transaction_type === 'NCA/NTA Received')>
                    <label class="form-check-label small fw-bold" style="color: #9D6B0B;" for="type_received_{{ $rowId }}">NCA/NTA Received</label>
                  </div>
                  <div class="input-group input-group-sm flex-grow-1 dynamic-input-group-{{ $rowId }} @if($record->transaction_type !== 'NCA/NTA Received') d-none @endif">
                    <span class="input-group-text bg-white">₱</span>
                    <input type="number" name="amount" id="amount_input_rec_{{ $rowId }}" step="0.01" class="form-control font-monospace" value="{{ $rawAmount }}" @if($record->transaction_type === 'NCA/NTA Received') required @else disabled @endif>
                  </div>
                </div>

                {{-- DOWNLOADED ROW --}}
                <div class="d-flex align-items-center gap-3">
                  <div class="form-check m-0" style="min-width: 160px;">
                    <input class="form-check-input tx-type-radio-{{ $rowId }}" type="radio" name="transaction_type" id="type_downloaded_{{ $rowId }}" value="NCA/NTA Downloaded" @checked($record->transaction_type === 'NCA/NTA Downloaded')>
                    <label class="form-check-label small fw-bold" style="color: var(--error);" for="type_downloaded_{{ $rowId }}">NCA/NTA Downloaded</label>
                  </div>
                  <div class="input-group input-group-sm flex-grow-1 dynamic-input-group-{{ $rowId }} @if($record->transaction_type !== 'NCA/NTA Downloaded') d-none @endif">
                    <span class="input-group-text bg-white">₱</span>
                    <input type="number" name="amount" id="amount_input_down_{{ $rowId }}" step="0.01" class="form-control font-monospace" value="{{ $rawAmount }}" @if($record->transaction_type === 'NCA/NTA Downloaded') required @else disabled @endif>
                  </div>
                </div>

              </div>
              <div class="mt-2 tiny font-monospace text-muted px-1">
                Live Preview: <span id="amount_preview_{{ $rowId }}" class="fw-bold text-dark">₱{{ number_format((float)$rawAmount, 2) }}</span>
              </div>
            </div>

            <div class="col-md-6">
              <label class="fw-bold small mb-1">EMDS Date</label>
              @php 
                try { $formattedDate = !empty($record->emds_date) ? \Carbon\Carbon::parse($record->emds_date)->format('Y-m-d') : ''; } 
                catch(\Exception $e) { $formattedDate = ''; }
              @endphp
              <input type="date" name="emds_date" class="form-control form-control-sm shadow-sm" value="{{ $formattedDate }}">
            </div>

            <div class="col-md-6">
              <label class="fw-bold small mb-1">ADA Check No.</label>
              <input type="text" name="ada_no" class="form-control form-control-sm shadow-sm" value="{{ $record->ada_no }}" placeholder="Enter tracking instrument id...">
            </div>

            <div class="col-12">
              <label class="fw-bold small mb-1">Remarks</label>
              <textarea name="remarks" class="form-control form-control-sm shadow-sm" rows="2" placeholder="Optional updates...">{{ $record->remarks }}</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <x-button type="button" variant="secondary" data-bs-dismiss="modal" id="cancelEditBtn_{{ $rowId }}">Cancel</x-button>
          <x-button type="submit" variant="primary" >Save Changes</x-button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editCancelConfirmModal_{{ $rowId }}" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow">
      <div class="modal-body text-center p-4">
        <div class="text-warning mb-3"><i class="bi bi-exclamation-circle-fill display-5"></i></div>
        <h5 class="fw-bold">Discard Changes?</h5>
        <p class="small text-muted mb-4">You have modified this record. Discard edits?</p>
        <div class="d-flex gap-2 justify-content-center">
          <button type="button" class="btn btn-sm btn-light border w-100" id="keepEditingEditBtn_{{ $rowId }}">Keep</button>
          <button type="button" class="btn btn-sm btn-warning w-100" id="discardEditChangesBtn_{{ $rowId }}">Discard</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainModalEl = document.getElementById('editSummaryModal{{ $rowId }}');
    const cancelModalEl = document.getElementById('editCancelConfirmModal_{{ $rowId }}');
    const form = document.getElementById('editSummaryForm_{{ $rowId }}');
    const radios = document.querySelectorAll('.tx-type-radio-{{ $rowId }}');
    let originalFormDataString = "";

    function getFormSnapshot() {
        return new URLSearchParams(new FormData(form)).toString();
    }

    mainModalEl.addEventListener('shown.bs.modal', function () {
        originalFormDataString = getFormSnapshot();
    });

    function updatePreview() {
        let editAmountPreview = document.getElementById('amount_preview_{{ $rowId }}');
        const val = parseFloat(this.value);
        if (editAmountPreview) {
            editAmountPreview.textContent = !isNaN(val) ? new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(val) : '₱0.00';
        }
    }

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.dynamic-input-group-{{ $rowId }}').forEach(group => {
                group.classList.add('d-none');
                const input = group.querySelector('input[type="number"]');
                if (input) {
                    input.disabled = true;
                    input.removeAttribute('required');
                    input.removeEventListener('input', updatePreview);
                }
            });

            const currentContainer = this.closest('.d-flex');
            const currentGroup = currentContainer.querySelector('.dynamic-input-group-{{ $rowId }}');
            if (currentGroup) {
                currentGroup.classList.remove('d-none');
                const activeInput = currentGroup.querySelector('input[type="number"]');
                if (activeInput) {
                    activeInput.disabled = false;
                    activeInput.setAttribute('required', 'required');
                    activeInput.focus();
                    activeInput.addEventListener('input', updatePreview);
                    updatePreview.call(activeInput);
                }
            }
        });
    });

    const activeInput = document.querySelector('.dynamic-input-group-{{ $rowId }}:not(.d-none) input[type="number"]');
    if (activeInput) {
        activeInput.addEventListener('input', updatePreview);
    }

    const cancelBtn = document.getElementById('cancelEditBtn_{{ $rowId }}');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const currentFormDataString = getFormSnapshot();
            const isFormDirty = (originalFormDataString !== currentFormDataString);
            
            const bsCancelModal = bootstrap.Modal.getOrCreateInstance(cancelModalEl);
            const bsEditModal = bootstrap.Modal.getOrCreateInstance(mainModalEl);

            if (isFormDirty) {
                bsCancelModal.show();
            } else {
                bsEditModal.hide();
            }
        });
    }

    document.getElementById('keepEditingEditBtn_{{ $rowId }}').addEventListener('click', () => {
        bootstrap.Modal.getOrCreateInstance(cancelModalEl).hide();
    });
    
    document.getElementById('discardEditChangesBtn_{{ $rowId }}').addEventListener('click', function() {
        bootstrap.Modal.getOrCreateInstance(cancelModalEl).hide();
        bootstrap.Modal.getOrCreateInstance(mainModalEl).hide();
        form.reset(); 
    });
});
</script>