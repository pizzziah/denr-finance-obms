<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        
        <div class="modal-header">
          <h5 class="fw-bold mb-0">Add User</h5>
        </div>
        
        <div class="modal-body">
          <div class="mb-3">
            <label class="fw-bold">Email</label>
              <input type="email" name="email" class="form-control" placeholder="juandelacruz@denr.gov.ph" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Password</label>
            <input type="password" name="password" class="form-control" placeholder="********" required>
          </div>   

          <div class="mb-3">
            <label class="fw-bold">Department</label>
            <select name="department" id="department" class="form-select" required>
              <option value="">Select Department</option>
              <option value="Accounting">Accounting</option>
              <option value="Budget">Budget</option>
              <option value="System Administration">System Administration</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Role</label>
              <select name="role" id="role" class="form-select" required>
                <option value="">Select Role</option>
              </select>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn secondary-bttn" data-bs-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn primary-bttn">
            Save
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const department = document.getElementById('department');
    const role = document.getElementById('role');

    department.addEventListener('change', function () {
      role.innerHTML = '';
        if (this.value === 'System Administration') {
          role.innerHTML =
            '<option value="admin" selected>Admin</option>';
          role.disabled = true;
        } else if (this.value === 'Budget') {
            role.innerHTML =
              '<option value="budget" selected>Budget</option>';
            role.disabled = true;
        } else if (this.value === 'Accounting') {
            role.innerHTML = `
              <option value="accountant">Accountant</option>
              <option value="bookkeeper">Book Keeper</option>`;
            role.disabled = false;
        }
    });
  });
</script>