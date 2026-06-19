@extends('layouts.app')
@section('title', 'Edit User')
@section('content')

<div class="card">
  <div class="card-header">
    <h5 class="fw-bold mb-0">Edit User</h5>
  </div>

  <div class="card-body">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
      @csrf
      @method('PUT')
      
      <div class="mb-3">
        <label class="fw-bold">Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
      </div>      
      
      <div class="mb-3">
        <label class="fw-bold">Department</label>
        <select name="department" id="department" class="form-select" required> 
          <option value="Accounting" {{ ($user->department == 'Accounting') ? 'selected' : '' }}> Accounting </option> 
          <option value="Budget" {{ ($user->department == 'Budget') ? 'selected' : '' }}> Budget </option> 
          <option value="System Administration" {{ ($user->department == 'System Administration' || $user->department == 'Admin') ? 'selected' : '' }}> System Administration </option> 
        </select>    
      </div>  
      
      <div class="mb-3">
        <label class="fw-bold">Role</label>
        <select name="role" id="role" class="form-select" required> </select>
      </div>

      <div class="mb-3">
        <label class="fw-bold">Password</label>
        <input type="password" name="password" class="form-control" placeholder="********">
        <small class="text-muted fw-normal">Leave blank to keep current password</small>
      </div>

      <div class="d-flex gap-2"> 
        <x-button variant="secondary" as="a"
          href="{{ route('admin.users') }}">
          Cancel
        </x-button>
        <x-button variant="primary" type="submit">
            Save Changes
        </x-button>
      </div>
    </form>
  </div>
</div>

@endsection

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const department = document.getElementById('department');
    const role = document.getElementById('role');
    
    const currentSavedRole = "{{ $user->role }}".toLowerCase().replace(/\s+/g, '');

    function loadRoles() {
      role.innerHTML = '';
      
      const existingHidden = document.getElementById('hidden-role');
      if (existingHidden) existingHidden.remove();

      if (department.value === 'System Administration') {
        role.innerHTML = '<option value="admin" selected>Admin</option>';
        role.disabled = true;
        createHiddenInput('admin');

      } else if (department.value === 'Budget') {
        role.innerHTML = '<option value="budget" selected>Budget</option>';
        role.disabled = true;
        createHiddenInput('budget');

      } else if (department.value === 'Accounting') {
        role.innerHTML = `
          <option value="">Select Role</option>
          <option value="accountant">Accountant</option>
          <option value="bookkeeper">Book Keeper</option>`;
        role.disabled = false;
        
        if (currentSavedRole === 'accountant' || currentSavedRole === 'bookkeeper') {
          role.value = currentSavedRole;
        }
      }
    }

    function createHiddenInput(value) {
      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'role';
      hiddenInput.id = 'hidden-role';
      hiddenInput.value = value;
      role.form.appendChild(hiddenInput);
    }

    loadRoles();
    department.addEventListener('change', loadRoles);
  });
</script>