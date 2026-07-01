<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-start">
      <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="modal-header">
          <h5 class="fw-bold mb-0">Modify Account Settings</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body">
          <div class="mb-3">
            <label class="fw-bold">Email Coordinate</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
          </div>      
          
          <div class="mb-3">
            <label class="fw-bold">Department Assignment</label>
            <select name="department" id="department_{{ $user->id }}" class="form-select" required> 
              <option value="Accounting" {{ $user->department == 'Accounting' ? 'selected' : '' }}>Accounting</option> 
              <option value="Budget" {{ $user->department == 'Budget' ? 'selected' : '' }}>Budget</option> 
              <option value="System Administration" {{ in_array($user->department, ['System Administration', 'Admin']) ? 'selected' : '' }}>System Administration</option> 
            </select>    
          </div>  
          
          <div class="mb-3">
            <label class="fw-bold">Privilege Role Profiling</label>
            <select name="role" id="role_{{ $user->id }}" class="form-select" required></select>
          </div>

          <div class="mb-3 d-none" id="permission_level_container_{{ $user->id }}">
            <label class="fw-bold text-primary">System Access Permission Level</label>
            <select name="permission_level" id="permission_level_{{ $user->id }}" class="form-select">
              <option value="restricted" {{ $user->permission_level === 'restricted' ? 'selected' : '' }}>Restricted (Normal Operations Only)</option>
              <option value="special" {{ $user->permission_level === 'special' ? 'selected' : '' }}>Special (Can Manage Quarter Locking/Unlocking)</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Update Account Security Key</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to maintain original credential token...">
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Save Structural Updates</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deptSel = document.getElementById('department_{{ $user->id }}');
    const roleSel = document.getElementById('role_{{ $user->id }}');
    const permContainer = document.getElementById('permission_level_container_{{ $user->id }}');
    const permInput = document.getElementById('permission_level_{{ $user->id }}');
    const targetRole = "{{ $user->role }}".toLowerCase().replace(/\s+/g, '');

    function synchronizeRoles() {
      roleSel.innerHTML = '';
      const oldHidden = document.getElementById('hidden-role-edit_{{ $user->id }}');
      if (oldHidden) oldHidden.remove();

      if (deptSel.value === 'System Administration') {
        roleSel.innerHTML = '<option value="admin" selected>Admin</option>';
        roleSel.disabled = true;
        injectHiddenInput('admin');
        permContainer.classList.add('d-none');
        permInput.required = false;
      } else if (deptSel.value === 'Budget') {
        roleSel.innerHTML = '<option value="budget" selected>Budget</option>';
        roleSel.disabled = true;
        injectHiddenInput('budget');
        permContainer.classList.add('d-none');
        permInput.required = false;
      } else if (deptSel.value === 'Accounting') {
        roleSel.innerHTML = `
          <option value="accountant">Accountant</option>
          <option value="bookkeeper">Book Keeper</option>`;
        roleSel.disabled = false;
        roleSel.value = (targetRole === 'bookkeeper' || targetRole === 'accountant') ? targetRole : 'accountant';
        
        // Show permission controls configuration option row choices
        permContainer.classList.remove('d-none');
        permInput.required = true;
      } else {
        permContainer.classList.add('d-none');
        permInput.required = false;
      }
    }

    function injectHiddenInput(val) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'role';
      input.id = 'hidden-role-edit_{{ $user->id }}';
      input.value = val;
      roleSel.form.appendChild(input);
    }

    synchronizeRoles();
    deptSel.addEventListener('change', synchronizeRoles);
});
</script>