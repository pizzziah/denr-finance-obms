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
          <option value="Accounting" {{ $user->department == 'Accounting' ? 'selected' : '' }}> Accounting </option> 
          <option value="Budget" {{ $user->department == 'Budget' ? 'selected' : '' }}> Budget </option> 
          <option value="System Administration" {{ $user->department == 'System Administration' ? 'selected' : '' }}> System Administration </option> 
        </select>    
      </div>  
      
      <div class="mb-3">
        <label class="fw-bold">Role</label>
        <select name="role" id="role" class="form-select" required> </select>
      </div>

      <div class="d-flex gap-2"> 
        <a href="{{ route('admin.users') }}" class="btn secondary-bttn"> Cancel </a> 
        <button type="submit" class="btn primary-bttn"> Save Changes </button> 
      </div>
    </form>
  </div>
</div>

@endsection

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const department = document.getElementById('department');
    const role = document.getElementById('role');

    function loadRoles() {
      role.innerHTML = '';
        if (department.value === 'System Administration') {
          role.innerHTML =
            '<option value="admin" selected>Admin</option>';
          role.disabled = true;
        } else if (department.value === 'Budget') {
            role.innerHTML =
              '<option value="budget" selected>Budget</option>';
            role.disabled = true;
        } else if (department.value === 'Accounting') {
            role.innerHTML = `
              <option value="accountant">Accountant</option>
              <option value="bookkeeper">Book Keeper</option>`;
            role.disabled = false;
        }
          role.value = "{{ $user->role }}";
      }

      loadRoles();

      department.addEventListener('change', loadRoles);
  });
</script>