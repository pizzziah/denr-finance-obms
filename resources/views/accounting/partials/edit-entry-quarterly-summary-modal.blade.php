<div class="modal fade" id="editSummaryModal{{ $rowId }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog text-start">
    <div class="modal-content">
      <form method="POST" action="{{ route('accounting.quarterly-summary.update', ['id' => $rowId]) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="target_quarter" value="{{ $selectedQuarter }}">
        
        <div class="modal-header">
          <h5 class="fw-bold mb-0">Modify Ledger Entry</h5>
        </div>
        
        <div class="modal-body">
          {{-- DATE INPUT --}}
          <div class="mb-3">
            <label class="fw-bold">Date</label>
            @php 
              try {
                $formattedDate = !empty($record->emds_date) ? \Carbon\Carbon::parse($record->emds_date)->format('n/j/Y H:i:s') : '';
              } catch(\Exception $e) {
                $formattedDate = ''; 
              }
            @endphp
            <input type="date" name="emds_date" class="form-control" value="{{ $formattedDate }}" required>
          </div>

          {{-- PARTICULARS INPUT --}}
          <div class="mb-3">
            <label class="fw-bold">Particulars</label>
            <textarea name="particulars" class="form-control" rows="2" required>{{ $record->particulars }}</textarea>
          </div>

          {{-- AMOUNT INPUT WITH LIVE VISUAL FORMATTER --}}
          <div class="mb-3">
            <label class="fw-bold">Amount</label>
            <div class="input-group">
              <span class="input-group-text bg-white">₱</span>
              <input type="number" name="amount" id="amount_input_{{ $rowId }}" step="0.01" class="form-control font-monospace" value="{{ $rawAmount }}" required>
            </div>
            <div class="mt-1 small font-monospace text-muted">
              Live Preview: <span id="amount_preview_{{ $rowId }}" class="fw-bold text-dark">₱{{ number_format((float)$rawAmount, 2) }}</span>
            </div>
          </div>

          {{-- TRANSACTION TYPE SELECTOR --}}
          <div class="mb-3">
            <label class="fw-bold d-block mb-1">Transaction Type</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="transaction_type" id="type_received_{{ $rowId }}" value="received" @checked($txType === 'received') required>
              <label class="form-check-label fw-semibold text-success" for="type_received_{{ $rowId }}">NCA/NTA Received</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="transaction_type" id="type_downloaded_{{ $rowId }}" value="downloaded" @checked($txType === 'downloaded')>
              <label class="form-check-label fw-semibold text-danger" for="type_downloaded_{{ $rowId }}">NCA/NTA Downloaded</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="transaction_type" id="type_adjustment_{{ $rowId }}" value="adjustment" @checked($txType === 'adjustment')>
              <label class="form-check-label fw-semibold text-warning" for="type_adjustment_{{ $rowId }}">Adjustment</label>
            </div>
          </div>


          {{-- ADA CHECK NO INPUT --}}
          <div class="mb-3">
            <label class="fw-bold">ADA Check No.</label>
            <input type="text" name="ada_check_no" class="form-control font-monospace" value="{{ $record->ada_check_no }}" placeholder="Enter tracking instrument id...">
          </div>

          {{-- REMARKS INPUT --}}
          <div class="mb-3">
            <label class="fw-bold">Remarks</label>
            <textarea name="remarks" class="form-control" rows="2" placeholder="Optional updates...">{{ $record->remarks }}</textarea>
          </div>
        </div>
        
        <div class="modal-footer">
          <x-button type="button" variant="secondary" data-bs-dismiss="modal">Cancel</x-button>
          <x-button type="submit" variant="primary">Save Changes</x-button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const editAmountInput = document.getElementById('amount_input_{{ $rowId }}');
    const editAmountPreview = document.getElementById('amount_preview_{{ $rowId }}');

    if (editAmountInput && editAmountPreview) {
      editAmountInput.addEventListener('input', function() {
        const val = parseFloat(this.value);
        if (!isNaN(val)) {
          editAmountPreview.textContent = new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP'
          }).format(val);
        } else {
          editAmountPreview.textContent = '₱0.00';
        }
      });
    }
  });
</script>