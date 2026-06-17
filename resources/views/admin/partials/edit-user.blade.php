@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Edit User
    </div>

    <div class="card-body">
        <form
            action="{{ route('admin.users.update', $user->id) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ $user->email }}">
            </div>

            <div class="mb-3">
                <label>Role</label>

                <select
                    name="role"
                    class="form-select">

                    <option
                        value="admin"
                        {{ $user->role == 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>

                    <option
                        value="accountant"
                        {{ $user->role == 'accountant' ? 'selected' : '' }}>
                        Accountant
                    </option>

                    <option
                        value="budget"
                        {{ $user->role == 'budget' ? 'selected' : '' }}>
                        Budget
                    </option>
                </select>
            </div>

            <button
                class="btn btn-success">
                Update User
            </button>
        </form>
    </div>
</div>
@endsection