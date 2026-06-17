<div class="modal fade"
     id="addUserModal"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="{{ route('admin.users.store') }}"
                method="POST">

                @csrf

                <div class="modal-header">
                    <h5>Add User</h5>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">

                        <label>Role</label>

                        <select
                            name="role"
                            class="form-select">

                            <option value="admin">
                                Admin
                            </option>

                            <option value="accountant">
                                Accountant
                            </option>

                            <option value="budget">
                                Budget
                            </option>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="submit"
                        class="btn btn-success">

                        Save User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>