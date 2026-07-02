{{-- ACTION SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // COMMON FUNCTION
    async function getRecord(budget_id) {
        const response = await fetch(`/budget/logbook/${encodeURIComponent(budget_id)}/details`);
        if (!response.ok) {throw new Error('Unable to load record.'); }
        return await response.json();
    }
    // VIEW BUTTON
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', function () {
            const budget_id = this.dataset.budgetId;
            const modal = bootstrap.Modal.getOrCreateInstance(
                document.getElementById('detailsModal')
            );

            modal.show();
            
            document.getElementById('detailsBody').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-success"></div>
                </div>
            `;

            fetch('/budget/logbook/' + budget_id + '/details')
                .then(response => response.json())
                .then(row => {
                    document.getElementById('transactionTitle').textContent =
                        row.ors_no ?? '-';

                    document.getElementById('transactionSubtitle').textContent =
                        row.payee ?? '-';
                    
                        document.getElementById('detailsEditBtn').onclick = function () {

                    bootstrap.Modal.getInstance(
                        document.getElementById('detailsModal')
                        ).hide();

                        openEditModal(budget_id);

                    };
                    let html = `

                    <div class="container-fluid">
                        <div class="row">
                            <!-- Section Title -->
                            <div class="col-2 fw-bold fs-4 lh-1">
                                Request<br>Information
                            </div>

                            <!-- Left Column -->
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-5 fw-bold">Date Received:</div>
                                    <div class="col-7">${row.date_received ?? '-'}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5 fw-bold">Issuing Office:</div>
                                    <div class="col-7">${row.issuing_office ?? '-'}</div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-5 fw-bold">Payee:</div>
                                    <div class="col-7">${row.payee ?? '-'}</div>
                                </div>
                                <div class="row  ">
                                    <div class="col-5 fw-bold">Classification:</div>
                                    <div class="col-7">${row.classification ?? '-'}</div>
                                </div>
                                <div class="row  ">
                                    <div class="col-5 fw-bold">Particulars:</div>
                                    <div class="col-7">${row.particulars ?? '-'}</div>
                                </div>
                                <div class="row  ">
                                    <div class="col-5 fw-bold">Particulars Remark:</div>
                                    <div class="col-7">${row.particulars_remark ?? '-'}</div>
                                </div>
                                <div class="row  ">
                                    <div class="col-5 fw-bold">Amount:</div>
                                        <div class="col-7">
                                            ₱${Number(row.amount ?? 0).toLocaleString(undefined,{
                                                minimumFractionDigits:2,
                                                maximumFractionDigits:2
                                                })}
                                        </div>
                                </div>
                            </div>   
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-2 fw-bold fs-4 lh-1">
                                Review<br>Processing
                            </div>
                            
                            <!-- LEFT COLUMN -->
                            <div class="col-5">
                                <div class="row mb">
                                    <div class="col-5 fw-bold">Status:</div>
                                    <div class="col-7">${row.status ?? '-'}</div>
                                </div>
                            </div>

                            <!-- RIGHT COLUMN -->
                            <div class="col-5">
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Date Returned:</div>
                                    <div class="col-7">${row.date_returned_1 ?? '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Remarks:</div>
                                    <div class="col-7">${row.remarks_1?? '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Date Received:</div>
                                    <div class="col-7">${row.date_received_1 ?? '-'}</div>
                                </div>
                            </div>
                        </div>

                        <hr> 

                        <div class="row">
                            <div class="col-2 fw-bold fs-4 lh-1">
                                Obligation<br>Processing
                            </div>
                            
                            <!-- LEFT COLUMN -->
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-5 fw-bold">ORS No:</div>
                                    <div class="col-7">${row.ors_no ?? '-'}</div>
                                </div>
                            </div>

                            <!-- RIGHT COLUMN -->
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-5 fw-bold">Date Forwarded:</div>
                                    <div class="col-7">${row.date_forwarded_1 ?? '-'}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5 fw-bold">Date ORS received:</div>
                                    <div class="col-7">${row.date_ors_received ?? '-'}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5 fw-bold">Date Returned:</div>
                                    <div class="col-7">${row.date_returned_2 ?? '-'}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5 fw-bold">Remarks:</div>
                                    <div class="col-7">${row.remarks_2 ?? '-'}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5 fw-bold">Date Received:</div>
                                    <div class="col-7">${row.date_received_2 ?? '-'}</div>
                                </div>
                            </div>
                        </div>

                        <hr> 

                        <div class="row">
                            <div class="col-2 fw-bold fs-4 lh-1">
                                Forwarded<br>to Accounting
                            </div>
                            
                            <!-- LEFT COLUMN -->
                            <div class="col-5">
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Date Forwarded to Acccounting:</div>
                                    <div class="col-7">${row.date_forwarded_accounting ?? '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Remarks:</div>
                                    <div class="col-7">${row.final_remarks ?? '-'}</div>
                                </div>
                            </div>
                        </div>

                        <hr> 

                        <div class="row">
                            <div class="col-2 fw-bold fs-4 lh-1">
                                Processing<br>Metrics
                            </div>
                            
                            <!-- LEFT COLUMN -->
                            <div class="col-5">
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Total Time in Budget:</div>
                                    <div class="col-7">${row.total_time_budget ?? '-'}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 fw-bold">Total Time:</div>
                                    <div class="col-7">${row.total_time ?? '-'}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    `;

                    document.getElementById('detailsBody').innerHTML = html;
                })
                .catch(() => {
                    document.getElementById('detailsBody').innerHTML = `
                        <div class="alert alert-danger">
                            Unable to load record.
                        </div>
                    `;
                });
        });
    });
    function prettyDate(dateString) {
            if (!dateString) return '';

            return new Date(dateString).toLocaleString('en-PH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }
        // Convert MySQL DATETIME -> datetime-local format
    function formatDateTime(value) {
        if (!value) return '';

        // 2026-06-08 17:05:00 -> 2026-06-08T17:05
        return value.replace(' ', 'T').substring(0, 16);
    }

    // ===================== OPEN EDIT MODAL =====================
    async function openEditModal(budget_id) {

        try {

            const row = await getRecord(budget_id);

            document.getElementById('editForm').action =
                `/budget/logbook/${encodeURIComponent(budget_id)}/update`;

            const fields = [
                'ors_no',
                'date_received',
                'payee',
                'particulars',
                'amount',

                'date_returned_1',
                'date_received_1',
                'remarks_1',

                'date_forwarded_1',
                'date_ors_received',
                'remarks_2',

                'date_returned_2',
                'date_received_2',

                'date_forwarded_accounting',

                'status',
                'total_time_budget',
                'total_time',
                'final_remarks'
            ];

            fields.forEach(field => {

                const input = document.getElementById('edit_' + field);

                if (!input) return;

                if (input.type === 'datetime-local') {

                    input.value = formatDateTime(row[field]);

                } else if (input.type === 'date') {

                    input.value = row[field]
                        ? row[field].substring(0,10)
                        : '';

                } else {

                    input.value = row[field] ?? '';

                }

            });

            // ===================== ISSUING OFFICE =====================

            if (!document.getElementById('edit_issuing_office').tomselect) {

                new TomSelect('#edit_issuing_office',{
                    create:false,
                    searchField:['text'],
                    placeholder:'Search Issuing Office...'
                });

            }

            document
                .getElementById('edit_issuing_office')
                .tomselect
                .setValue(row.issuing_office ?? '', true);

            // ===================== CLASSIFICATION =====================

            if (!document.getElementById('edit_classifications').tomselect) {

                new TomSelect('#edit_classifications',{
                    create:false,
                    searchField:['text'],
                    placeholder:'Search Classification...'
                });

            }

            document
                .getElementById('edit_classifications')
                .tomselect
                .setValue(row.classification ?? '', true);

            // ===================== UACS =====================

            if (!document.getElementById('edit_uac_codes').tomselect) {

                new TomSelect('#edit_uac_codes',{
                    create:false,
                    searchField:['text','value'],
                    placeholder:'Search UACS Code...'
                });

            }

            document
                .getElementById('edit_uac_codes')
                .tomselect
                .setValue(row.uac_codes ?? '', true);

            bootstrap.Modal.getOrCreateInstance(
                document.getElementById('editModal')
            ).show();

        } catch (error) {

            console.error(error);

            alert(error.message);

        }

    }

    // ===================== EDIT BUTTON =====================

    document.querySelectorAll('.edit-btn').forEach(button => {

        button.addEventListener('click', function () {

            openEditModal(this.dataset.budgetId);

        });

    });
    
    document.getElementById('editForm').addEventListener('submit', function (e) {

        const orsInput = document.getElementById('edit_ors_no');
        const errorBox = document.getElementById('editError');

        errorBox.classList.add('d-none');
        errorBox.innerHTML = '';

        const ors = orsInput.value.trim();

        if (ors !== '' && !/^\d+$/.test(ors)) {

            e.preventDefault();

            errorBox.innerHTML = 'ORS No. must contain numbers only.';
            errorBox.classList.remove('d-none');

            orsInput.focus();

            return;
        }

    }); 

    // DELETE BUTTON
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(){
            const budget_id = this.dataset.budgetId;
            const payee = this.dataset.payee;

            document.getElementById('actionTitle').textContent = 'Delete Record';
            document.getElementById('actionBody').innerHTML =
                `Are you sure you want to delete <strong>${budget_id}</strong>?`;
            document.getElementById('actionFooter').innerHTML = `
                <form method="POST" action="/budget/logbook/${encodeURIComponent(budget_id)}/destroy">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete</button>
                </form>
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
            `;
            bootstrap.Modal.getOrCreateInstance(
                document.getElementById('actionModal')
            ).show();
        });
    });
});

// PRINT DETAILS
function printDetails() {
    const content = document.getElementById('detailsBody').innerHTML;
    const printWindow = window.open('', '_blank');

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Budget Transaction</title>

            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

            <style>

                body{
                    padding:30px;
                    font-family:Arial,sans-serif;
                    font-size:14px;
                }
                h4{
                    text-align:center;
                    margin-bottom:30px;
                }
                hr{
                    margin:20px 0;
                }
                .row{
                    margin-bottom:12px;
                }
                strong{
                    font-weight:600;
                }
                @media print{
                    body{
                        margin:0;
                        padding:20px;
                    }
                    .no-print{
                        display:none;
                    }
                }
            </style>
        </head>
        <body>
            <h4>Accounting Transaction Details</h4>
            ${content}
        </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    },500);
}
</script>