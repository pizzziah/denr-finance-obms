
{{-- ACTION SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.action-btn').forEach(button => {
        button.addEventListener('click', function () {
            let action = this.dataset.action;
            let dv = this.dataset.dv;
            let obr = this.dataset.obr ?? '';
            let payee = this.dataset.payee ?? '';
            let status = this.dataset.status ?? '';
            let title = document.getElementById('actionTitle');
            let body = document.getElementById('actionBody');
            let footer = document.getElementById('actionFooter');
            let entries = this.dataset.entries;
            let amount = this.dataset.amount;
            const safeDv = encodeURIComponent(dv);

            if(action === 'view'){
                title.innerHTML = 'View Transaction';
                body.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>DV No.</strong><br>${dv}
                    </div>

                    <div class="col-md-6">
                        <strong>Status</strong><br>${status}
                    </div>

                    <div class="col-md-12 mt-3">
                        <strong>Payee</strong><br>${payee}
                    </div>

                    <div class="col-md-12 mt-3">
                        <strong>Total Accounting Entries</strong><br>
                        <span class="badge bg-primary">${entries}</span>
                    </div>
                    <div class="col-md-6 mt-3">
                        <strong>Total Amount</strong><br>
                        ₱${Number(amount).toLocaleString(undefined,{
                            minimumFractionDigits:2,
                            maximumFractionDigits:2
                        })}
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="alert alert-info mb-0">
                            Click <strong>Open Details</strong> to view every UACS, Debit, Credit and Tax entry.
                        </div>
                    </div>
                </div>
                `;

                footer.innerHTML = `
                <button
                    class="btn btn-primary"
                    onclick="openDetails('${safeDv}')">
                    Open Details
                </button>

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Close
                </button>
                `;
            }

            if (action === 'edit') {

                fetch('/accounting/logbook/' + encodeURIComponent(dv) + '/edit')
                .then(response => response.json())
                .then(row => {

                    document.getElementById('editForm').action =
                        '/accounting/logbook/' + encodeURIComponent(dv) + '/update';

                    const fields = [
                        'ors_no',
                        'obr_no',
                        'dv_no',
                        'payee',
                        'particulars',
                        'particulars_remark',
                        'uac_codes',
                        'debit',
                        'credit',
                        'tax_percent',
                        'tax_remarks',
                        'returned_remarks',
                        'signed_by_accountant',
                        'status',
                        'budget_year',
                        'source_month',
                        'date_received',
                        'date_processed',
                        'obr_date',
                        'date_signed',
                        'date_forwarded'
                    ];

                    fields.forEach(field => {
                        const input = document.getElementById('edit_' + field);

                        if (input) {
                            input.value = row[field] ?? '';
                        }
                    });

                    bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('editModal')
                    ).show();
                });
            }

            if(action === 'delete'){
                title.innerHTML = 'Delete Transaction';
                body.innerHTML = `Are you sure you want to delete <strong>${dv}</strong>?`;
                footer.innerHTML = `
                    <form method="POST" action="/accounting/logbook/${dv}/destroy">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">Delete</button>
                    </form>
                `;
            }
        });
    });
});
function openDetails(dv) {

    // close first modal
    bootstrap.Modal.getInstance(
        document.getElementById('actionModal')
    ).hide();

    // open second modal
    const detailsModal =
        new bootstrap.Modal(
            document.getElementById('detailsModal')
        );

    detailsModal.show();

    // loading
    document.getElementById('detailsBody').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-success"></div>
        </div>
    `;

    fetch('/accounting/logbook/' + encodeURIComponent(dv) + '/details')
        .then(response => response.json())
        .then(data => {

            let summary = data.summary;
            let rows = data.details;

            let html = `

            <div class="container-fluid">
            <hr>
            <div class="row">

                <div class="col-2 fw-bold fs-4">
                    Record<br>Information
                </div>

                <div class="col-10">
                    <div class="row">

                        <div class="col-md-3">
                            <strong>Date Received:</strong> ${rows[0].date_received ?? '-'}
                        </div>

                        <div class="col-md-3">
                            <strong>OBR Date:</strong> ${rows[0].obr_date ?? '-'}
                        </div>

                        <div class="col-md-6">
                            <strong>Payee:</strong> ${summary.payee}
                        </div>

                        <div class="col-md-3 mt-3">
                            <strong>OBR No.:</strong> ${rows[0].obr_no}
                        </div>

                        <div class="col-md-9 mt-3">
                            <strong>Particulars:</strong> ${rows[0].particulars}
                        </div>

                        <div class="col-md-12 mt-3">
                            <strong>Particular Remark:</strong> ${rows[0].particulars_remark ?? '-'}
                        </div>

                    </div>
                </div>
            </div>
            <hr>

            <div class="row">

            <div class="col-2 fw-bold fs-4">
            Accounting<br>Processing
            </div>

            <div class="col-10">
            `;

            rows.forEach(row => {

            html += `

            <div class="border-bottom pb-3 mb-3">
            <div class="row">

            <div class="col-md-3">
            <strong>Date Processed:</strong> ${row.date_processed ?? '-'}
            </div>

            <div class="col-md-2">
            <strong>DV No.:</strong> ${row.dv_no}
            </div>

            <div class="col-md-3">
            <strong>UACS Code:</strong> ${row.uac_codes ?? '-'}
            </div>

            <div class="col-md-4">
            <strong>Debit:</strong> ₱${Number(row.debit ?? 0).toLocaleString(undefined,{
            minimumFractionDigits:2
            })}
            </div>

            <div class="col-md-4 mt-3">
            <strong>Credit:</strong> ₱${Number(row.credit ?? 0).toLocaleString(undefined,{
            minimumFractionDigits:2
            })}
            </div>

            <div class="col-md-4 mt-3">
            <strong>% Tax:</strong> ${row.tax_percent ?? '-'}
            </div>

            <div class="col-md-4 mt-3">
            <strong>Tax Remarks:</strong> ${row.tax_remarks ?? '-'}
            </div>

            </div>
            </div>
            `;

            });

            html += `
                    </tbody>
                </table>
            `;
            html += `

            <hr>

            <div class="row mb-4">

                <div class="col-3">
                    <h5 class="fw-bold">Signature</h5>
                </div>

                <div class="col-9">
                    <div class="row">

                        <div class="col-md-4">
                            <strong>Signed:</strong> ${rows[0].signed_by_accountant ?? '-'}
                        </div>

                        <div class="col-md-4">
                            <strong>Date Signed:</strong> ${rows[0].date_signed ?? '-'}
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <div class="row">

                <div class="col-3">
                    <h5 class="fw-bold">Routing & Status</h5>
                </div>

                <div class="col-9">
                    <div class="row">

                        <div class="col-md-4">
                            <strong>Status:</strong> ${summary.status}
                        </div>

                        <div class="col-md-4">
                            <strong>Date Forwarded:</strong> ${rows[0].date_forwarded ?? '-'}
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
                    Unable to load transaction details.
                </div>
            `;

        });

}
</script>