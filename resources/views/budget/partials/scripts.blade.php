
{{-- ACTION SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.action-btn').forEach(button => {
        button.addEventListener('click', function () {
            let action = this.dataset.action;
            let ors = this.dataset.ors;
            let payee = this.dataset.payee ?? '';
            let status = this.dataset.status ?? '';
            let title = document.getElementById('actionTitle');
            let body = document.getElementById('actionBody');
            let footer = document.getElementById('actionFooter');

            if(action === 'view'){
                title.innerHTML = 'View Transaction';
                body.innerHTML = `
                    <p><strong>ORS No:</strong> ${ors}</p>
                    <p><strong>Payee:</strong> ${payee}</p>
                    <p><strong>Status:</strong> ${status}</p>
                `;

                footer.innerHTML = `
                    <button class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>
                `;
            }

            if(action === 'edit'){

                title.innerHTML = 'Edit Status';

                body.innerHTML = `
                    <form id="editForm"
                          method="POST"
                          action="/accounting/logbook/${payee}/update">

                        @csrf
                        @method('PUT')

                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="Processing">Processing</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </form>
                `;

                footer.innerHTML = `
                    <button form="editForm"
                            class="btn btn-success">
                        Save
                    </button>
                `;
            }

            if(action === 'delete'){
                title.innerHTML = 'Delete Transaction';
                body.innerHTML = `
                    Are you sure you want to delete
                    <strong>${ors}</strong>?
                `;

                footer.innerHTML = `
                    <form method="POST"
                          action="/accounting/logbook/${payee}/destroy">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">
                            Delete
                        </button>
                    </form>
                `;
            }
        });
    });
});
</script>
