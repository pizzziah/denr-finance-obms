<script>
document.addEventListener('DOMContentLoaded', function () {

  const showUrlBase   = "{{ url('/accounting/logbook') }}"; 
  const updateUrlBase = "{{ url('/accounting/logbook') }}"; 
  const rowTemplate   = document.getElementById('creditRowTemplate');

  const RETURN_STATUSES = ['Returned to End User', 'Returned to Budget'];

  /* ---------------------------------------------------------------- *
   * Helper: Format datetime for <input type="datetime-local">
   * ---------------------------------------------------------------- */
  function formatDateTimeLocal(dateString) {
    if (!dateString || dateString === '-') return '';
    return dateString.replace(' ', 'T').substring(0, 16);
  }

  /* ---------------------------------------------------------------- *
   * Credit-entry repeater
   * ---------------------------------------------------------------- */
  function addCreditRow(containerId, prefill) {
    const container = document.getElementById(containerId);
    if (!container || !rowTemplate) return;

    const clone = rowTemplate.content.cloneNode(true);
    const wrap  = clone.querySelector('.credit-row');

    if (prefill) {
      wrap.querySelector('[name="credit_uac_codes[]"]').value   = prefill.uac_codes ?? '';
      wrap.querySelector('[name="credit_amounts[]"]').value     = prefill.credit ?? '';
      wrap.querySelector('[name="credit_tax_percent[]"]').value = prefill.tax_percent ?? '';
      wrap.querySelector('[name="credit_tax_remarks[]"]').value = prefill.tax_remarks ?? '';
    }

    container.appendChild(clone);

    // Get the newly added row
    const newRow = container.lastElementChild;

    // Initialize TomSelect for its UACS dropdown
    const uacsSelect = newRow.querySelector('[name="credit_uac_codes[]"]');

    if (uacsSelect && !uacsSelect.tomselect) {
      new TomSelect(uacsSelect, {
        create: false,
        searchField: ['text'],
        placeholder: 'Search UACS...'
      });
    }
  }

  document.getElementById('addUacsBtn-add')?.addEventListener('click', () => addCreditRow('addCreditRows'));
  document.getElementById('addUacsBtn-edit')?.addEventListener('click', () => addCreditRow('editCreditRows'));

  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.remove-credit-row');
    if (!btn) return;
    btn.closest('.credit-row')?.remove();
    recalcEditCreditTotal();
  });

  document.addEventListener('input', function (e) {
    if (e.target.name === 'credit_amounts[]') recalcEditCreditTotal();
  });

  function recalcEditCreditTotal() {
    const total = Array.from(document.querySelectorAll('#editCreditRows [name="credit_amounts[]"]'))
      .reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
    const el = document.getElementById('editCreditTotal');
    if (el) el.textContent = total.toFixed(2);
  }

  /* ---------------------------------------------------------------- *
   * Toggles (Signed & Status)
   * ---------------------------------------------------------------- */
  function wireSignedToggle(select) {
    if (!select) return;
    const scope = select.dataset.scope;
    const signedBy   = document.querySelector(`.signed-by-input[data-scope="${scope}"]`);
    const dateSigned = document.querySelector(`.date-signed-input[data-scope="${scope}"]`);

    function apply() {
      const isYes = select.value === 'Yes';
      [signedBy, dateSigned].forEach(input => {
        if (!input) return;
        input.disabled = !isYes;
        if (isYes) input.setAttribute('required', 'required');
        else { input.removeAttribute('required'); input.value = ''; }
      });
    }
    select.addEventListener('change', apply);
    apply();
  }
  document.querySelectorAll('.signed-select').forEach(wireSignedToggle);

  function wireStatusToggle(select) {
    if (!select) return;
    const scope = select.dataset.scope;
    const wrap = document.querySelector(`.returned-remarks-wrap[data-scope="${scope}"]`);
    function apply() {
      if (wrap) wrap.style.display = RETURN_STATUSES.includes(select.value) ? '' : 'none';
    }
    select.addEventListener('change', apply);
    apply();
  }
  document.querySelectorAll('.status-select').forEach(wireStatusToggle);

  // Make all existing selects searchable
  document.querySelectorAll('#editRecordModal select').forEach(function(select) {
    if (!select.tomselect) {
      new TomSelect(select, {
        create: false,
        searchField: ['text'],
        placeholder: 'Search...'
      });
    }
  });                              

  /* ---------------------------------------------------------------- *
   * Edit modal: Fetch transaction and prefill using Self-Healing URLs
   * ---------------------------------------------------------------- */
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.action-btn[data-action="edit"]');
    if (!btn) return;

    const dbId = btn.dataset.id;
    const dvCode = btn.dataset.dv || btn.dataset.txn || dbId;
    if (!dbId) return;

    const loading  = document.getElementById('editLoading');
    const formBody = document.getElementById('editFormBody');
    const editCreditRows = document.getElementById('editCreditRows');

    // Reset UI states
    if (loading) {
      loading.style.display  = '';
      loading.innerHTML = `
        <div class="spinner-border text-primary" role="status"></div>
        <p class="text-muted mt-2">Retrieving transaction details...</p>
      `;
    }
    if (formBody) formBody.style.display = 'none';
    if (editCreditRows) editCreditRows.innerHTML = '';

    document.getElementById('editTransactionId').value = dbId;
    document.getElementById('editTransactionLabel').textContent = dvCode;

    // Build URLs using the primary ID parameter
    const restfulUrl = `${showUrlBase}/${dbId}`;
    const customUrl  = `${showUrlBase}/${dbId}/show`;

    let finalUpdateUrl = `${updateUrlBase}/${dbId}`;

    fetch(restfulUrl, {
      headers: { 
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json' 
      }
    })
    .then(res => {
      if (res.ok) return res.json();
      
      finalUpdateUrl = `${updateUrlBase}/${dbId}/update`;
      return fetch(customUrl, {
        headers: { 
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json' 
        }
      }).then(res2 => {
        if (!res2.ok) throw new Error('Could not retrieve transaction details.');
        return res2.json();
      });
    })
    .then(data => {
      document.getElementById('editRecordForm').action = finalUpdateUrl;

      const record = data.record || data || {};

      const isBudgetSourced = (record.budget_id !== null && record.budget_id !== undefined && record.budget_id !== '');
      const fieldsToLock = ['edit_payee', 'edit_particulars', 'edit_debit', 'edit_uac_codes', 'edit_obr_no'];

      fieldsToLock.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
          if (el.tagName === 'SELECT') {
            el.disabled = isBudgetSourced;
          } else {
            el.readOnly = isBudgetSourced;
          }
          el.style.backgroundColor = isBudgetSourced ? '#e9ecef' : '';
        }
      });

      const safelySet = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.value = value ?? '';
      };

      safelySet('edit_date_received', formatDateTimeLocal(record.date_received));
      safelySet('edit_obr_date', (record.obr_date || '').substring(0, 10));
      safelySet('edit_obr_no', record.obr_no || record.ors_no);
      safelySet('edit_ors_no', record.ors_no || record.obr_no);
      safelySet('edit_payee', record.payee);
      safelySet('edit_particulars', record.particulars);
      safelySet('edit_particulars_remark', record.particulars_remark);
      safelySet('edit_date_processed', formatDateTimeLocal(record.date_processed));
      safelySet('edit_dv_no', record.dv_no);
      safelySet('edit_debit', record.debit ? parseFloat(record.debit).toFixed(2) : '');
      
      const debitUacs = document.getElementById('edit_uac_codes');
      if (debitUacs) {

          const uacValue = record.uac_codes ?? '';

          if (debitUacs.tomselect) {

              // Clear old value first
              debitUacs.tomselect.clear();

              // Set database value
              if (uacValue) {
                  debitUacs.tomselect.setValue(uacValue, true);
              }

          } else {

              debitUacs.value = uacValue;

          }
      }

      const debitTotal = document.getElementById('editDebitTotal');
      if (debitTotal) debitTotal.textContent = record.debit ? parseFloat(record.debit).toFixed(2) : '0.00';

      const signedSelect = document.getElementById('edit_signed');
      if (signedSelect) {
        signedSelect.value = (record.signed === 'Yes' || record.signed == 1 || record.signed === true) ? 'Yes' : 'No';
        signedSelect.dispatchEvent(new Event('change'));
      }
      
      safelySet('edit_signed_by_accountant', record.signed_by_accountant);
      safelySet('edit_date_signed', formatDateTimeLocal(record.date_signed));

      const statusSelect = document.getElementById('edit_status');
      if (statusSelect) {
        statusSelect.value = record.status || 'Pending';
        statusSelect.dispatchEvent(new Event('change'));
      }

      safelySet('edit_date_forwarded', formatDateTimeLocal(record.date_forwarded));
      safelySet('edit_returned_remarks', record.returned_remarks);

      const creditEntries = data.credit_entries || record.credit_entries || [];
      creditEntries.forEach(entry => addCreditRow('editCreditRows', entry));
      recalcEditCreditTotal();

      if (loading) loading.style.display  = 'none';
      if (formBody) formBody.style.display = '';
    })
    .catch((error) => {
      console.error('Data Fetching Error:', error);
      if (loading) {
        loading.innerHTML = `
          <span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Failed to load record.</span><br>
          <small class="text-muted">Ensure route for <b>${dvCode}</b> exists.</small>
        `;
      }
    });
  });

  /* ---------------------------------------------------------------- *
   * Pay Confirmation Action
   * ---------------------------------------------------------------- */
/* ---------------------------------------------------------------- *
   * View Action: Fetch & Populate dynamic details modal
   * ---------------------------------------------------------------- */
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.view-details-btn') || e.target.closest('.action-btn[data-action="view"]');
    if (!btn) return;

    const dbId = btn.dataset.id;
    if (!dbId) return;

    const loading = document.getElementById('detailsLoading') || document.getElementById('modalLoading');
    const content = document.getElementById('detailsContent') || document.getElementById('modalContent');
    const errorMsg = document.getElementById('modalError');
    const creditContainer = document.getElementById('view-credit-entries');

    // Reset UI Visibility states
    if (loading) loading.classList.remove('d-none');
    if (content) content.classList.add('d-none');
    if (errorMsg) errorMsg.classList.add('d-none');
    if (creditContainer) creditContainer.innerHTML = '';

    const restfulUrl = `${showUrlBase}/${dbId}`;
    const customUrl  = `${showUrlBase}/${dbId}/show`;

    fetch(restfulUrl, {
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => {
      if (res.ok) return res.json();
      return fetch(customUrl, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      }).then(res2 => {
        if (!res2.ok) throw new Error('Data endpoint execution failed.');
        return res2.json();
      });
    })
    .then(data => {
      const record = data.record || data || {};
      const creditEntries = data.credit_entries || record.credit_entries || [];

      const safelyText = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.textContent = val && val !== '' ? val : '-';
      };

      // Set Sub-header Titles explicitly showing both transaction_id and dv_no
      const txnId = record.transaction_id || data.transaction_id || '-';
      const dvNo  = record.dv_no || '-';
      const obrNo = record.obr_no || record.ors_no || '-';

      safelyText('transactionTitle', `DV No: ${dvNo} | TXN ID: ${txnId}`);
      safelyText('transactionSubtitle', `OBR/ORS No: ${obrNo}`);

      // Basic Information
      safelyText('view_date_received', record.date_received);
      safelyText('view_obr_date', record.obr_date);
      safelyText('view_obr_no', obrNo);
      safelyText('view_payee', record.payee);
      safelyText('view_particulars', record.particulars);
      safelyText('view_particulars_remark', record.particulars_remark);

      // Debit Entry Information
      safelyText('view_date_processed', record.date_processed);
      safelyText('view_dv_no', dvNo);
      safelyText('view_uac_codes', record.uac_codes);

      const numericDebit = parseFloat(data.total_debit || record.debit || 0);
      const formattedDebit = numericDebit.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      
      safelyText('view_debit', formattedDebit);
      safelyText('viewDebitTotal', formattedDebit);

      // Populate Credit Entries Cards Grid Breakdown
      let totalCreditSum = 0;
      if (creditContainer) {
        if (creditEntries.length > 0) {
          creditContainer.innerHTML = creditEntries.map((entry, index) => {
            const entryCredit = parseFloat(entry.credit || 0);
            totalCreditSum += entryCredit;

            return `
              <div class="col-md-6 col-12">
                <div class="card border border-light-subtle shadow-sm h-100">
                  <div class="card-header bg-light py-1 px-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-secondary small">Entry #${index + 1}</span>
                    <code class="fw-bold text-dark">${entry.uac_codes ?? '-'}</code>
                  </div>
                  <div class="card-body p-3">
                    <div class="row mb-2">
                      <div class="col-5 fw-bold text-muted small">Credit Amount:</div>
                      <div class="col-7 fw-bold text-success">₱${entryCredit.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                    </div>
                    <div class="row mb-2">
                      <div class="col-5 fw-bold text-muted small">Tax Percent:</div>
                      <div class="col-7 small">${entry.tax_percent ? entry.tax_percent + '%' : '-'}</div>
                    </div>
                    <div class="row">
                      <div class="col-5 fw-bold text-muted small">Tax Remarks:</div>
                      <div class="col-7 small text-break">${entry.tax_remarks && entry.tax_remarks !== '' ? entry.tax_remarks : '-'}</div>
                    </div>
                  </div>
                </div>
              </div>
            `;
          }).join('');
        } else {
          creditContainer.innerHTML = `
            <div class="col-12 text-center text-muted py-4 bg-light rounded border">
              <i class="bi bi-inbox fs-4 d-block mb-1"></i> No credit entries found for this record.
            </div>
          `;
        }
      }

      safelyText('viewCreditTotal', totalCreditSum.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

      // Sign-off Information
      safelyText('view_signed', record.signed);
      safelyText('view_date_signed', record.date_signed);
      safelyText('view_signed_by_accountant', record.signed_by_accountant);

      // Status & Forwarding Information
      safelyText('view_status', record.status);
      safelyText('view_date_forwarded', record.date_forwarded);

      // Handle returned remarks display toggle
      const returnedWrap = document.getElementById('view_returned_remarks_wrap');
      if (returnedWrap) {
        if (RETURN_STATUSES.includes(record.status)) {
          returnedWrap.style.display = '';
          safelyText('view_returned_remarks', record.returned_remarks);
        } else {
          returnedWrap.style.display = 'none';
        }
      }

      // Action buttons configuration
      const editBtn = document.getElementById('detailsEditBtn');
      if (editBtn) {
        editBtn.setAttribute('data-id', record.accounting_id || record.transaction_id || dbId);
        editBtn.setAttribute('data-dv', dvNo);
        editBtn.setAttribute('data-txn', txnId);
        editBtn.onclick = function () {
          const detailsModalInstance = bootstrap.Modal.getInstance(document.getElementById('detailsModal'));
          if (detailsModalInstance) detailsModalInstance.hide();

          const editBtnTrigger = document.querySelector(`.action-btn[data-action="edit"][data-id="${dbId}"]`);
          if (editBtnTrigger) editBtnTrigger.click();
        };
      }

      const deleteBtn = document.getElementById('detailsDeleteBtn');
      if (deleteBtn) {
        deleteBtn.setAttribute('data-id', record.accounting_id || record.transaction_id || dbId);
        deleteBtn.setAttribute('data-dv', dvNo);
        deleteBtn.setAttribute('data-txn', txnId);
        deleteBtn.onclick = function () {
          const detailsModalInstance = bootstrap.Modal.getInstance(document.getElementById('detailsModal'));
          if (detailsModalInstance) detailsModalInstance.hide();

          const deleteBtnTrigger = document.querySelector(`.action-btn[data-action="delete"][data-id="${dbId}"]`);
          if (deleteBtnTrigger) deleteBtnTrigger.click();
        };
      }

      // Display metrics state changes
      if (loading) loading.classList.add('d-none');
      if (content) content.classList.remove('d-none');
    })
    .catch((error) => {
      console.error('View details parsing error:', error);
      if (loading) loading.classList.add('d-none');
      if (errorMsg) errorMsg.classList.remove('d-none');
    });
  });

  const viewId = "{{ $view ?? '' }}";
  if (viewId) {
    const viewButton = document.querySelector(
      `.action-btn[data-action="view"][data-id="${viewId}"]`
    );
    if (viewButton) {
      viewButton.closest("tr")?.scrollIntoView({
        behavior: "smooth",
        block: "center"
      });

      setTimeout(() => {
        viewButton.click();
      }, 500);
    }
  }
});
</script>