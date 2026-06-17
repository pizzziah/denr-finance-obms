@extends('layouts.app')

@section('title', 'Users')

@section('content')

<div class="container-fluid p-0">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn gap-2 fw-bold fs-5" style="background-color: var(--primary); color: var(--background);" 
            data-bs-toggle="modal" data-bs-target="#addUserModal">
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
        <h5 class="px-3 pt-3 fw-bold">User Management</h5>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="200">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)

                    <tr>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            @if($user->is_active)
                                Active
                            @else
                                Inactive
                            @endif
                        </td>
                        <td>
                            <a
                                href="{{ route('admin.users.edit', $user->id) }}"
                                class="btn btn-warning btn-sm">
                                Edit
                            </a>
                            <form
                                action="{{ route('admin.users.destroy', $user->id) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-danger btn-sm">
                                    Deactivate
                                </button>
                            </form>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="5">
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
</div>

@include('admin.partials.add-user-modal')

@endsection

@php
    $pageTitle = 'Users';
@endphp