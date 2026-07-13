<style>
    /* Force all disabled or readonly fields to look explicitly gray and un-editable */
    input:disabled, 
    select:disabled, 
    textarea:disabled,
    input[readonly], 
    select[readonly], 
    textarea[readonly] {
        background-color: #e9ecef !important;
        color: #6c757d !important;
        opacity: 1;
        cursor: not-allowed;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const $ = (id) => document.getElementById(id);

    // ===================== FORMAT DATETIME =====================
    function formatDateTime(value) {
        if (!value) return '';
        return value.replace(' ', 'T').substring(0, 16);
    }

    // ===================== WORKING TIME CALCULATION FALLBACKS =====================
    function calculateBudgetTime(row) {
        return row.total_time_budget ?? '-';
    }
    function calculateWorkingHours(start, end) {
        return 0; // Handled by backend, placeholder to avoid crashes
    }
    function formatWorkingTime(hours) {
        return '-';
    }

    
    // ===================== TOMSELECT INIT =====================
    function initTomSelect(context = document) {
        const config = {
            create: false,
            allowEmptyOption: true,
            placeholder: "Search..."
        };

        const selectors = [
            '#edit_issuing_office',
            '#edit_classifications',
            '#edit_uac_codes',
            '#edit_status', // Added status selector here to ensure it initializes safely if using TomSelect

            'select[name="issuing_office"]',
            'select[name="classification"]',
            'select[name="uac_codes"]',
            'select[name="status"]'
        ];

        selectors.forEach(sel => {
            const el = context.querySelector(sel);
            if (!el) return;

            // destroy old instance if exists
            if (el.tomselect) {
                el.tomselect.destroy();
            }

            new TomSelect(el, config);
        });
    }

    // ===================== GET RECORD =====================
    async function getRecord(id) {
        const url = `/budget/logbook/${encodeURIComponent(id)}/details`;
        console.log("Fetching:", url);
        const res = await fetch(url);
        console.log("Status:", res.status);

        if (!res.ok) {
            const text = await res.text();
            console.log(text);
            throw new Error("HTTP " + res.status);
        }
        return await res.json();
    }

    // ===================== VIEW =====================
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.view-btn');
        if (!btn) return;

        const id = btn.dataset.budgetId;
        const modal = bootstrap.Modal.getOrCreateInstance($('detailsModal'));

        modal.show();

        $('detailsLoading').classList.remove('d-none');
        $('detailsContent').classList.add('d-none');

        try {
            const response = await getRecord(id);
            const row = response.budget;
            const reviews = response.reviews ?? [];

            // Hide loading spinner
            $('detailsLoading').classList.add('d-none');
            $('detailsContent').classList.remove('d-none');

            $('transactionTitle').textContent = row.ors_no ?? '-';
            $('transactionSubtitle').textContent = row.payee ?? '-';

            $('detailsEditBtn').onclick = () => {
                modal.hide();
                openEditModal(id);
            };

            // ================= REVIEW HISTORY =================
            let reviewHtml = "";

            if (row.date_returned_1 || row.remarks_1 || row.date_received_1) {
                reviewHtml += `
                <div class="border rounded p-3 mb-3 bg-light">
                    <h6 class="fw-bold mb-3">Review #1</h6>
                    <div class="row mb-1">
                        <div class="col-5 fw-bold">Date Returned:</div>
                        <div class="col-7">${row.date_returned_1 ?? '-'}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-5 fw-bold">Remarks:</div>
                        <div class="col-7">${row.remarks_1 ?? '-'}</div>
                    </div>
                    <div class="row">
                        <div class="col-5 fw-bold">Date Received:</div>
                        <div class="col-7">${row.date_received_1 ?? '-'}</div>
                    </div>
                </div>
                `;
            }

            reviews.forEach((review, index) => {
                reviewHtml += `
                <div class="border rounded p-3 mb-3 bg-light">
                    <h6 class="fw-bold mb-3">Review #${index + 2}</h6>
                    <div class="row mb-1">
                        <div class="col-5 fw-bold">Date Returned:</div>
                        <div class="col-7">${review.date_returned ?? '-'}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-5 fw-bold">Remarks:</div>
                        <div class="col-7">${review.remarks ?? '-'}</div>
                    </div>
                    <div class="row">
                        <div class="col-5 fw-bold">Date Received:</div>
                        <div class="col-7">${review.date_received ?? '-'}</div>
                    </div>
                </div>
                `;
            });

            if (reviewHtml === "") {
                reviewHtml = `<div class="text-muted">No review history.</div>`;
            }

            $('view_date_received').textContent = row.date_received ?? '-';
            $('view_issuing_office').textContent = row.issuing_office ?? '-';
            $('view_payee').textContent = row.payee ?? '-';
            $('view_classification').textContent = row.classification ?? '-';
            $('view_ors_no').textContent = row.ors_no ?? '-';
            $('view_particulars').textContent = row.particulars ?? '-';
            $('view_particulars_remark').textContent = row.particulars_remark ?? '-';
            $('view_due_date').textContent = row.due_date ?? '-';
            $('view_amount').textContent = Number(row.amount ?? 0).toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            $('view_status').textContent = row.status ?? '-';
            $('view_date_forwarded_1').textContent = row.date_forwarded_1 ?? '-';
            $('view_date_ors_received').textContent = row.date_ors_received ?? '-';
            $('view_date_returned_2').textContent = row.date_returned_2 ?? '-';
            $('view_remarks_2').textContent = row.remarks_2 ?? '-';
            $('view_date_received_2').textContent = row.date_received_2 ?? '-';
            $('view_date_forwarded_accounting').textContent = row.date_forwarded_accounting ?? '-';
            $('view_final_remarks').textContent = row.final_remarks ?? '-';
            $('view_total_time_budget').textContent = calculateBudgetTime(row);
            $('view_total_time').textContent = formatWorkingTime(calculateWorkingHours(row.date_received, row.date_forwarded_accounting));

            $('view_review_history').innerHTML = reviewHtml;
            
        } catch (err) {
            console.error("View Modal Error:", err);
        }
    });

    // ===================== OPEN EDIT MODAL =====================
    window.openEditModal = async (id) => {
        try {
            const response = await getRecord(id);
            const row = response.budget;
            const reviews = response.reviews ?? [];

            $('editForm').action = `/budget/logbook/${encodeURIComponent(id)}/update`;

            const fields = [
                'ors_no','date_received','payee','particulars','amount','due_date',
                'date_returned_1','date_received_1','remarks_1',
                'date_forwarded_1','date_ors_received','remarks_2',
                'date_returned_2','date_received_2',
                'date_forwarded_accounting','status',
                'total_time_budget','total_time','final_remarks'
            ];

            fields.forEach(f => {
                const el = $('edit_' + f);
                if (!el) return;

                if (el.type === 'datetime-local') {
                    el.value = formatDateTime(row[f]);
                }
                else if (el.type === 'date') {
                    el.value = row[f]?.substring(0,10) ?? '';
                }
                else {
                    el.value = row[f] ?? '';
                }
            });

            $('edit_total_time_budget').value = calculateBudgetTime(row);
            $('edit_total_time').value = formatWorkingTime(calculateWorkingHours(row.date_received, row.date_forwarded_accounting));

            // ===========================
            // LOAD REVIEW HISTORY
            // ===========================
            const container = $('reviewRowsContainer');
            container.innerHTML = '';

            if (row.date_returned_1 || row.remarks_1 || row.date_received_1) {
                const clone = document.getElementById('reviewRowTemplate').content.cloneNode(true);
                clone.querySelector('[name="review_date_returned[]"]').value = formatDateTime(row.date_returned_1);
                clone.querySelector('[name="review_date_received[]"]').value = formatDateTime(row.date_received_1);
                clone.querySelector('[name="review_remarks[]"]').value = row.remarks_1 ?? '';
                container.appendChild(clone);
            }

            reviews.forEach(review => {
                const clone = document.getElementById('reviewRowTemplate').content.cloneNode(true);
                clone.querySelector('[name="review_date_returned[]"]').value = formatDateTime(review.date_returned);
                clone.querySelector('[name="review_date_received[]"]').value = formatDateTime(review.date_received);
                clone.querySelector('[name="review_remarks[]"]').value = review.remarks ?? '';
                container.appendChild(clone);
            });

            // Sync Dropdowns (Standard / TomSelect verification layout)
            const syncSelect = (elementId, value) => {
                const selectEl = $(elementId);
                if (!selectEl) return;
                if (selectEl.tomselect) {
                    selectEl.tomselect.setValue(value ?? "", true);
                } else {
                    selectEl.value = value ?? "";
                }
            };

            syncSelect("edit_issuing_office", row.issuing_office);
            syncSelect("edit_classifications", row.classification);
            syncSelect("edit_uac_codes", row.uac_codes);
            syncSelect("edit_status", row.status); 
            
            handleStatusFieldDependencies(row.status);

            bootstrap.Modal.getOrCreateInstance($("editModal")).show();
        }
        catch (e) {
            alert(e.message);
        }
    };

    // ===================== REALTIME STATUS CHANGE EFFECTS =====================
    function handleStatusFieldDependencies(currentStatus) {
        console.log("Status shifted to:", currentStatus);

        // Define which elements are impacted by the dynamic status
        const forwardingField = $('edit_date_forwarded_accounting');
        const finalRemarksField = $('edit_final_remarks');
        
        // EXAMPLE LOGIC: Adjust this mapping based on your requirements
        if (currentStatus === "Processing") {
            // If Processing, enable these fields so the user can edit them
            if (forwardingField) forwardingField.removeAttribute('disabled');
            if (finalRemarksField) finalRemarksField.removeAttribute('disabled');
        } 
        else if (currentStatus === "Pending") {
            // If Pending, lock down these fields to avoid accidental entry
            if (forwardingField) forwardingField.setAttribute('disabled', 'disabled');
            if (finalRemarksField) finalRemarksField.setAttribute('disabled', 'disabled');
        }
        else {
            // Default baseline state rules for other options
            if (forwardingField) forwardingField.removeAttribute('disabled');
            if (finalRemarksField) finalRemarksField.removeAttribute('disabled');
        }
    }

    // Bind event tracking directly onto the Status Selection field
    const statusSelectEl = $('edit_status');
    if (statusSelectEl) {
        // Capture normal select element dropdown changes
        statusSelectEl.addEventListener('change', (e) => {
            handleStatusFieldDependencies(e.target.value);
        });

        // Capture asynchronous changes if handled via the TomSelect wrapper
        setTimeout(() => {
            if (statusSelectEl.tomselect) {
                statusSelectEl.tomselect.on('change', (value) => {
                    handleStatusFieldDependencies(value);
                });
            }
        }, 500);
    }

    // ===================== EDIT BUTTON =====================
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.edit-btn');
        if (btn) openEditModal(btn.dataset.budgetId);
    });

    // ===================== ORS VALIDATION =====================
    $('editForm')?.addEventListener('submit', (e) => {
        const ors = $('edit_ors_no');
        const err = $('editError');

        err?.classList.add('d-none');

        if (ors.value.trim() && !/^\d+$/.test(ors.value.trim())) {
            e.preventDefault();
            err.innerHTML = 'ORS No. must be numeric.';
            err.classList.remove('d-none');
            ors.focus();
        }
    });

    // ===================== DELETE =====================
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.delete-btn');
        if (!btn) return;

        const id = btn.dataset.budgetId;

        $('actionTitle').textContent = 'Delete Record';
        $('actionBody').innerHTML = `Delete <strong>${id}</strong>?`;

        $('actionFooter').innerHTML = `
            <form method="POST" action="/budget/logbook/${encodeURIComponent(id)}/destroy">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        `;

        bootstrap.Modal.getOrCreateInstance($('actionModal')).show();
    });

    // ===================== ADD MODAL INIT =====================
    document.getElementById('addRecordModal')?.addEventListener('shown.bs.modal', function () {
        initTomSelect(this);
    });

    // ===================== INITIAL LOAD =====================
    initTomSelect(document);
});

// ===================== REVIEW HISTORY DOM MANAGEMENT =====================
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('reviewRowsContainer');
    const template = document.getElementById('reviewRowTemplate');
    const addBtn = document.getElementById('btnAddReviewRow');

    if (addBtn && container && template) {
        addBtn.addEventListener('click', function () {
            const clone = template.content.cloneNode(true);
            container.appendChild(clone);
        });

        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('btnRemoveReview')) {
                e.target.closest('.review-row').remove();
            }
        });
    }
});

// ===================== NOTIFICATIONS POLLING MANAGEMENT =====================
document.addEventListener('DOMContentLoaded', function () {
    const readAllBtn = document.getElementById('readAllBtn');
    
    loadNotifications();
    setInterval(loadNotifications, 30000);

    if (readAllBtn) {
        readAllBtn.addEventListener('click', function (e) {
            e.preventDefault();
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(() => loadNotifications());
        });

    });

    const ors = document.getElementById("edit_ors_no");

    if (ors) {
        ors.addEventListener("input", function () {
            this.value = this.value.replace(/\D/g, "");
        });
    }
    // ===================== COMPUTE WORKING HOURS =====================
    // Exclude Friday, Saturday, Sunday
    function calculateWorkingHours(start, end) {

        if (!start || !end) return 0;

        let startDate = new Date(start.replace(' ', 'T'));
        let endDate = new Date(end.replace(' ', 'T'));

        if (isNaN(startDate) || isNaN(endDate)) {
            return 0;
        }

        let hours = 0;

        while (startDate < endDate) {

            let day = startDate.getDay();

            // Monday-Thursday only
            if (day !== 0 && day !== 5 && day !== 6) {
                hours++;
            }

            startDate.setHours(startDate.getHours() + 1);
        }

        return hours;
    }


    // ===================== FORMAT 0d0h =====================
    function formatWorkingTime(hours) {

        let days = Math.floor(hours / 24);
        let remainingHours = hours % 24;

        return `${days}d${remainingHours}h`;
    }

    function calculateBudgetTime(row) {

        let total = calculateWorkingHours(
            row.date_received,
            row.date_forwarded_accounting
        );


        // Remove Returned to End User periods
        total -= calculateWorkingHours(
            row.date_returned_1,
            row.date_received_1
        );


        total -= calculateWorkingHours(
            row.date_returned_2,
            row.date_received_2
        );


        if (total < 0) {
            total = 0;
        }


        return formatWorkingTime(total);
    }
</script>