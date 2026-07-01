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

                    let html = `

                    <div class="container-fluid">
                    <div class="row">
                        <div class="col-2 fw-bold fs-4">
                            Request<br>Information
                        </div>
                        <div class="col-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>ORS No:</strong> ${row.ors_no ?? '-'}
                                </div>
                                <div class="col-md-4">
                                    <strong>Date Received:</strong> ${row.date_received ?? '-'}
                                </div>
                                <div class="col-md-4">
                                    <strong>Status:</strong> ${row.status ?? '-'}
                                </div>
                                <div class="col-md-4">
                                    <strong>Payee:</strong> ${row.payee ?? '-'}
                                </div>
                                <div class="col-md-4">
                                    <strong>Issuing Office:</strong> ${row.issuing_office ?? '-'}
                                </div>
                                <div class="col-md-12">
                                    <strong>Particulars:</strong> ${row.particulars ?? '-'}
                                </div>
                                <div class="col-md-4">
                                    <strong>Classification:</strong> ${row.classification ?? '-'}
                                </div>
                                <div class="col-md-4]">
                                    <strong>UACS Code:</strong> ${row.uac_codes ?? '-'}
                                </div>
                                <div class="col-md-4 mt-3">
                                    <strong>Amount: </strong> ₱${Number(row.amount ?? 0).toLocaleString(undefined,{
                                        minimumFractionDigits:2,
                                        maximumFractionDigits:2
                                    })}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-1">
                    <div class="row">
                        <div class="col-2 fw-bold fs-4">
                            Returned<br>to End User
                        </div>
                        <div class="col-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Date Returned</strong><br>
                                    ${row.date_returned_1 ?? '-'}
                                </div>
                                <div class="col-md-4">
                                    <strong>Date Received</strong><br>
                                    ${row.date_received_1 ?? '-'}
                                </div>
                                <div class="col-md-4">
                                    <strong>Remarks</strong><br>
                                    ${row.remarks_1 ?? '-'}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-1">
                    <div class="row">
                        <div class="col-2 fw-bold fs-4">
                            Forwarded
                        </div>
                        <div class="col-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Date Forwarded</strong><br>
                                    ${row.date_forwarded_1 ?? '-'}
                                </div>
                                <div class="col-md-4">
                                    <strong>Date ORS Received</strong><br>
                                    ${row.date_ors_received ?? '-'}
                                </div>
                                <div class="col-md-4">
                                    <strong>Remarks</strong><br>
                                    ${row.remarks_2 ?? '-'}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-1">
                    <div class="row">
                        <div class="col-2 fw-bold fs-4">
                            Returned by<br>Accounting
                        </div>
                        <div class="col-10">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Date Returned</strong><br>
                                    ${row.date_returned_2 ?? '-'}
                                </div>
                                <div class="col-md-6">
                                    <strong>Date Received</strong><br>
                                    ${row.date_received_2 ?? '-'}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-1">
                    <div class="row">
                        <div class="col-2 fw-bold fs-4">
                            Forwarded to<br>Accounting
                        </div>
                        <div class="col-10">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Date Forwarded</strong><br>
                                    ${row.date_forwarded_accounting ?? '-'}
                                </div>
                                <div class="col-md-4">
                                    <strong>Total Time in Budget</strong><br>
                                    ${row.total_time_budget ?? '-'}
                                </div>
                                <div class="col-md-4">
                                    <strong>Total Time</strong><br>
                                    ${row.total_time ?? '-'}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-1">
                    <div class="row">
                        <div class="col-2 fw-bold fs-4">
                            Final<br>Remarks
                        </div>
                        <div class="col-10">
                            <strong>Remarks</strong><br>
                            ${row.final_remarks ?? '-'}
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

    // EDIT BUTTON
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', async function () {
            const budget_id = this.dataset.budgetId;

            try{
                const row = await getRecord(budget_id);
                document.getElementById('editForm').action =
                    `/budget/logbook/${encodeURIComponent(budget_id)}/update`;
                [
                    'ors_no',
                    'date_received',
                    'payee',
                    'issuing_office',
                    'classification',
                    'particulars',
                    'uac_codes',
                    'amount',
                    'date_returned_1',
                    'date_received_1',
                    'remarks_1',
                    'date_forwarded_1',
                    'date_ors_received',
                    'remarks_2',
                    'status',
                    'final_remarks'
                ].forEach(field => {
                    const input = document.getElementById('edit_' + field);
                    if(input){
                        input.value = row[field] ?? '';
                    }
                });

                bootstrap.Modal.getOrCreateInstance(
                    document.getElementById('editModal')
                ).show();

            }catch(error){
                alert('Unable to load record.');
            }
        });
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