@extends('layouts.app')
@section('title', 'Users')
@section('content')
<div class="container-fluid p-0 m-0">
  <div class="d-flex mb-3">
    <button class="btn header-bttn" data-bs-toggle="modal" data-bs-target="#addUserModal">
      <i class="bi bi-person-fill-add"></i>
      Add User
    </button>
  </div>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <div class="card">
    <h5 class="px-3 pt-3 fw-bold pb-0 m-0">
      Manage Users
    </h5>

    <div class="card-body">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th>Email</th>
            <th>Department</th>
            <th>Role</th>
            <th>Status</th>
            <th width="220">Actions</th>
          </tr>
        </thead>

        <tbody>
          @forelse($users as $user)

          <tr>
            <td>{{ $user->email }}</td>
            <td>{{ $user->department }}</td>
            <td> {{ match(strtolower(str_replace(' ', '', $user->role))) {
              'admin' => 'Admin',
              'accountant' => 'Accountant',
              'budget' => 'Budget',
              'bookkeeper' => 'Book Keeper',
              default => ucwords($user->role),
              }
            }}
            </td>
            <td>
              @if($user->is_active === 'active')
                <span class="p-2 rounded success-bttn">Active</span>
              @else
                <span class="p-2 rounded alert-bttn">Inactive</span>
              @endif
            </td>
            <td class="gap-2"> 
              <a href="{{ route('admin.users.edit', $user->id) }}" class="btn edit-bttn me-2">
                <i class="bi bi-pencil-square fs-5"></i>
              </a>

              @if(auth()->id() != $user->id)

              <form action="{{ route('admin.users.destroy', $user->id) }}"
                method="POST"
                class="d-inline">

                @csrf
                @method('DELETE')

                @if($user->is_active === 'active')
                  <button type="submit" class="btn alert-bttn">
                    <i class="bi bi-person-fill-lock fs-5"></i>
                  </button>
                @else
                  <button type="submit" class="btn success-bttn">
                    <i class="bi bi-person-check-fill fs-5"></i>
                  </button>
                @endif
              </form>
              @endif
            </td>
          </tr>

          @empty

          <tr>
            <td colspan="5" class="text-center">No users found.</td>
          </tr>

          @endforelse

        </tbody>
      </table>

      <div class="mt-3">
        {{ $users->links() }}
      </div>
    </div>

    
  </div>
</div>

@include('admin.partials.add-user-modal')
@endsection

@php
  $pageTitle = 'Users';
@endphp